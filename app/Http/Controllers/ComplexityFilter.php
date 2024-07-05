<?php

namespace App\Http\Controllers;

use App\Complexity;
use Illuminate\Validation\Rule;

class ComplexityFilter {

    // todo create filter request and use trait
    public static function extract(): Complexity {
        request()->validate([
            'filters.complexity' => ['nullable', Rule::enum(Complexity::class)],
        ]);

        return Complexity::tryFrom(request('filters.complexity')) ?? Complexity::EASY;
    }
}
