<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait FilteredLearnable {

    protected LearnableFilter $learnableFilter;

    protected function getFilteredUserRelation(string $name): MorphToMany {
        request()->validate([
            'filters.learnable' => ['nullable', Rule::enum(LearnableFilter::class)],
        ]);

        $this->learnableFilter =
            LearnableFilter::tryFrom(request('filters.learnable')) ?? LearnableFilter::ALL;

        return match ($this->learnableFilter) {
            LearnableFilter::ALL => auth()->user()->{$name}(),
            LearnableFilter::ONLY_COMPLETED =>
            auth()->user()->{$name}()->wherePivot('is_completed', 1),
            LearnableFilter::ONLY_STUDIED =>
            auth()->user()->{$name}()->wherePivot('is_completed', 0),
        };
    }
}