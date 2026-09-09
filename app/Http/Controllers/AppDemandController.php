<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppDemand;
use Illuminate\Support\Facades\DB;

class AppDemandController extends Controller
{
    public function index(Request $request)
    {
        // Текущий статус из вкладок (по умолчанию 'all')
        $status = $request->input('status', 'all');

        // Строим быстрый запрос с жадной загрузкой связанных справочников
        $query = AppDemand::with(['course', 'vuz', 'creator']);

        // Фильтрация по статусам (вкладкам)
        if ($status !== 'all') {
            $query->where('dem_status', $status);
        }

        // Живой поиск Alpine.js
        if ($request->filled('search_id')) {
            $query->where('dem_id', $request->search_id);
        }

        if ($request->filled('search_num')) {
            $query->where('dem_num', $request->search_num);
        }

        if ($request->filled('search_course')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('course_name', 'LIKE', '%' . $request->search_course . '%'); // Укажите реальное поле названия курса из BK_Course
            });
        }

        // Быстрая постраничная навигация без перегрузки СУБД
        $demands = $query->orderBy('dem_id', 'desc')->simplePaginate(15);

        // Расчет счетчиков для вкладок статусов
        $totalCount = AppDemand::count();
        $counts = AppDemand::select('dem_status', DB::raw('count(*) as total'))
            ->groupBy('dem_status')
            ->pluck('total', 'dem_status')
            ->toArray();

        // Если пришел AJAX-запрос от Alpine.js — отдаем только обновленные строки таблицы
        if ($request->ajax()) {
            return view('demands.partials.table-rows', compact('demands'))->render();
        }

        // Для обычного визита отдаем всю страницу целиком
        return view('demands.index', compact('demands', 'status', 'totalCount', 'counts'));
    }

    public function show($id)
    {
        $demand = \App\Models\AppDemand::with(['course', 'vuz', 'edType', 'form', 'expenses', 'typeDoc', 'creator', 'editor', 'country', 'city', 'prof', 'division'])->findOrFail($id);
        return view('demands.show', compact('demand'));
    }
}
