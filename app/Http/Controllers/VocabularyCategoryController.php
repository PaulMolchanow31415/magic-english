<?php

namespace App\Http\Controllers;

use Inertia\Response;
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
            'name' => 'string|unique|min:3|max:255',
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

    public function destroy(VocabularyCategory $category): RedirectResponse {
        $category->delete();

        return to_route('admin.vocabulary-category.index');
    }
}
