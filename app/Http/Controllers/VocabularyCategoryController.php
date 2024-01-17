<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use App\Models\VocabularyCategory;
use Illuminate\Http\RedirectResponse;

class VocabularyCategoryController extends Controller {
    use CategoryValidatable;

    public function index(): Response|ResponseFactory {
        return inertia('Admin/VocabularyCategory', [
            'categories' => VocabularyCategory::search(request('search'))->paginate(15),

            'filters' => request()->only(['search']),
        ]);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse {
        $this->validateRequest('vocabulary_categories');

        VocabularyCategory::updateOrCreate(
            ['id' => $request['id']],
            ['name' => $request['name']],
        );

        return to_route('admin.vocabulary-category.index');
    }

    public function show(VocabularyCategory $category) {
        //
    }

    public function destroy(int $id): RedirectResponse {
        VocabularyCategory::findOrFail($id)->delete();

        return to_route('admin.vocabulary-category.index');
    }
}
