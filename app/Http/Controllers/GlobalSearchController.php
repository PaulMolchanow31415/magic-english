<?php

namespace App\Http\Controllers;

use App\Models\Singer;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Dictionary;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller {

    public function __invoke(Request $request) {
        $compact = fn(string $name, string $route) => (object)['name' => $name, 'url' => $route];
        $request->validate(['query' => 'string|required|min:1']);
        $perPage = 4;
        $maxAmount = 20;
        $result = collect();
        $query = $request->get('query', '');

        // Singers
        $result->push(
            ...Arr::map(
            Singer::search($query)->paginate($perPage)->items(),
            fn($singer) => $compact($singer->name, route('singer.show', $singer)),
        ),
        );
        // Courses
        if (count($result) < $maxAmount) {
            $result->push(
                ...Arr::map(
                Course::search($query)->paginate($perPage)->items(),
                fn($course) => $compact($course->name, route('skills.course.show', $course)),
            ),
            );
        }
        // Lessons
        if (count($result) < $maxAmount) {
            $result->push(
                ...Arr::map(
                Lesson::search($query)->paginate($perPage)->items(),
                fn($lesson) => $compact(
                    $lesson->number.' Урок',
                    route('skills.lesson.show', $lesson->number),
                ),
            ),
            );
        }
        // Dictionaries
        if (count($result) < $maxAmount) {
            $result->push(
                ...Arr::map(
                Dictionary::search($query)->paginate($perPage)->items(),
                fn($dictionary) => $compact(
                    $dictionary->category,
                    route('skills.dictionary.show', $dictionary->category),
                ),
            ),
            );
        }

        return $result;
    }

}
