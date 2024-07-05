<?php

namespace App\Http\Controllers;

use App\Complexity;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Service\DiscussionService;
use App\Http\Requests\SearchRequest;

class CourseController extends Controller {
    use PhotoUploadable;
    use FilteredLearnable;

    public function index(SearchRequest $request) {
        return inertia('Admin/Course', [
            'courses' => Course::search($request['search'])->paginate(10),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request, DiscussionService $service) {
        $request->validate([
            'id'          => 'int|nullable',
            'name'        => 'string|required|max:1024|unique:courses',
            'description' => 'string|required|max:2048',
            'complexity'  => [Rule::enum(Complexity::class)],
        ]);

        $oldPath = Course::firstOrNew(['id' => $request['id']])->poster_url;
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

    public function courses() {
        $complexity = ComplexityFilter::extract();

        return inertia('Skills/Course/Index', [
            'courses'               => Course::whereComplexity($complexity)
                ->select(['id', 'name', 'description', 'poster_url'])->get(),
            'learnableCoursesCount' => user()->courses()->count(),
            'filters'               => ['complexity' => $complexity],
        ]);
    }

    public function show(int $id) {
        $course = Course::whereId($id)->with('grammarRules')->firstOrFail();

        return inertia('Skills/Course/Show', [
            'course'      => $course,
            'isCompleted' => user()->courses()
                ->withPivotValue('is_completed', true)
                ->find($course)?->exists(),
        ]);
    }

    public function deletePoster(Request $request): void {
        $this->handleDeleteFile();
        Course::wherePosterUrl($request['filename'])->update(['poster_url' => null]);
    }

    public function destroy(Course $course, DiscussionService $service): void {
        $service->deleteFrom($course);
        $course->deleteWithPhoto();
    }
}
