<?php
// app/Http/Controllers/Api/CourseController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoursesUrp;
use App\Models\MatrixCourse;
use App\Models\MatrixDpo;
use App\Models\MatrixOt;
use App\Models\MatrixPo;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CourseController extends Controller
{
    private const array MATRIX_VALUES = [
        'matrix_dpo' => 1,
        'matrix_po' => 2,
        'matrix_courses' => 3,
        'matrix_ot' => 4,
        'courses_urp' => 5,
    ];

    public function __construct(
        private readonly CourseService $courseService,
    ) {}

    public function getByProvider(Request $request): JsonResponse
    {
        $providerId = $request->integer('provider_id');
        $courses = $this->courseService->getCoursesByProvider($providerId);

        return response()->json([
            'courses' => $courses,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:500'],
            'provider_id' => ['required', 'integer'],
            'matrix' => ['nullable', 'string', 'in:matrix_courses,matrix_dpo,matrix_ot,matrix_po'],
        ]);

        $name = trim($validated['name']);
        $providerId = (int)$validated['provider_id'];

        // Проверяем, является ли провайдер РН-Юганскнефтегаз (91, 92)
        $isRnYugansk = in_array($providerId, [91, 92], true);

        // Определяем matrix_num
        $matrixNum = null;
        $matrixName = null;

        // Если РН-Юганскнефтегаз и выбрана матрица
        if ($isRnYugansk && isset($validated['matrix'])) {
            $matrix = $validated['matrix'];
            $matrixName = $matrix;
            $matrixNum = self::MATRIX_VALUES[$matrix] ?? null;
            
            $course = match($matrix) {
                'matrix_courses' => MatrixCourse::create(['program_name' => $name]),
                'matrix_dpo' => MatrixDpo::create(['program_name' => $name]),
                'matrix_ot' => MatrixOt::create(['program_name' => $name]),
                'matrix_po' => MatrixPo::create(['profession_name' => $name]),
                default => null,
            };

            if (!$course) {
                return response()->json(['error' => 'Не удалось сохранить курс'], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Курс добавлен в ' . $matrixName,
                'course' => $name,
                'matrix_num' => $matrixNum,
                'matrix_name' => $matrixName,
            ]);
        }

        // Иначе сохраняем в courses_urp
        $course = CoursesUrp::create([
            'name' => $name,
        ]);

        if (!$course) {
            return response()->json(['error' => 'Не удалось сохранить курс'], 422);
        }

        // Определяем matrix_num для courses_urp
        $matrixNum = self::MATRIX_VALUES['courses_urp'];
        $matrixName = 'courses_urp';

        return response()->json([
            'success' => true,
            'message' => 'Курс добавлен в Справочник сторонних УЦ',
            'course' => $name,
            'matrix_num' => $matrixNum,
            'matrix_name' => $matrixName,
        ]);
    }
}