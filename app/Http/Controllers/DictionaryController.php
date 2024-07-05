<?php

namespace App\Http\Controllers;

use App\Complexity;
use App\Models\Dictionary;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Service\DiscussionService;
use App\Http\Requests\SearchRequest;

class DictionaryController extends Controller {
    use PhotoUploadable;

    public function index(SearchRequest $request) {
        return inertia('Admin/Dictionary', [
            'dictionaries' => Dictionary::search($request['search'])->paginate(5),
            'filters'      => $request->only(['search']),
        ]);
    }

    public function store(Request $request, DiscussionService $service) {
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

        $service->createOrUpdate($dictionary);

        if ($request->filled('vocabulary_ids')) {
            $dictionary->vocabularies()->sync($request['vocabulary_ids']);
        }

        $dictionary->save();
    }

    public function glossary() {
        $complexity = ComplexityFilter::extract();

        return inertia('Skills/Dictionary/Index', [
            'dictionaries' => Dictionary::whereComplexity($complexity)
                ->select(['category', 'poster_url'])->get(),

            'learnableVocabulariesCount' => user()->vocabularies()->count(),

            'filters' => ['complexity' => $complexity],
        ]);
    }

    public function show(string $category) {
        return inertia('Skills/Dictionary/Show', [
            'dictionary' => Dictionary::whereCategory($category)->firstOrFail(),
        ]);
    }

    public function deletePoster(Request $request) {
        $this->handleDeleteFile();
        Dictionary::wherePosterUrl($request['filename'])->update(['poster_url' => null]);
    }

    public function destroy(int $id, DiscussionService $service) {
        $dictionary = Dictionary::findOrFail($id);
        $service->deleteFrom($dictionary);
        $this->deleteFileIfExist($dictionary->poster_url);
        $dictionary->delete();
    }
}
