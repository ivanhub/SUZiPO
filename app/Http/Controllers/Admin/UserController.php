<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{
    /**
     * Display a listing of users with search and filters.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $filterId = $request->input('filter_id');
        $filterName = $request->input('filter_name');
        $filterEmail = $request->input('filter_email');
        $filterEmployeeId = $request->input('filter_employee_id');
        $filterPosition = $request->input('filter_position');
        $filterDepartment = $request->input('filter_department');
        $filterCreatedAt = $request->input('filter_created_at');
        $filterRoles = $request->input('filter_roles');

        $hasActiveFilters = $search || $roleFilter || $filterId || $filterName || $filterEmail || 
                            $filterEmployeeId || $filterPosition || $filterDepartment || 
                            $filterCreatedAt || $filterRoles;

        $users = User::query()
            ->with('roles')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $s = mb_strtolower($search);
                    $q->whereRaw('LOWER("id"::text) LIKE ?', ["%{$s}%"])
                      ->orWhereRaw('LOWER("name") LIKE ?', ["%{$s}%"])
                      ->orWhereRaw('LOWER("email") LIKE ?', ["%{$s}%"])
                      ->orWhereRaw('LOWER("employee_id") LIKE ?', ["%{$s}%"])
                      ->orWhereRaw('LOWER("position") LIKE ?', ["%{$s}%"])
                      ->orWhereRaw('LOWER("department") LIKE ?', ["%{$s}%"]);
                });
            })
            ->when($filterId, function ($query, $filterId) {
                return $query->where('id', $filterId);
            })
            ->when($filterName, function ($query, $filterName) {
                return $query->whereRaw('LOWER("name") LIKE ?', ['%' . mb_strtolower($filterName) . '%']);
            })
            ->when($filterEmail, function ($query, $filterEmail) {
                return $query->whereRaw('LOWER("email") LIKE ?', ['%' . mb_strtolower($filterEmail) . '%']);
            })
            ->when($filterEmployeeId, function ($query, $filterEmployeeId) {
                return $query->whereRaw('LOWER("employee_id") LIKE ?', ['%' . mb_strtolower($filterEmployeeId) . '%']);
            })
            ->when($filterPosition, function ($query, $filterPosition) {
                return $query->whereRaw('LOWER("position") LIKE ?', ['%' . mb_strtolower($filterPosition) . '%']);
            })
            ->when($filterDepartment, function ($query, $filterDepartment) {
                return $query->whereRaw('LOWER("department") LIKE ?', ['%' . mb_strtolower($filterDepartment) . '%']);
            })
            ->when($filterCreatedAt, function ($query, $filterCreatedAt) {
                return $query->whereDate('created_at', $filterCreatedAt);
            })
            ->when($filterRoles, function ($query, $filterRoles) {
                return $query->role($filterRoles);
            })
            ->when($roleFilter, function ($query, $roleFilter) {
                return $query->role($roleFilter);
            })
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $roles = Role::all();

        return view('admin.users.index', compact(
            'users', 'search', 'roleFilter', 'roles',
            'filterId', 'filterName', 'filterEmail', 'filterEmployeeId',
            'filterPosition', 'filterDepartment', 'filterCreatedAt', 'filterRoles',
            'hasActiveFilters'
        ));
    }

    /**
     * Show form for creating a new user.
     */
    public function create(): View
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        if (!empty($validated['roles'])) {
            $roles = Role::whereIn('id', $validated['roles'])->pluck('name');
            $user->assignRole($roles);
        }

        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->pluck('name');
            $user->givePermissionTo($permissions);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь создан успешно.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show form for editing user.
     */
    public function edit(User $user): View
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();
        
        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }

        if (isset($validated['roles'])) {
            $roles = Role::whereIn('id', $validated['roles'])->pluck('name');
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->pluck('name');
            $user->syncPermissions($permissions);
        } else {
            $user->syncPermissions([]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь обновлён успешно.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Вы не можете удалить самого себя.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь удалён.');
    }

    /**
     * Экспорт всех пользователей в Excel (с учётом поиска)
     */
    public function export(Request $request): BinaryFileResponse
    {
        $export = new UsersExport(
            null, // selectedUserIds
            $request->input('search'),
            $request->input('role'),
            $request->input('filter_id'),
            $request->input('filter_name'),
            $request->input('filter_email'),
            $request->input('filter_employee_id'),
            $request->input('filter_position'),
            $request->input('filter_department'),
            $request->input('filter_created_at'),
            $request->input('filter_roles')
        );

        return Excel::download($export, 'users_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * Экспорт выбранных пользователей в Excel
     */
    public function exportSelected(Request $request): BinaryFileResponse
    {
        $selectedUsers = $request->input('selected_users', []);
        
        if (empty($selectedUsers)) {
            return redirect()->back()->with('error', 'Не выбрано ни одного пользователя.');
        }

        return Excel::download(new UsersExport($selectedUsers), 'selected_users_' . date('Y-m-d_His') . '.xlsx');
    }
}