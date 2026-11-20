<?php

namespace App\Http\Controllers;

use App\Services\MatrixImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MatrixController extends Controller
{
    public function index()
    {
        $counts = [
            'po'      => Schema::hasTable('matrix_po') ? DB::table('matrix_po')->count() : 0,
            'dpo'     => Schema::hasTable('matrix_dpo') ? DB::table('matrix_dpo')->count() : 0,
            'courses' => Schema::hasTable('matrix_courses') ? DB::table('matrix_courses')->count() : 0,
            'ot'      => Schema::hasTable('matrix_ot') ? DB::table('matrix_ot')->count() : 0,
        ];

        return view('matrices.index', compact('counts'));
    }
 
    public function import(Request $request, MatrixImportService $importService)
    {
        try {
            $request->validate([
                'matrix_file' => 'required|file|mimes:xlsx,xls|max:20480', // до 20 МБ
            ], [
                'matrix_file.required' => 'Выберите файл Excel для загрузки.',
                'matrix_file.mimes'    => 'Файл должен быть в формате .xlsx или .xls.',
                'matrix_file.max'      => 'Размер файла не должен превышать 20 МБ.',
            ]);

            $file = $request->file('matrix_file');
            $stats = $importService->import($file->getRealPath());

            return response()->json([
                'success' => true,
                'message' => 'Импорт матриц успешно завершен.',
                'stats'   => $stats,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first(),
            ], 422);

        } catch (\Throwable $e) {
            Log::error('Ошибка при загрузке файла матриц: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка парсинга файла: ' . $e->getMessage(),
                'debug'   => [
                    'file' => basename($e->getFile()),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }
}
