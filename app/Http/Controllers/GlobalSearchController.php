<?php

namespace App\Http\Controllers;

use App\Models\Singer;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Dictionary;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller {

    private function toObj(string $name, string $route) {
        return (object)[
            'name' => $name,
            'url'  => $route,
        ];
    }

    public function __invoke(Request $request) {
        $request->validate(['query' => 'string|required|min:1']);
        $perPage = 4;
        $maxAmount = 20;
        $result = collect();
        $query = $request->get('query', '');

        // Singers
        $result->push(
            ...Arr::map(
            Singer::search($query)->paginate($perPage)->items(),
            fn($singer) => $this->toObj($singer->name, route('singer.show', $singer)),
        ),
        );
        // Courses
        if (count($result) < $maxAmount) {
            $result->push(
                ...Arr::map(
                Course::search($query)->paginate($perPage)->items(),
                fn($course) => $this->toObj($course->name, route('skills.course.show', $course)),
            ),
            );
        }
        // Lessons
        if (count($result) < $maxAmount) {
            $result->push(
                ...Arr::map(
                Lesson::search($query)->paginate($perPage)->items(),
                fn($lesson) => $this
                    ->toObj($lesson->number.' Урок', route('skills.lesson.show', $lesson->number)),
            ),
            );
        }
        // Dictionaries
        if (count($result) < $maxAmount) {
            $result->push(
                ...Arr::map(
                Dictionary::search($query)->paginate($perPage)->items(),
                fn($dictionary) => $this->toObj(
                    $dictionary->category,
                    route('skills.dictionary.show', $dictionary->category),
                ),
            ),
            );
        }

        return $result;
    }

}
