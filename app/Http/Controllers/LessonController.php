<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use App\Service\DiscussionService;

class LessonController extends Controller {

    public function index(): Response|ResponseFactory {
        return inertia('Admin/Lesson', [
            'lessons' => Lesson::search(request('search'))
                ->orderBy('number', 'desc')
                ->paginate(5),

            'filters'          => request()->only(['search']),
            'prevLessonNumber' => Lesson::latest('created_at')->first()->number,
        ]);
    }

    public function store(Request $request, DiscussionService $service) {
        $request->validate([
            'id'      => 'nullable|int',
            'number'  => 'required|int|unique:lessons',
            'content' => 'required|string|min:20',
        ]);

        $service->createOrUpdate(
            Lesson::updateOrCreate(['id' => $request['id']], [
                'number'  => $request['number'],
                'content' => $request['content'],
            ]),
        );
    }

    public function destroy(int $id, DiscussionService $service) {
        $lesson = Lesson::findOrFail($id);
        $service->deleteFrom($lesson);
        $lesson->delete();
    }

    public function lessons() {
        return inertia('Skills/Lessons/Index', [
            'lessons'               => Lesson::select('number')->get(),
            'learnableLessonsCount' => auth()->user()->lessons()->count(),
        ]);
    }

    public function show(int $number) {
        $defineUrl = fn($n) => Lesson::whereNumber($n)->exists()
            ? route('skills.lesson.show', ['number' => $n]) : null;
        $lesson = Lesson::whereNumber($number)->firstOrFail();

        return inertia('Skills/Lessons/Show', [
            'lesson'      => $lesson,
            'nextPageUrl' => $defineUrl($number + 1),
            'prevPageUrl' => $defineUrl($number - 1),
            'canComplete' => auth()->user()->lessons()
                ->wherePivot('is_completed', false)->find($lesson),
        ]);
    }
}
