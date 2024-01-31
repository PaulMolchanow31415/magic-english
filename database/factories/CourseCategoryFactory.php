<?php

namespace Database\Factories;

use App\Models\CourseCategory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseCategoryFactory extends Factory {
    protected $model = CourseCategory::class;

    public function definition(): array {
        return [
            'name' => $this->faker->realTextBetween(100, 200, rand(1, 5)),
        ];
    }
}
