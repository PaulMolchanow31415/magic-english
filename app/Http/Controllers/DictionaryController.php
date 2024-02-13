<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Dictionary;
use App\Models\Complexity;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use Illuminate\Validation\Rule;

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
            'category'            => 'string|required|unique:dictionaries',
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
                'category'   => $request['category'],
                'complexity' => $request['complexity'],
                'poster_url' => $updatedPath ?? $request['photo_external_path'] ?? $oldPath,
            ],
        );

        $dictionary->discussion()->save(
            $dictionary->discussion ?: new Discussion(),
        );

        if ($request->filled('vocabulary_ids')) {
            $dictionary->vocabularies()->sync($request['vocabulary_ids']);
        }

        $dictionary->save();
    }

    public function glossary(): Response|ResponseFactory {
        return inertia('Skills/Dictionary/Index', [
            'dictionaries'               => Dictionary::paginate(4),
            'learnableVocabulariesCount' => auth()->user()->vocabularies()->count(),
        ]);
    }

    public function show(string $category) {
        return inertia('Skills/Dictionary/Show', [
            'dictionary' => Dictionary::whereCategory($category)->firstOrFail(),
        ]);
    }

    public function deletePoster(Request $request): void {
        $this->handleDeletePoster();
        Dictionary::wherePosterUrl($request['filename'])->update(['poster_url' => null]);
    }

    public function destroy(int $id): void {
        $dictionary = Dictionary::findOrFail($id);
        $this->deleteFileIfExist($dictionary->poster_url);
        $dictionary->delete();
    }
}
