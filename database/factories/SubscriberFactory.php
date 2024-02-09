<?php

namespace Database\Factories;

use App\Models\Subscriber;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory {
    protected $model = Subscriber::class;

    public function definition(): array {
        return [
            'email'      => $this->faker->unique()->safeEmail(),
            'is_enabled' => $this->faker->boolean(),
        ];
    }
}
