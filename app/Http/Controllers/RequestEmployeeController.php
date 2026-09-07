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

        // Проверяем каждого сотрудника
        foreach ($employees as $employee) {
            $this->applyChecks($employee, $trainingRequest);
        }

        return view('request_employees.index', compact('trainingRequest', 'employees'));
    }

    /**
     * Добавить сотрудника
     */
    public function store(Request $request, int $requestId): RedirectResponse
    {
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
        $userSap = AllUserSap::where('tab_number', $request->tab_number)->first();
        
        if (!$userSap) {
            // Пункт 8: Сотрудник не найден
            $data['status'] = 'dismissed';
            $data['warning_type'] = 'dismissed';
            $data['warning_message'] = 'Сотрудник не найден в SAP. Возможно уволен.';
        } else {
            $data['user_sap_id'] = $userSap->id;
            
            // Пункт 7: Проверка на дубликат
            $duplicateCheck = $this->checkDuplicate($userSap, $trainingRequest);
            if ($duplicateCheck['duplicate']) {
                // НЕ добавляем сотрудника, а просто возвращаем ошибку
                return redirect()
                    ->back()
                    ->with('error', $duplicateCheck['message']);
            }
            
            // Если не дубликат - добавляем как активного
            $data['status'] = 'active';
        }
    }


 elseif ($request->filled('last_name') || $request->filled('first_name')) {
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
     * Пакетное добавление сотрудников
     */
    public function storeBulk(Request $request, int $requestId): RedirectResponse
    {
        $request->validate([
            'tab_numbers' => 'required|string',
        ]);

        $trainingRequest = TrainingRequestModel::with('course')->findOrFail($requestId);
        
        $tabNumbers = array_filter(array_map('trim', explode("\n", $request->tab_numbers)));
        $tabNumbers = array_filter($tabNumbers, fn($v) => $v !== '');
        
        if (empty($tabNumbers)) {
            return redirect()
                ->back()
                ->with('error', 'Введите хотя бы один табельный номер.');
        }

        $usersSap = AllUserSap::whereIn('tab_number', $tabNumbers)->get()->keyBy('tab_number');
        
        $addedCount = 0;
        $notFound = [];
        $errors = [];

        foreach ($tabNumbers as $tabNumber) {
            $userSap = $usersSap->get($tabNumber);
            
            if (!$userSap) {
                // Пункт 8: Добавляем как уволенного
                RequestEmployee::create([
                    'request_id' => $requestId,
                    'status' => 'dismissed',
                    'warning_type' => 'dismissed',
                    'warning_message' => 'Сотрудник не найден в SAP. Возможно уволен.',
                    'last_name' => 'Не найден в SAP',
                    'first_name' => 'Таб. №' . $tabNumber,
                ]);
                $notFound[] = $tabNumber;
                $addedCount++;
                continue;
            }

            // Пункт 7: Проверка на дубликат
            $existsInCurrent = RequestEmployee::where('request_id', $requestId)
                ->where('user_sap_id', $userSap->id)
                ->exists();

            if ($existsInCurrent) {
                $errors[] = "Сотрудник {$userSap->full_name} (таб. №{$tabNumber}) уже добавлен.";
                continue;
            }

            $duplicateCheck = $this->checkDuplicate($userSap, $trainingRequest);
            $data = [
                'request_id' => $requestId,
                'user_sap_id' => $userSap->id,
            ];

            if ($duplicateCheck['duplicate']) {
                $data['status'] = 'blocked';
                $data['warning_type'] = 'duplicate';
                $data['warning_message'] = $duplicateCheck['message'];
            } else {
                $data['status'] = 'active';
            }

            RequestEmployee::create($data);
            $addedCount++;
        }

        $message = "Добавлено: {$addedCount}.";
        
        if (!empty($notFound)) {
            $message .= " Не найдены: " . implode(', ', array_slice($notFound, 0, 5));
        }
        
        if (!empty($errors)) {
            $message .= " Ошибки: " . implode('; ', array_slice($errors, 0, 3));
        }

        return redirect()
            ->route('request-employees.index', $requestId)
            ->with($addedCount > 0 ? 'success' : 'error', $message);
    }

    /**
     * Проверка дубликата (пункт 7)
     */

private function checkDuplicate(AllUserSap $userSap, TrainingRequestModel $currentRequest): array
{
    $courseId = $currentRequest->course_id;
    
    // Ищем ТОЛЬКО ПРОШЛЫЕ заявки (не текущую)
    $pastRequests = TrainingRequestModel::where('course_id', $courseId)
        ->where('id', '!=', $currentRequest->id)
        ->pluck('id');
        
    if ($pastRequests->isEmpty()) {
        return ['duplicate' => false];
    }

    // Ищем сотрудника в прошлых заявках
    $pastEmployee = RequestEmployee::whereIn('request_id', $pastRequests)
        ->where('user_sap_id', $userSap->id)
        ->with('userSap')
        ->first();

    if (!$pastEmployee) {
        return ['duplicate' => false];
    }

    // Проверяем должность
    if ($pastEmployee->userSap && $pastEmployee->userSap->position === $userSap->position) {
        return [
            'duplicate' => true,
            'message' => "{$userSap->full_name} уже проходил обучение. (Должность совпадает)."
        ];
    }

    return ['duplicate' => false];
}
    /**
     * Проверка просроченности документа (пункт 9)
     */
    private function checkExpired(RequestEmployee $employee): array
    {
        if (!$employee->document_issue_date || !$employee->reissue_period) {
            return ['expired' => false];
        }

        $expiryDate = $employee->calculateExpiryDate();
        
        if ($expiryDate && $expiryDate->lt(now())) {
            return [
                'expired' => true,
                'message' => "Документ просрочен {$expiryDate->diffForHumans()}"
            ];
        }

        return ['expired' => false];
    }

    /**
     * Применить все проверки к сотруднику
     */
private function applyChecks(RequestEmployee $employee, TrainingRequestModel $trainingRequest): void
{
    $updates = [];

    // Пункт 8: Проверка на увольнение
    if ($employee->user_sap_id && !$employee->userSap) {
        $updates['status'] = 'dismissed';
        $updates['warning_type'] = 'dismissed';
        $updates['warning_message'] = 'Сотрудник уволен (не найден в SAP)';
    }

    // Пункт 8: Проверка на отсутствие в период обучения
    if ($employee->userSap && $trainingRequest->start_date && $trainingRequest->end_date) {
        $hasAbsence = RequestEmployee::where('user_sap_id', $employee->user_sap_id)
            ->where('request_id', '!=', $trainingRequest->id)
            ->where('absence_start_date', '<=', $trainingRequest->end_date)
            ->where('absence_end_date', '>=', $trainingRequest->start_date)
            ->exists();

        if ($hasAbsence) {
            $updates['status'] = 'warning';
            $updates['warning_type'] = 'absence';
            $updates['warning_message'] = 'Сотрудник отсутствует в период обучения';
        }
    }

    // Пункт 9: Проверка на просроченность
    if ($employee->document_issue_date && $employee->reissue_period) {
        $expiredCheck = $this->checkExpired($employee);
        if ($expiredCheck['expired']) {
            $updates['status'] = 'expired';
            $updates['warning_type'] = 'expired';
            $updates['warning_message'] = 'Документ об обучении просрочен';
        }
    }

    // НЕ перезаписываем статус, если он уже blocked (дубликат)
    if (!empty($updates) && $employee->status != 'blocked') {
        $employee->update($updates);
    }
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

        // Применяем проверки после обновления
        $trainingRequest = TrainingRequestModel::with('course')->findOrFail($requestId);
        $this->applyChecks($employee, $trainingRequest);

        return redirect()
            ->route('request-employees.index', $requestId)
            ->with('success', 'Данные сотрудника обновлены.');
    }
}