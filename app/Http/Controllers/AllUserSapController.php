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
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
    ]);

    try {
        $file = $request->file('file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Пропускаем заголовки (первая строка)
        $count = 0;
        $errors = [];

        foreach ($rows as $key => $row) {
            if ($key == 0) continue; // Пропускаем заголовки

            // Пропускаем пустые строки
            if (empty($row[0]) && empty($row[1]) && empty($row[2])) continue;

            try {
                // Конвертация даты из Excel (число) в формат Y-m-d
                $birthDate = null;
                if (!empty($row[4]) && is_numeric($row[4])) {
                    $birthDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$row[4])->format('Y-m-d');
                }

                AllUserSap::create([
                    'tab_number' => !empty($row[0]) ? (int)$row[0] : null,
                    'last_name' => $row[1] ?? null,
                    'first_name' => $row[2] ?? null,
                    'middle_name' => $row[3] ?? null,
                    'birth_date' => $birthDate,
                    'gender' => $row[5] ?? null,
                    'gender_key' => $row[6] ?? null,
                    'pfr_certificate' => $row[7] ?? null,
                    'position' => $row[8] ?? null,
                    'rank' => !empty($row[9]) ? (string)$row[9] : null,
                    'level_4_name' => $row[10] ?? null,
                    'level_3_name' => $row[11] ?? null,
                    'duv_b' => $row[12] ?? null,
                    'mvz' => $row[13] ?? null,
                    'employee_category' => $row[14] ?? null,
                ]);

                $count++;
            } catch (\Exception $e) {
                $errors[] = "Ошибка в строке " . ($key + 1) . ": " . $e->getMessage();
            }
        }

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