<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Vocabulary;
use App\Models\Dictionary;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;

class StudentController extends Controller {

    public function showVocabularyDashboard(Request $request) {
        $request->validate([
            'filter' => ['nullable', Rule::enum(VocabularyFilter::class)],
        ]);

        $filter = VocabularyFilter::tryFrom($request['filter']) ?? VocabularyFilter::ALL;
        $result = null;

        switch ($filter) {
            case VocabularyFilter::ALL:
                $result = auth()->user()->vocabularies();
                break;
            case VocabularyFilter::ONLY_COMPLETED:
                $result = auth()->user()->completedVocabularies();
                break;
            case VocabularyFilter::ONLY_STUDIED:
                $result = auth()->user()->studiedVocabularies();
                break;
        }

        return inertia('Skills/Dashboard/MyGlossary', [
            'vocabularies' => $result
                ->withPivot(['is_completed'])
                ->orderBy('en')->get(),

            'selectedFilter' => $filter->value,
            'filters'        => VocabularyFilter::cases(),
        ]);
    }

    public function addVocabulary(string $word): void {
        auth()->user()->vocabularies()->syncWithoutDetaching(
            Vocabulary::whereEn($word)->first(),
        );
    }

    public function removeVocabulary(int $id) {
        auth()->user()->vocabularies()->detach($id);
    }

    public function completeVocabularies(Request $request) {
        $request->validate([
            'vocabulary_ids'   => 'array|required',
            'vocabulary_ids.*' => 'int|required',
        ]);

        auth()->user()->vocabularies()
            ->syncWithPivotValues($request['vocabulary_ids'], [
                'is_completed' => true,
            ], false);
    }

    public function flushVocabularies() {
        auth()->user()->vocabularies()->detach();

        return to_route('student.vocabularies.dashboard');
    }

    public function addDictionary(int $id): RedirectResponse {
        auth()->user()->vocabularies()->syncWithoutDetaching(
            Dictionary::findOrFail($id)->vocabularies,
        );

        return to_route('student.vocabularies.challenge', ['dictionaryId' => $id]);
    }

    public function showVocabularyChallenge(Request $request) {
        $request->validate(['dictionaryId' => 'int|nullable']);

        return inertia('Skills/Challenges/Vocabulary', [
            'vocabularies' => $request['dictionaryId']
                ? Dictionary::findOrFail($request['dictionaryId'])->vocabularies
                : auth()->user()->vocabularies,
        ]);
    }

    public function addCourse(int $id): void {
        auth()->user()->courses()->syncWithoutDetaching(Course::findOrFail($id));
    }
}
