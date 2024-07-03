<?php

namespace App\Http\Controllers;

use App\Complexity;
use Inertia\Response;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use Illuminate\Validation\Rule;
use App\Service\DiscussionService;

class LessonController extends Controller {
    use FilteredLearnable;

    public function index(): Response|ResponseFactory {
        return inertia('Admin/Lesson', [
            'lessons' => Lesson::search(request('search'))
                ->orderBy('number', 'desc')
                ->paginate(5),

            'filters'          => request()->only(['search']),
            'prevLessonNumber' => Lesson::latest('created_at')->first()?->number ?? 0,
        ]);
    }

    public function store(Request $request, DiscussionService $service) {
        $request->validate([
            'id'         => 'nullable|int',
            'number'     => 'required|int|unique:lessons',
            'content'    => 'required|string|min:20',
            'complexity' => ['required', Rule::enum(Complexity::class)],
        ]);

        $service->createOrUpdate(
            Lesson::updateOrCreate(['id' => $request['id']], [
                'number'     => $request['number'],
                'content'    => $request['content'],
                'complexity' => $request['complexity'],
            ]),
        );
    }

    public function destroy(int $id, DiscussionService $service) {
        $lesson = Lesson::findOrFail($id);
        $service->deleteFrom($lesson);
        $lesson->delete();
    }

    public function lessons() {
        $complexity = ComplexityFilter::extract();

        return inertia('Skills/Lessons/Index', [
            'lessons' => Lesson::whereComplexity($complexity)->select('number')->get(),

            'learnableLessonsCount' => auth()->user()->lessons()->count(),

            'filters' => ['complexity' => $complexity],
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
                ->wherePivot('is_completed', false)->find($lesson)?->exists(),
        ]);
    }
}
