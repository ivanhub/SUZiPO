<?php

namespace App\Http\Controllers;

use App\Models\AllUserSap;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AllUserSapController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $users = AllUserSap::when($search, function ($query, $search) {
            return $query->where('last_name', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('middle_name', 'like', "%{$search}%")
                ->orWhere('tab_number', 'like', "%{$search}%")
                ->orWhere('position', 'like', "%{$search}%");
        })
        ->orderBy('last_name')
        ->paginate(20);

        return view('all_users_sap.index', compact('users', 'search'));
    }

    public function create(): View
    {
        return view('all_users_sap.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tab_number' => 'nullable|integer',
            'last_name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'gender_key' => 'nullable|string|max:20',
            'pfr_certificate' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:255',
            'rank' => 'nullable|string|max:50',
            'level_4_name' => 'nullable|string|max:500',
            'level_3_name' => 'nullable|string|max:500',
            'duv_b' => 'nullable|string|max:50',
            'mvz' => 'nullable|string|max:50',
            'employee_category' => 'nullable|string|max:100',
        ]);

        AllUserSap::create($validated);

        return redirect()->route('all-users-sap.index')
            ->with('success', 'Запись успешно добавлена.');
    }

    public function edit(AllUserSap $allUserSap): View
    {
        return view('all_users_sap.edit', compact('allUserSap'));
    }

    public function update(Request $request, AllUserSap $allUserSap): RedirectResponse
    {
        $validated = $request->validate([
            'tab_number' => 'nullable|integer',
            'last_name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'gender_key' => 'nullable|string|max:20',
            'pfr_certificate' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:255',
            'rank' => 'nullable|string|max:50',
            'level_4_name' => 'nullable|string|max:500',
            'level_3_name' => 'nullable|string|max:500',
            'duv_b' => 'nullable|string|max:50',
            'mvz' => 'nullable|string|max:50',
            'employee_category' => 'nullable|string|max:100',
        ]);

        $allUserSap->update($validated);

        return redirect()->route('all-users-sap.index')
            ->with('success', 'Запись успешно обновлена.');
    }

    public function destroy(AllUserSap $allUserSap): RedirectResponse
    {
        $allUserSap->delete();

        return redirect()->route('all-users-sap.index')
            ->with('success', 'Запись успешно удалена.');
    }

    /**
     * Загрузка и импорт файла Excel
     */

public function import(Request $request)
{
    set_time_limit(300);
    ini_set('memory_limit', '512M');
    
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
    ]);

    try {
        $file = $request->file('file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        $count = 0;
        $errors = [];
        $chunkSize = 500;
        $data = [];

        foreach ($rows as $key => $row) {
            if ($key == 1) continue;

            if (empty($row['A']) && empty($row['B']) && empty($row['C'])) continue;

            try {
                $birthDate = null;
                if (!empty($row['E']) && is_numeric($row['E'])) {
                    $birthDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$row['E'])->format('Y-m-d');
                }

                $data[] = [
                    'tab_number' => !empty($row['A']) ? (int)$row['A'] : null,
                    'last_name' => $row['B'] ?? null,
                    'first_name' => $row['C'] ?? null,
                    'middle_name' => $row['D'] ?? null,
                    'birth_date' => $birthDate,
                    'gender' => $row['F'] ?? null,
                    'gender_key' => $row['G'] ?? null,
                    'pfr_certificate' => $row['H'] ?? null,
                    'position' => $row['I'] ?? null,
                    'rank' => !empty($row['J']) ? (string)$row['J'] : null,
                    'level_4_name' => $row['K'] ?? null,
                    'level_3_name' => $row['L'] ?? null,
                    'duv_b' => $row['M'] ?? null,
                    'mvz' => $row['N'] ?? null,
                    'employee_category' => $row['O'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $count++;

                if (count($data) >= $chunkSize) {
                    AllUserSap::insert($data);
                    $data = [];
                }

            } catch (\Exception $e) {
                $errors[] = "Ошибка в строке " . ($key + 1) . ": " . $e->getMessage();
            }
        }

        if (count($data) > 0) {
            AllUserSap::insert($data);
        }

        unset($rows, $data, $spreadsheet);

        return response()->json([
            'success' => true,
            'count' => $count,
            'errors' => $errors
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Ошибка при импорте файла: ' . $e->getMessage()
        ], 500);
    }
}

}