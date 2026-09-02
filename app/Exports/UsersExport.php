<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $search;
    protected $roleFilter;
    protected $filterId;
    protected $filterName;
    protected $filterEmail;
    protected $filterEmployeeId;
    protected $filterPosition;
    protected $filterDepartment;
    protected $filterCreatedAt;
    protected $filterRoles;
    protected $selectedUserIds;

    public function __construct(
        $selectedUserIds = null,
        $search = null,
        $roleFilter = null,
        $filterId = null,
        $filterName = null,
        $filterEmail = null,
        $filterEmployeeId = null,
        $filterPosition = null,
        $filterDepartment = null,
        $filterCreatedAt = null,
        $filterRoles = null
    ) {
        $this->selectedUserIds = $selectedUserIds;
        $this->search = $search;
        $this->roleFilter = $roleFilter;
        $this->filterId = $filterId;
        $this->filterName = $filterName;
        $this->filterEmail = $filterEmail;
        $this->filterEmployeeId = $filterEmployeeId;
        $this->filterPosition = $filterPosition;
        $this->filterDepartment = $filterDepartment;
        $this->filterCreatedAt = $filterCreatedAt;
        $this->filterRoles = $filterRoles;
    }

    public function collection(): Collection
    {
        $query = User::with('roles');

        // Если выбраны конкретные пользователи
        if ($this->selectedUserIds) {
            $query->whereIn('id', $this->selectedUserIds);
        }

        // Применяем фильтры
        if ($this->search) {
            $s = mb_strtolower($this->search);
            $query->where(function ($q) use ($s) {
                $q->whereRaw('LOWER("id"::text) LIKE ?', ["%{$s}%"])
                  ->orWhereRaw('LOWER("name") LIKE ?', ["%{$s}%"])
                  ->orWhereRaw('LOWER("email") LIKE ?', ["%{$s}%"])
                  ->orWhereRaw('LOWER("employee_id") LIKE ?', ["%{$s}%"])
                  ->orWhereRaw('LOWER("position") LIKE ?', ["%{$s}%"])
                  ->orWhereRaw('LOWER("department") LIKE ?', ["%{$s}%"]);
            });
        }

        if ($this->filterId) {
            $query->where('id', $this->filterId);
        }

        if ($this->filterName) {
            $query->whereRaw('LOWER("name") LIKE ?', ['%' . mb_strtolower($this->filterName) . '%']);
        }

        if ($this->filterEmail) {
            $query->whereRaw('LOWER("email") LIKE ?', ['%' . mb_strtolower($this->filterEmail) . '%']);
        }

        if ($this->filterEmployeeId) {
            $query->whereRaw('LOWER("employee_id") LIKE ?', ['%' . mb_strtolower($this->filterEmployeeId) . '%']);
        }

        if ($this->filterPosition) {
            $query->whereRaw('LOWER("position") LIKE ?', ['%' . mb_strtolower($this->filterPosition) . '%']);
        }

        if ($this->filterDepartment) {
            $query->whereRaw('LOWER("department") LIKE ?', ['%' . mb_strtolower($this->filterDepartment) . '%']);
        }

        if ($this->filterCreatedAt) {
            $query->whereDate('created_at', $this->filterCreatedAt);
        }

        if ($this->filterRoles) {
            $query->role($this->filterRoles);
        }

        if ($this->roleFilter) {
            $query->role($this->roleFilter);
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Имя',
            'Email',
            'Сотрудник ID',
            'Должность',
            'Отдел',
            'Роли',
            'Дата создания',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->employee_id ?? '—',
            $user->position ?? '—',
            $user->department ?? '—',
            $user->roles->pluck('name')->implode(', '),
            $user->created_at->format('d.m.Y H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}