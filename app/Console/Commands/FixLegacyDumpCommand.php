<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixLegacyDumpCommand extends Command
{
    protected $signature = 'db:fix-legacy';
    protected $description = 'Приведение схемы старого дампа БД к актуальным требованиям Laravel';

    public function handle(): int
    {
        $this->info('Исправляем ограничения NOT NULL в app_protocols...');

        $queries = [
            'ALTER TABLE app_protocols ALTER COLUMN dem_id DROP NOT NULL;',
            'ALTER TABLE app_protocols ALTER COLUMN order_date DROP NOT NULL;',
            'ALTER TABLE app_protocols ALTER COLUMN row_version DROP NOT NULL;',
            'ALTER TABLE app_protocols ALTER COLUMN prot_status DROP NOT NULL;',
        ];

        foreach ($queries as $sql) {
            DB::statement($sql);
            $this->line("✅ Выполнено: {$sql}");
        }

        $this->info('🎉 Схема БД успешно адаптирована после импорта дампа!');
        return self::SUCCESS;
    }
}
