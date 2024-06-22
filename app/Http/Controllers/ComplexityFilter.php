<?php

namespace App\Http\Controllers;

use App\Models\Complexity;
use Illuminate\Validation\Rule;

class ComplexityFilter {

    // todo create filter request
    public static function extract(): Complexity {
        request()->validate([
            'filters.complexity' => ['nullable', Rule::enum(Complexity::class)],
        ]);

        return Complexity::tryFrom(request('filters.complexity')) ?? Complexity::EASY;
    }
}