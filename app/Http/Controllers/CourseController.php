<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Complexity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller {
    use PhotoUploadable;

    public function index() {
        return inertia('Admin/Course', [
            'courses' => Course::search(request('search'))->paginate(10),
            'filters' => request()->only(['search']),
        ]);
    }

    public function store(Request $request) {
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

        Course::updateOrCreate(
            ['id' => $request['id']],
            [
                'name'        => $request['name'],
                'description' => $request['description'],
                'complexity'  => $request['complexity'],
                'poster_url'  => $updatedPath ?? $request['photo_external_path'] ?? $oldPath,
            ],
        );
    }

    public function show(Course $course) {
        //
    }

    public function deletePoster(Request $request): void {
        $this->handleDeletePoster();
        Course::wherePosterUrl($request['filename'])->update(['poster_url' => null]);
    }
    
    public function destroy(int $id): void {
        $course = Course::findOrFail($id);
        $this->deleteFileIfExist($course->poster_url);
        $course->delete();
    }
}
