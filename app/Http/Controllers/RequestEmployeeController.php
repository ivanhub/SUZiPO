<?php

namespace App\Http\Controllers;

use App\Models\Request as TrainingRequestModel;
use App\Models\RequestEmployee;
use App\Models\AllUserSap;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestEmployeeController extends Controller
{
    /**
     * Показать сотрудников заявки
     */
    public function index(Request $request, int $requestId): View
    {
        $trainingRequest = TrainingRequestModel::with('course')->findOrFail($requestId);
        
        $employees = RequestEmployee::where('request_id', $requestId)
            ->with('userSap')
            ->orderBy('created_at')
            ->get();

        return view('request_employees.index', compact('trainingRequest', 'employees'));
    }

    /**
     * Добавить сотрудника
     */

public function store(Request $request, int $requestId): RedirectResponse
{
    // Валидация для обоих вариантов
    $request->validate([
        'tab_number' => 'nullable|string|max:50',
        'last_name' => 'nullable|string|max:255',
        'first_name' => 'nullable|string|max:255',
        'middle_name' => 'nullable|string|max:255',
    ]);

    $trainingRequest = TrainingRequestModel::with('course')->findOrFail($requestId);

    $data = [
        'request_id' => $requestId,
        'status' => 'active',
    ];

    if ($request->filled('tab_number')) {
        // Добавление по табельному номеру
        $userSap = AllUserSap::where('tab_number', $request->tab_number)->first();
        
        if (!$userSap) {
            return redirect()
                ->back()
                ->with('error', "Сотрудник с табельным номером {$request->tab_number} не найден в SAP. Возможно он уволен.");
        }

        $data['user_sap_id'] = $userSap->id;
        
        // Проверка на дубликат в прошлых заявках (пункт 7)
        $duplicateCheck = $this->checkDuplicate($userSap, $trainingRequest);
        if ($duplicateCheck['duplicate']) {
            return redirect()
                ->back()
                ->with('error', $duplicateCheck['message']);
        }
        
    } elseif ($request->filled('last_name') || $request->filled('first_name')) {
        // Ручной ввод
        $data['last_name'] = $request->last_name;
        $data['first_name'] = $request->first_name;
        $data['middle_name'] = $request->middle_name;
    } else {
        return redirect()
            ->back()
            ->with('error', 'Введите табельный номер или ФИО сотрудника.');
    }

    RequestEmployee::create($data);

    return redirect()
        ->route('request-employees.index', $requestId)
        ->with('success', 'Сотрудник добавлен в заявку.');
}

    /**
     * Проверка дубликата (пункт 7)
     */
    private function checkDuplicate(AllUserSap $userSap, TrainingRequestModel $currentRequest): array
    {
        $courseId = $currentRequest->course_id;
        
        // Ищем прошлые заявки с той же программой обучения
        $pastRequests = TrainingRequestModel::where('course_id', $courseId)
            ->where('id', '!=', $currentRequest->id)
            ->pluck('id');
            
        if ($pastRequests->isEmpty()) {
            return ['duplicate' => false];
        }

        // Ищем сотрудника в этих заявках
        $pastEmployee = RequestEmployee::whereIn('request_id', $pastRequests)
            ->where('user_sap_id', $userSap->id)
            ->first();

        if (!$pastEmployee) {
            return ['duplicate' => false];
        }

        // Проверяем должность
        if ($pastEmployee->userSap && $pastEmployee->userSap->position === $userSap->position) {
            return [
                'duplicate' => true,
                'message' => "Сотрудник {$userSap->full_name} уже проходил это обучение."
            ];
        }

        return ['duplicate' => false];
    }

    /**
     * Удалить сотрудника из заявки
     */
    public function destroy(int $requestId, int $employeeId): RedirectResponse
    {
        $employee = RequestEmployee::where('request_id', $requestId)
            ->where('id', $employeeId)
            ->firstOrFail();

        $employee->delete();

        return redirect()
            ->route('request-employees.index', $requestId)
            ->with('success', 'Сотрудник удален из заявки.');
    }

    /**
     * Обновить дополнительные поля сотрудника
     */
    public function update(Request $request, int $requestId, int $employeeId): RedirectResponse
    {
        $employee = RequestEmployee::where('request_id', $requestId)
            ->where('id', $employeeId)
            ->firstOrFail();

        $validated = $request->validate([
            'absence_start_date' => 'nullable|date',
            'absence_end_date' => 'nullable|date',
            'absence_type' => 'nullable|string|max:50',
            'distance_learning_date' => 'nullable|date',
            'fulltime_learning_date' => 'nullable|date',
            'note' => 'nullable|string|max:1000',
            'document_issue_date' => 'nullable|date',
            'reissue_period' => 'nullable|string|max:50',
        ]);

        $employee->update($validated);

        // Проверка на просроченность (пункт 9)
        if ($employee->isDocumentExpired()) {
            $employee->update([
                'status' => 'expired',
                'warning_type' => 'expired',
                'warning_message' => 'Документ об обучении просрочен'
            ]);
        } else {
            $employee->update([
                'status' => 'active',
                'warning_type' => null,
            ]);
        }

        return redirect()
            ->route('request-employees.index', $requestId)
            ->with('success', 'Данные сотрудника обновлены.');
    }

/**
 * Пакетное добавление сотрудников по табельным номерам
 */
public function storeBulk(Request $request, int $requestId): RedirectResponse
{
    $request->validate([
        'tab_numbers' => 'required|string',
    ]);

    $trainingRequest = TrainingRequestModel::with('course')->findOrFail($requestId);
    
    // Разбираем табельные номера
    $tabNumbers = array_filter(array_map('trim', explode("\n", $request->tab_numbers)));
    $tabNumbers = array_filter($tabNumbers, fn($v) => $v !== '');
    
    if (empty($tabNumbers)) {
        return redirect()
            ->back()
            ->with('error', 'Введите хотя бы один табельный номер.');
    }

    // Ищем сотрудников в SAP
    $usersSap = AllUserSap::whereIn('tab_number', $tabNumbers)->get()->keyBy('tab_number');
    
    $addedCount = 0;
    $notFound = [];
    $errors = [];

    foreach ($tabNumbers as $tabNumber) {
        $userSap = $usersSap->get($tabNumber);
        
        if (!$userSap) {
            $notFound[] = $tabNumber;
            continue;
        }

        // Проверка на дубликат в этой же заявке
        $existsInCurrent = RequestEmployee::where('request_id', $requestId)
            ->where('user_sap_id', $userSap->id)
            ->exists();

        if ($existsInCurrent) {
            $errors[] = "Сотрудник {$userSap->full_name} (таб. №{$tabNumber}) уже добавлен в эту заявку.";
            continue;
        }

        // Проверка на дубликат в прошлых заявках (пункт 7)
        $duplicateCheck = $this->checkDuplicate($userSap, $trainingRequest);
        if ($duplicateCheck['duplicate']) {
            $errors[] = $duplicateCheck['message'];
            continue;
        }

        // Добавляем сотрудника
        RequestEmployee::create([
            'request_id' => $requestId,
            'user_sap_id' => $userSap->id,
            'status' => 'active',
        ]);
        
        $addedCount++;
    }

    // Формируем сообщение
    $message = "Добавлено сотрудников: {$addedCount}.";
    
    if (!empty($notFound)) {
        $message .= " Не найдены в SAP: " . implode(', ', array_slice($notFound, 0, 5));
        if (count($notFound) > 5) {
            $message .= " и еще " . (count($notFound) - 5) . "...";
        }
    }
    
    if (!empty($errors)) {
        $message .= " Ошибки: " . implode('; ', array_slice($errors, 0, 3));
        if (count($errors) > 3) {
            $message .= " и еще " . (count($errors) - 3) . "...";
        }
    }

    if ($addedCount > 0) {
        return redirect()
            ->route('request-employees.index', $requestId)
            ->with('success', $message);
    } else {
        return redirect()
            ->route('request-employees.index', $requestId)
            ->with('error', $message);
    }
}

}