<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GeneratePostgresModels extends Command
{
    protected $signature = 'db:generate-models';
    protected $description = 'Автогенерация моделей из существующей БД PostgreSQL';

    public function handle()
    {
        $tables = DB::select("
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_schema = 'public' 
            AND table_type = 'BASE TABLE'
        ");

        if (empty($tables)) {
            $this->error('Таблицы не найдены. Проверьте подключение в .env!');
            return 1;
        }

        $this->info('Найдено таблиц: ' . count($tables) . '. Начинаю генерацию...');

        foreach ($tables as $table) {
            $tableName = $table->table_name;

            if (in_array($tableName, ['migrations', 'failed_jobs', 'password_reset_tokens', 'personal_access_tokens'])) {
                continue;
            }

            $className = Str::studly(Str::singular($tableName));
            $className = preg_replace('/[^A-Za-z0-9]/', '', $className);

            $this->generateModelFile($tableName, $className);
        }

        $this->info('🎉 Все модели успешно созданы в папке app/Models!');
        return 0;
    }

    private function generateModelFile($tableName, $className)
    {
        $directory = app_path('Models');
        File::ensureDirectoryExists($directory);
        $filePath = $directory . '/' . $className . '.php';

        $stub = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class {$className} extends Model
{
    protected \$table = '{$tableName}';
    public \$timestamps = false;
}
";

        File::put($filePath, $stub);
        $this->line("Создана модель: <info>{$className}</info> для таблицы <comment>{$tableName}</comment>");
    }
}
