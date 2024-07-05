<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Vocabulary;
use App\Models\Dictionary;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class StudentController extends Controller {
    use FilteredLearnable;

    public function showVocabularyDashboard() {
        return inertia('Skills/Dashboard/MyGlossary', [
            'vocabularies' => $this->getFilteredUserRelation('vocabularies')
                ->withPivot(['is_completed'])
                ->orderBy('en')->get(),

            'filters' => ['learnable' => $this->learnableFilter],
        ]);
    }

    public function showCoursesDashboard() {
        $complexity = ComplexityFilter::extract();

        return inertia('Skills/Dashboard/AddedCourses', [
            'courses' => $this->getFilteredUserRelation('courses')
                ->where('complexity', $complexity)->get(),

            'filters' => [
                'learnable'  => $this->learnableFilter,
                'complexity' => $complexity,
            ],
        ]);
    }

    public function showLessonsDashboard() {
        $complexity = ComplexityFilter::extract();

        return inertia('Skills/Dashboard/AddedLessons', [
            'lessons' => $this->getFilteredUserRelation('lessons')
                ->where('complexity', $complexity)
                ->withPivot(['is_completed'])
                ->orderBy('number')->get(),

            'filters' => [
                'complexity' => $complexity,
                'learnable'  => $this->learnableFilter,
            ],
        ]);
    }

    public function latestAddedLesson(): RedirectResponse {
        $lesson = user()->lessons()->latest('number')->first()
                  ?? Lesson::latest('number')->first();

        return to_route('skills.lesson.show', ['number' => $lesson->number]);
    }

    public function addVocabulary(string $word): void {
        user()->vocabularies()->syncWithoutDetaching(
            Vocabulary::whereEn($word)->first(),
        );
    }

    public function addDictionary(Dictionary $dictionary): RedirectResponse {
        user()->vocabularies()->syncWithoutDetaching($dictionary->vocabularies);

        return to_route('student.vocabularies.challenge', ['dictionaryId' => $dictionary->id]);
    }

    public function addCourse(Course $course): void {
        user()->courses()->syncWithoutDetaching($course);
    }

    public function addLesson(Lesson $lesson): void {
        user()->lessons()->syncWithoutDetaching($lesson);
    }

    public function completeVocabularies(Request $request) {
        $request->validate([
            'vocabulary_ids'   => 'array|required',
            'vocabulary_ids.*' => 'int|required',
        ]);

        user()->vocabularies()->syncWithPivotValues($request['vocabulary_ids'], [
            'is_completed' => true,
        ], false);

        return to_route('student.vocabularies.dashboard');
    }

    public function completeLesson(Lesson $lesson) {
        user()->lessons()->syncWithPivotValues($lesson, ['is_completed' => true], false);
    }

    public function completeCourse(Course $course) {
        user()->courses()->syncWithPivotValues($course, ['is_completed' => true], false);

        return to_route('student.courses.dashboard');
    }

    public function removeVocabulary(Vocabulary $vocabulary) {
        user()->vocabularies()->detach($vocabulary);
    }

    public function removeLesson(int $number) {
        user()->lessons()->detach(Lesson::whereNumber($number)->firstOrFail());
    }

    public function flushVocabularies() {
        user()->vocabularies()->detach();

        return to_route('student.vocabularies.dashboard');
    }

    public function flushLessons() {
        user()->lessons()->detach();

        return to_route('student.lessons.dashboard');
    }

    public function showVocabularyChallenge(Request $request) {
        $request->validate(['dictionaryId' => 'int|nullable']);

        return inertia('Skills/Challenges/Vocabulary', [
            'vocabularies' => $request['dictionaryId']
                ? Dictionary::findOrFail($request['dictionaryId'])->vocabularies
                : user()->vocabularies,
        ]);
    }
}
