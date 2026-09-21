<?php

namespace App\Http\Controllers;

use App\Models\Request as TrainingRequestModel;
use App\Models\RequestEmployee;
use App\Models\AllUserSap;
use App\Models\CourseException;
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
            $data['tab_number'] = $request->tab_number;
            $data['status'] = 'dismissed';
            $data['warning_type'] = 'dismissed';
            $data['warning_message'] = 'Сотрудник не найден в SAP. Возможно уволен.';
        } else {
            $data['user_sap_id'] = $userSap->id;
            
	    // Сохраняем ВСЕ данные из SAP
                $this->copySapData($data, $userSap);
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
                    'tab_number' => $tabNumber,
                    'status' => 'dismissed',
                    'warning_type' => 'dismissed',
                    'warning_message' => 'Сотрудник не найден в SAP. Возможно уволен.',
//                    'last_name' => 'Не найден в SAP',
//                    'first_name' => 'Таб. №' . $tabNumber,
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
                'tab_number' => $tabNumber,
            ];
	    // Сохраняем ВСЕ данные из SAP
            $this->copySapData($data, $userSap);
            
            $duplicateCheck = $this->checkDuplicate($userSap, $trainingRequest);

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
     * Копирование данных из SAP в request_employees
     */
    private function copySapData(array &$data, AllUserSap $userSap): void
    {
        $data['tab_number'] = $userSap->tab_number;
        $data['last_name'] = $userSap->last_name;
        $data['first_name'] = $userSap->first_name;
        $data['middle_name'] = $userSap->middle_name;
        $data['birth_date'] = $userSap->birth_date;
        $data['gender'] = $userSap->gender;
        $data['gender_key'] = $userSap->gender_key;
        $data['pfr_certificate'] = $userSap->pfr_certificate;
        $data['position'] = $userSap->position;
        $data['rank'] = $userSap->rank;
        $data['level_4_name'] = $userSap->level_4_name;
        $data['level_3_name'] = $userSap->level_3_name;
        $data['duv_b'] = $userSap->duv_b;
        $data['mvz'] = $userSap->mvz;
        $data['employee_category'] = $userSap->employee_category;
    }



    /**
     * Проверка дубликата (пункт 7)
     */

private function checkDuplicate(AllUserSap $userSap, TrainingRequestModel $currentRequest): array
{
    // Получаем название курса текущей заявки
    $currentCourse = $currentRequest->course;
    
    // Проверяем, если курс в исключениях - пропускаем проверку
    if ($currentCourse) {
        $isException = CourseException::where('course_name', $currentCourse->course)->exists();
        if ($isException) {
            return ['duplicate' => false];
        }
    }
    
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

    // Получаем название курса для проверки исключений
    $currentCourse = $trainingRequest->course;
    $isException = false;
    if ($currentCourse) {
        $isException = CourseException::where('course_name', $currentCourse->course)->exists();
    }

    // СБРАСЫВАЕМ статусы, которые больше не актуальны
    // Если курс в исключениях или курс изменился - сбрасываем duplicate
    if ($employee->status == 'blocked' && $employee->warning_type == 'duplicate') {
        if ($isException) {
            $updates['status'] = 'active';
            $updates['warning_type'] = null;
            $updates['warning_message'] = null;
        } else {
            // Пересчитываем дубликат заново
            $duplicateCheck = $this->checkDuplicate($employee->userSap, $trainingRequest);
            if (!$duplicateCheck['duplicate']) {
                $updates['status'] = 'active';
                $updates['warning_type'] = null;
                $updates['warning_message'] = null;
            }
        }
    }

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

    // Пункт 7: Проверка на дубликат - если курс не в исключениях
    if ($employee->userSap && !$isException) {
        // Проверяем, является ли текущая заявка первой для этого сотрудника
        $firstEmployee = RequestEmployee::where('user_sap_id', $employee->user_sap_id)
            ->whereHas('request', function($q) use ($trainingRequest) {
                $q->where('course_id', $trainingRequest->course_id);
            })
            ->orderBy('created_at')
            ->first();
            
        // Если этот сотрудник добавлен в текущей заявке первым - не блокируем
        if ($firstEmployee && $firstEmployee->request_id == $trainingRequest->id) {
            // Это первая заявка - не блокируем
            if ($employee->status == 'blocked' && $employee->warning_type == 'duplicate') {
                $updates['status'] = 'active';
                $updates['warning_type'] = null;
                $updates['warning_message'] = null;
            }
        } else {
            // Проверяем дубликат
            $duplicateCheck = $this->checkDuplicate($employee->userSap, $trainingRequest);
            if ($duplicateCheck['duplicate']) {
                $updates['status'] = 'blocked';
                $updates['warning_type'] = 'duplicate';
                $updates['warning_message'] = $duplicateCheck['message'];
            } else {
                // Если раньше был блокирован как дубликат - сбрасываем
                if ($employee->status == 'blocked' && $employee->warning_type == 'duplicate') {
                    $updates['status'] = 'active';
                    $updates['warning_type'] = null;
                    $updates['warning_message'] = null;
                }
            }
        }
    }

    if (!empty($updates)) {
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
            'absence_reason' => 'nullable|string|max:1000', // Добавить
            'absence_type' => 'nullable|string|max:50',
            'distance_learning_date' => 'nullable|date',
            'fulltime_learning_date' => 'nullable|date',
            'note' => 'nullable|string|max:1000',
            'document_issue_date' => 'nullable|date',
            'reissue_period' => 'nullable|string|max:50',
        ]);

        // Проверяем, заполнена ли причина отсутствия
        $absenceReasonFilled = !empty($validated['absence_reason']);
        $employee->update($validated);

 // Если причина отсутствия заполнена - отправляем уведомление куратору
    if ($absenceReasonFilled) {
        $this->notifyCurator($employee, $requestId);
    }

        // Применяем проверки после обновления
        $trainingRequest = TrainingRequestModel::with('course')->findOrFail($requestId);
        $this->applyChecks($employee, $trainingRequest);

        return redirect()
            ->route('request-employees.index', $requestId)
            ->with('success', 'Данные сотрудника обновлены.');
    }

/**
 * Отправка уведомления куратору
 */
/**
 * Отправка уведомления куратору
 */
private function notifyCurator(RequestEmployee $employee, int $requestId): void
{
    $trainingRequest = TrainingRequestModel::with('curator')->find($requestId);

//проверка поля email при отправке
//    dd([        'has_request' => (bool)$trainingRequest, 'has_curator' => $trainingRequest ? (bool)$trainingRequest->curator : false,        'curator_email' => $trainingRequest?->curator?->email ?? 'EMAIL ОТСУТСТВУЕТ'    ]);
    
    if (!$trainingRequest || !$trainingRequest->curator || !$trainingRequest->curator->email) {
        return;
    }
    
    $curator = $trainingRequest->curator;
    
    // Безопасно получаем имя сотрудника
    $employeeName = $employee->full_name ?: 'Сотрудник';
    $tabNumber = $employee->tab_number ?: '—';
    $absenceReason = $employee->absence_reason ?: '—';
    
    // Форматируем даты ЗАРАНЕЕ, а не внутри строки
    $startDate = $employee->absence_start_date instanceof \Carbon\Carbon 
        ? $employee->absence_start_date->format('d.m.Y') 
        : ($employee->absence_start_date ?: '—');
        
    $endDate = $employee->absence_end_date instanceof \Carbon\Carbon 
        ? $employee->absence_end_date->format('d.m.Y') 
        : ($employee->absence_end_date ?: '—');
    
    $subject = "Уведомление об отсутствии сотрудника в заявке #{$requestId}";
    
    $message = "
        <h2>Уведомление об отсутствии сотрудника</h2>
        <p><strong>Заявка:</strong> #{$requestId}</p>
        <p><strong>Сотрудник:</strong> {$employeeName}</p>
        <p><strong>Табельный номер:</strong> {$tabNumber}</p>
        <p><strong>Дата начала отсутствия:</strong> {$startDate}</p>
        <p><strong>Дата окончания отсутствия:</strong> {$endDate}</p>
        <p><strong>Причина отсутствия:</strong> {$absenceReason}</p>
    ";
    
    // Отправляем email куратору
    \Illuminate\Support\Facades\Mail::html($message, function ($mail) use ($curator, $subject) {
        $mail->to($curator->email)
             ->subject($subject);
    });
}

}