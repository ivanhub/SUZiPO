<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UcExtProtocol;
use App\Models\AppProtocol;
use Illuminate\Support\Facades\DB;

class ProtocolController extends Controller
{
    public function index(Request $request)
    {
        // Инициализируем запрос с жадной загрузкой программ обучения
        $query = AppProtocol::with(['demand.course', 'editor']);

        // 1. Фильтр по ID протокола (первичный ключ)
        if ($request->filled('searchId')) {
            $query->where('prot_id', $request->searchId);
        }

        // 2. Фильтр по состоянию (статусу) протокола
        if ($request->filled('searchStatus')) {
            $query->where('prot_status', 'LIKE', '%' . $request->searchStatus . '%');
        }

        // 3. Фильтр по номеру протокола
        if ($request->filled('searchProtNum')) {
            $query->where('prot_num', 'LIKE', '%' . $request->searchProtNum . '%');
        }

        // 4. Фильтр по Редактору (связанная таблица пользователей/редакторов)
        if ($request->filled('searchEditor')) {
            $query->whereHas('editor', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->searchEditor . '%');
            });
        }

        // 5. Фильтр по Наименованию курса (сквозной через app_demands -> bk_course)
        if ($request->filled('searchCourse')) {
            $query->whereHas('demand.course', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->searchCourse . '%');
            });
        }

        // 6. Фильтр по Номеру заявки (таблица app_demands)
        if ($request->filled('searchDemNum')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('dem_num', 'LIKE', '%' . $request->searchDemNum . '%');
            });
        }

        // 7. Фильтр по Квалификации (поле из таблицы app_demands)
        if ($request->filled('searchQual')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('qualification', 'LIKE', '%' . $request->searchQual . '%');
            });
        }

        // 8. Фильтр по ФИО Преподавателя (поле из таблицы app_demands)
        if ($request->filled('searchTeacher')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('teacher_name', 'LIKE', '%' . $request->searchTeacher . '%');
            });
        }

        // 9. Фильтр по Номеру аудитории (поле из таблицы app_demands)
        if ($request->filled('searchClassroom')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('classroom_number', 'LIKE', '%' . $request->searchClassroom . '%');
            });
        }

        // 10. Фильтр по Куратору группы (поле из таблицы app_demands)
        if ($request->filled('searchCurator')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('curator_name', 'LIKE', '%' . $request->searchCurator . '%');
            });
        }


        // Быстрая постраничная навигация без нагрузки на базу данных
        // $protocols = $query->orderBy('dateprotocol', 'desc')->simplePaginate(15);
        $protocols = AppProtocol::with(['demand.course', 'editor'])
            ->orderBy('prot_id', 'desc')
            ->simplePaginate(15);

        $protocols->appends($request->all());


        // Расчет счетчиков для верхних табов-вкладок по полю flagapproved
        $totalCount = UcExtProtocol::count();
        $counts = UcExtProtocol::select('flagapproved', DB::raw('count(*) as total'))
            ->whereNotNull('flagapproved')
            ->groupBy('flagapproved')
            ->pluck('total', 'flagapproved')
            ->toArray();

        // AJAX-ответ для Alpine.js (возвращает только строки tbody)
        if ($request->ajax()) {
            return view('protocols.partials.table-rows', compact('protocols'))->render();
        }

        return view('protocols.index', compact('protocols', 'totalCount', 'counts'));
    }

    /**
     * Сохранение правок из Pop-up окна
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date_start'   => 'nullable|date',
            'date_end'     => 'nullable|date',
            'prot_date'    => 'nullable|date',
            'group_num'    => 'required|string|max:50',
            'order_num'    => 'nullable|string|max:100',
            'order_date'   => 'nullable|date',
            'flagapproved' => 'required|integer',
        ]);


        $protocol = UcExtProtocol::findOrFail($id);

        $protocol->date_start   = $request->input('date_start');
        $protocol->date_end     = $request->input('date_end');
        $protocol->prot_date    = $request->input('prot_date');
        $protocol->group_num    = $request->input('group_num');
        $protocol->order_num    = $request->input('order_num');
        $protocol->order_date   = $request->input('order_date');
        $protocol->prot_status  = $request->input('flagapproved');

        $protocol->save();

        return redirect()->route('protocols.index')->with('success', 'Протокол успешно обновлен!');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
