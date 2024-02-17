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

        $result = null;

        switch ($this->learnableFilter) {
            case LearnableFilter::ALL:
                $result = auth()->user()->{$name}();
                break;
            case LearnableFilter::ONLY_COMPLETED:
                $result = auth()->user()->{$name}()->wherePivot('is_completed', 1);
                break;
            case LearnableFilter::ONLY_STUDIED:
                $result = auth()->user()->{$name}()->wherePivot('is_completed', 0);
                break;
        }

        return $result;
    }
}