<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Inertia\ResponseFactory;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CourseCategoryController extends Controller {
    use CategoryValidatable;

    public function index(): Response|ResponseFactory {
        return inertia('Admin/CourseCategory', [
            'categories' => CourseCategory::search(request('search'))->paginate(15),
            'filters'    => request()->only(['search']),
        ]);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse {
        $this->validateRequest('course_categories');

        CourseCategory::updateOrCreate(
            ['id' => $request['id']],
            ['name' => $request['name']],
        );

        return to_route('admin.course-category.index');
    }

    public function show(CourseCategory $category) {
        //
    }

    public function destroy(int $id): RedirectResponse {
        CourseCategory::findOrFail($id)->delete();

        return to_route('admin.course-category.index');
    }
}
