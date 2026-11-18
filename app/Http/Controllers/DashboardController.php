<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $kpi = [
            'employees' => 0,
            'control'   => 0,
            'expiring'  => 0,
            'overdue'   => 0,
        ];

        $chartData = [
            'actual'    => 0,
            'expiring'  => 0,
            'overdue'   => 0,
            'planned'   => 0,
            'untrained' => 0,
        ];

        $recentTraining = collect();
        $recentRequests = collect();

        try {
            // 1. Общее количество сотрудников
            if (Schema::hasTable('all_users_sap')) {
                $kpi['employees'] = DB::table('all_users_sap')->count();
            } elseif (Schema::hasTable('uc_ext_students')) {
                $kpi['employees'] = DB::table('uc_ext_students')->count();
            }

            // 2. Заявки на контроле и лента последних заявок
            if (Schema::hasTable('app_demands')) {
                $kpi['control'] = DB::table('app_demands')
                    ->whereIn('status', ['in_progress', 'sent', 'accepted'])
                    ->count();

                $recentRequests = DB::table('app_demands')
                    ->orderByDesc('id')
                    ->limit(10)
                    ->get();
            }

            // 3. Последние записи по обучению (результаты протоколов)
            if (Schema::hasTable('uc_ext_studentsprotocol')) {
                $recentTraining = DB::table('uc_ext_studentsprotocol')
                    ->orderByDesc('id')
                    ->limit(10)
                    ->get();
            }

            // 4. Расчёт процентов для кольцевой диаграммы «Соответствие обучению»
            $totalTrained = Schema::hasTable('uc_ext_studentsprotocol')
                ? DB::table('uc_ext_studentsprotocol')->where('flagpassed', 1)->count()
                : 0;

            $totalBase = max($kpi['employees'], $totalTrained, 1);
            $actualPct = (int)round(($totalTrained / $totalBase) * 100);
            $actualPct = min($actualPct, 100);

            $chartData = [
                'actual'    => $actualPct,
                'expiring'  => 0,
                'overdue'   => 0,
                'planned'   => $kpi['control'] > 0 ? min(100 - $actualPct, (int)round(($kpi['control'] / $totalBase) * 100)) : 0,
                'untrained' => max(0, 100 - $actualPct),
            ];

        } catch (\Throwable $e) {
            Log::error('Ошибка формирования данных дашборда: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }

        return view('dashboard', compact('kpi', 'chartData', 'recentTraining', 'recentRequests'));
    }
}
