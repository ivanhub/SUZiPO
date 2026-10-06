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
                ->get()
                ->map(fn($course) => [
                    'name' => $course->program_name,
                    'matrix_num' => 3,
                    'hours' => $course->hours,
                    'theory_hours' => $course->theory_hours,
                    'self_study_hours' => $course->self_study_hours,
                    'practical_hours' => $course->practical_hours,
                    'rank' => null,
                ])
        );

        // matrix_dpo (matrix_num = 1)
        $courses = $courses->merge(
            MatrixDpo::query()
                ->whereNotNull('program_name')
                ->get()
                ->map(fn($course) => [
                    'name' => $course->program_name,
                    'matrix_num' => 1,
                    'hours' => $course->total_hours,
                    'theory_hours' => $course->theoretical_hours,
                    'self_study_hours' => $course->self_study_hours,
                    'practical_hours' => $course->practical_hours,
                    'rank' => null,
                ])
        );

        // matrix_ot (matrix_num = 4)
        $courses = $courses->merge(
            MatrixOt::query()
                ->whereNotNull('program_name')
                ->get()
                ->map(fn($course) => [
                    'name' => $course->program_name,
                    'matrix_num' => 4,
                    'hours' => $course->total_hours,
                    'theory_hours' => $course->fulltime_theoretical_hours + $course->distance_theoretical_hours,
                    'self_study_hours' => null,
                    'practical_hours' => $course->practical_hours,
                    'rank' => null,
                ])
        );

        // matrix_po (matrix_num = 2)
        $courses = $courses->merge(
            MatrixPo::query()
                ->whereNotNull('profession_name')
                ->get()
                ->map(fn($course) => [
                    'name' => $course->profession_name,
                    'matrix_num' => 2,
                    'hours' => $course->hours,
                    'theory_hours' => $course->theory_hours,
                    'self_study_hours' => $course->self_study_hours,
                    'practical_hours' => $course->practical_hours,
                    'rank' => $course->rank,
                ])
        );

        // Убираем дубликаты и null, сортируем
        return $courses
            ->filter()
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
            ->get()
            ->map(fn($course) => [
                'name' => $course->name,
                'matrix_num' => 5,
                'hours' => null,
                'theory_hours' => null,
                'self_study_hours' => null,
                'practical_hours' => null,
                'rank' => null,
            ])
            ->unique('name')
            ->sortBy('name')
            ->values();
    }
}