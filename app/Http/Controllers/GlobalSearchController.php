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
        $request->validate(['query' => 'string|required|min:1']);
        $perPage = 4;
        $maxAmount = 20;
        $result = collect();

        // Singers
        $result->push(
            ...Arr::map(
            Singer::search($request['query'])->paginate($perPage)->items(),
            fn(Singer $singer) => (object)[
                'name' => $singer->name,
                'url'  => route('singer.show', $singer),
            ],
        ),
        );
        // Courses
        if (count($result) < $maxAmount) {
            $result->push(
                ...Arr::map(
                Course::search($request['query'])->paginate($perPage)->items(),
                fn(Course $course) => (object)[
                    'name' => $course->name,
                    'url'  => route('skills.course.show', $course),
                ],
            ),
            );
        }
        // Lessons
        if (count($result) < $maxAmount) {
            $result->push(
                ...Arr::map(
                Lesson::search($request['query'])->paginate($perPage)->items(),
                fn(Lesson $lesson) => (object)[
                    'name' => $lesson->number.' Урок',
                    'url'  => route('skills.lesson.show', $lesson->number),
                ],
            ),
            );
        }
        // Dictionaries
        if (count($result) < $maxAmount) {
            $result->push(
                ...Arr::map(
                Dictionary::search($request['query'])->paginate($perPage)->items(),
                fn(Dictionary $dictionary) => (object)[
                    'name' => $dictionary->category,
                    'url'  => route(
                        'skills.dictionary.show',
                        $dictionary->category,
                    ),
                ],
            ),
            );
        }

        return $result;
    }

}
