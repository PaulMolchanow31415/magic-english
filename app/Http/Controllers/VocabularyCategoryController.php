<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Complexity;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use App\Models\VocabularyCategory;
use Illuminate\Http\RedirectResponse;

class VocabularyCategoryController extends Controller {

    public function index(): Response|ResponseFactory {
        return inertia('Admin/VocabularyCategory', [
            'categories' => VocabularyCategory::search(request('search'))
                ->paginate(15),

            'filters' => request()->only(['search']),
        ]);
    }

    public function store(Request $request): RedirectResponse {
        $request->validate([
            'id'   => 'int|nullable',
            'name' => 'string|unique:vocabulary_categories|min:3|max:255',
        ]);

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
