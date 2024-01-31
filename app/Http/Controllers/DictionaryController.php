<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Dictionary;
use App\Models\Complexity;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use Illuminate\Validation\Rule;
use App\Models\VocabularyCategory;

class DictionaryController extends Controller {
    use PhotoUploadable;

    public function index(): Response|ResponseFactory {
        return inertia('Admin/Dictionary', [
            'dictionaries' => Dictionary::search(request('search'))->paginate(5),
            'filters'      => request()->only(['search']),
        ]);
    }

    public function store(Request $request): void {
        $request->validate([
            'id'                  => 'int|nullable',
            'category_id'         => 'int|nullable',
            'vocabulary_ids'      => 'array|nullable',
            'complexity'          => [Rule::enum(Complexity::class)],
            'photo_external_path' => 'active_url|nullable',
        ]);

        $dictionary = Dictionary::firstOrNew(['id' => $request['id']]);
        $oldPath = $dictionary->poster_url;
        $updatedPath = $this->upload($oldPath);

        $dictionary = Dictionary::updateOrCreate(
            ['id' => $request['id']],
            [
                'complexity'             => $request['complexity'],
                'vocabulary_category_id' => $request['category_id'],
                'poster_url'             => $updatedPath ??
                                            $request['photo_external_path'] ?? $oldPath,
            ],
        );

        $dictionary->discussion()->save(
            $dictionary->discussion ?: new Discussion(),
        );

        if ($request->filled('category_id')) {
            $dictionary->category()->associate(
                VocabularyCategory::findOrFail($request['category_id']),
            );
        }

        if ($request->filled('vocabulary_ids')) {
            $dictionary->vocabularies()->sync($request['vocabulary_ids']);
        }

        $dictionary->save();
    }

    public function show(Dictionary $dictionary) {
        //
    }

    public function deletePoster(Request $request): void {
        $request->validate(['filename' => 'required|string']);

        $this->deleteFileIfExist($request['filename']);

        Dictionary::wherePosterUrl($request['filename'])->update(['poster_url' => null]);
    }

    public function destroy(int $id): void {
        $dictionary = Dictionary::findOrFail($id);
        $this->deleteFileIfExist($dictionary->poster_url);
        $dictionary->delete();
    }
}
