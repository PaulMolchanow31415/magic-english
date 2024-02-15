<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Filterable {
    private LearnableFilter $filter;
    private MorphToMany     $list;

    private function handleFilter(string $property): void {
        request()->validate([
            'filter' => ['nullable', Rule::enum(LearnableFilter::class)],
        ]);

        $this->filter = LearnableFilter::tryFrom(request('filter')) ?? LearnableFilter::ALL;

        switch ($this->filter) {
            case LearnableFilter::ALL:
                $this->list = auth()->user()->{$property}();
                break;
            case LearnableFilter::ONLY_COMPLETED:
                $this->list = auth()->user()->{$property}()->wherePivot('is_completed', 1);
                break;
            case LearnableFilter::ONLY_STUDIED:
                $this->list = auth()->user()->{$property}()->wherePivot('is_completed', 0);
                break;
        }
    }

    private function filterOptions(): array {
        return [
            'selectedFilter' => $this->filter->value,
            'filters'        => LearnableFilter::cases(),
        ];
    }
}