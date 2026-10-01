<?php
// app/Services/CourseService.php

namespace App\Services;

use App\Models\CoursesUrp;
use App\Models\MatrixCourse;
use App\Models\MatrixDpo;
use App\Models\MatrixOt;
use App\Models\MatrixPo;
use Illuminate\Support\Collection;

final class CourseService
{
    /** ID провайдеров Учебный центр ООО «РН-Юганскнефтегаз» */
    private const ARRAY RN_YUGANSK_PROVIDER_IDS = [91, 92];

    /**
     * Получить курсы в зависимости от провайдера
     */
    public function getCoursesByProvider(?int $providerId): Collection
    {
        if ($providerId === null) {
            return collect();
        }

        // Если выбран Учебный центр ООО «РН-Юганскнефтегаз» — грузим из matrix-таблиц
        if (in_array($providerId, self::RN_YUGANSK_PROVIDER_IDS, true)) {
            return $this->getMatrixCourses();
        }

        // Иначе — из справочника courses_urp
        return $this->getUrpCourses();
    }

    /**
     * Получить курсы из matrix-таблиц
     */
    private function getMatrixCourses(): Collection
    {
        $courses = collect();


 // matrix_courses (matrix_num = 3)
        $courses = $courses->merge(
            MatrixCourse::query()
                ->whereNotNull('program_name')
                ->pluck('program_name')
                ->map(fn($name) => ['name' => $name, 'matrix_num' => 3])
        );

        // matrix_dpo (matrix_num = 1)
        $courses = $courses->merge(
            MatrixDpo::query()
                ->whereNotNull('program_name')
                ->pluck('program_name')
                ->map(fn($name) => ['name' => $name, 'matrix_num' => 1])
        );

        // matrix_ot (matrix_num = 4)
        $courses = $courses->merge(
            MatrixOt::query()
                ->whereNotNull('program_name')
                ->pluck('program_name')
                ->map(fn($name) => ['name' => $name, 'matrix_num' => 4])
        );

        // matrix_po (matrix_num = 2)
        $courses = $courses->merge(
            MatrixPo::query()
                ->whereNotNull('profession_name')
                ->pluck('profession_name')
                ->map(fn($name) => ['name' => $name, 'matrix_num' => 2])
        );


        // Убираем дубликаты и null, сортируем
        return $courses
            ->filter()
//            ->unique()
//            ->sort()
	    ->unique('name')
            ->sortBy('name')
            ->values();
    }

    /**
     * Получить курсы из справочника courses_urp
     */
    private function getUrpCourses(): Collection
    {
        return CoursesUrp::query()
            ->whereNotNull('name')
            ->pluck('name')
//            ->filter()
	    ->map(fn($name) => ['name' => $name, 'matrix_num' => 5])
            ->unique('name')
            ->sortBy('name')
//            ->unique()
//            ->sort()
            ->values();
    }
}