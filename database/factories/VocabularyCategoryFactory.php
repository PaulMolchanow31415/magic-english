<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use App\Models\VocabularyCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class VocabularyCategoryFactory extends Factory {
    protected $model = VocabularyCategory::class;

    public function definition(): array {
        return [
            'name' => $this->faker->realTextBetween(100, 200, rand(1, 5)),
        ];
    }
}
