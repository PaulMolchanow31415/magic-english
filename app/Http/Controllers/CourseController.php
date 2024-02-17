<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Course;
use App\Models\Complexity;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use Illuminate\Validation\Rule;
use App\Service\DiscussionService;

class CourseController extends Controller {
    use PhotoUploadable;
    use FilteredLearnable;

    public function index(): Response|ResponseFactory {
        return inertia('Admin/Course', [
            'courses' => Course::search(request('search'))->paginate(10),
            'filters' => request()->only(['search']),
        ]);
    }

    public function store(Request $request, DiscussionService $service) {
        $request->validate([
            'id'                  => 'int|nullable',
            'name'                => 'string|required|max:1024|unique:courses',
            'description'         => 'string|required|max:2048',
            'photo_external_path' => 'active_url|nullable',
            'complexity'          => [Rule::enum(Complexity::class)],
        ]);

        $course = Course::firstOrNew(['id' => $request['id']]);
        $oldPath = $course->poster_url;
        $updatedPath = $this->upload($oldPath);

        $service->createOrUpdate(
            Course::updateOrCreate(['id' => $request['id']], [
                'name'        => $request['name'],
                'description' => $request['description'],
                'complexity'  => $request['complexity'],
                'poster_url'  => $updatedPath ?? $request['photo_external_path'] ?? $oldPath,
            ]),
        );
    }

    public function courses(): Response|ResponseFactory {
        $complexity = ComplexityFilter::extract();

        return inertia('Skills/Course/Index', [
            'courses' => Course::whereComplexity($complexity)->paginate(4),

            'learnableCoursesCount' => auth()->user()->courses()->count(),

            'filters' => ['complexity' => $complexity],
        ]);
    }

    public function show(string $name): Response|ResponseFactory {
        return inertia('Skills/Course/Show', [
            'course' => Course::whereName($name)->with('grammarRules')->firstOrFail(),
        ]);
    }

    public function deletePoster(Request $request): void {
        $this->handleDeletePoster();
        Course::wherePosterUrl($request['filename'])->update(['poster_url' => null]);
    }

    public function destroy(int $id, DiscussionService $service): void {
        $course = Course::findOrFail($id);
        $service->deleteFrom($course);
        $this->deleteFileIfExist($course->poster_url);
        $course->delete();
    }
}
