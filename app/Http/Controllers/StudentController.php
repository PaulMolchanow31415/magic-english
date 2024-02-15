<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Vocabulary;
use App\Models\Dictionary;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class StudentController extends Controller {
    use Filterable;

    public function showVocabularyDashboard() {
        $this->handleFilter('vocabularies');

        return inertia('Skills/Dashboard/MyGlossary', [
            'vocabularies' => $this->list
                ->withPivot(['is_completed'])
                ->orderBy('en')->get(),

            ...$this->filterOptions(),
        ]);
    }

    public function showCoursesDashboard() {
        return inertia('Skills/Dashboard/AddedCourses', [
            'courses' => auth()->user()->courses,
        ]);
    }

    public function showLessonsDashboard() {
        $this->handleFilter('lessons');

        return inertia('Skills/Dashboard/AddedLessons', [
            'lessons' => $this->list
                ->withPivot(['is_completed'])
                ->orderBy('number')->get(),

            ...$this->filterOptions(),
        ]);
    }

    public function latestAddedLesson(): RedirectResponse {
        $lesson = auth()->user()->lessons()->latest('number')->first()
                  ?? Lesson::latest('number')->first();

        return to_route('skills.lesson.show', ['number' => $lesson->number]);
    }

    public function addVocabulary(string $word): void {
        auth()->user()->vocabularies()->syncWithoutDetaching(
            Vocabulary::whereEn($word)->first(),
        );
    }

    public function addDictionary(int $id): RedirectResponse {
        auth()->user()->vocabularies()->syncWithoutDetaching(
            Dictionary::findOrFail($id)->vocabularies,
        );

        return to_route('student.vocabularies.challenge', ['dictionaryId' => $id]);
    }

    public function addCourse(int $id): RedirectResponse {
        auth()->user()->courses()->syncWithoutDetaching(Course::findOrFail($id));

        return to_route('skills.courses');
    }

    public function addLesson(int $id): void {
        auth()->user()->lessons()->syncWithoutDetaching(Lesson::findOrFail($id));
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

    public function completeLesson(int $id) {
        auth()->user()->lessons()->syncWithPivotValues(Lesson::findOrFail($id), [
            'is_completed' => true,
        ], false);
    }

    public function removeVocabulary(int $id) {
        auth()->user()->vocabularies()->detach($id);
    }

    public function removeLesson(int $number) {
        auth()->user()->lessons()->detach(Lesson::whereNumber($number)->firstOrFail());
    }

    public function flushVocabularies() {
        auth()->user()->vocabularies()->detach();

        return to_route('student.vocabularies.dashboard');
    }

    public function flushLessons() {
        auth()->user()->lessons()->detach();

        return to_route('student.lessons.dashboard');
    }

    public function showVocabularyChallenge(Request $request) {
        $request->validate(['dictionaryId' => 'int|nullable']);

        return inertia('Skills/Challenges/Vocabulary', [
            'vocabularies' => $request['dictionaryId']
                ? Dictionary::findOrFail($request['dictionaryId'])->vocabularies
                : auth()->user()->vocabularies,
        ]);
    }
}
