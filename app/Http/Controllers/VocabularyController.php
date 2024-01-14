<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

class VocabularyController extends Controller {
    use PhotoUploadable;

    public function index(): Response|ResponseFactory {
        return inertia('Admin/Vocabulary', [
            'dictionary' => Vocabulary::search(request('search'))->paginate(30),
            'filters'    => request()->only(['search']),
        ]);
    }

    public function show(Vocabulary $vocabulary) {
        //
    }

    public function store(Request $request): RedirectResponse {
        $request->validate([
            'id'                  => 'int|nullable',
            'en'                  => 'required|string',
            'translations'        => 'required|array',
            'photo_external_path' => 'url|nullable',
        ]);

        $word = Vocabulary::firstOrNew(['id' => $request['id']]);
        $oldPath = $word->poster_url;
        $updatedPath = $this->upload($oldPath, $request);

        Vocabulary::updateOrCreate(
            ['id' => $request['id']],
            [
                'en'           => $request['en'],
                'translations' => $request['translations'],
                'poster_url'   => $updatedPath ?? $request['photo_external_path'] ?? $oldPath,
            ],
        );

        return to_route('admin.vocabulary.index');
    }

    /**
     * Сохраняет слово если оно отсутствует в бд
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return Collection - коллекция переведенных слов на русском языке
     */
    public function translate(Request $request): Collection {
        $request->validate(['word' => 'string|required']);

        $word = mb_strtolower($request['word'], 'UTF-8');

        $vocabulary = Vocabulary::where('en', $word)->firstOr(function () use ($word, $request) {
            $response = Http::get(
                'https://api.mymemory.translated.net/get?q='.$request['word'].'&langpair=en|ru',
            )->json('matches');

            $formatted = array_map(function ($item) {
                $t = mb_strtolower($item['translation'], 'UTF-8'); // convert to lower case
                $t = preg_replace('/^\s+|\s+$/u', '', $t);         // trim

                return preg_replace('#[^а-я\s]#iu', '', $t);       // replace other chars
            }, $response);

            $formatted = array_unique($formatted, SORT_LOCALE_STRING);

            $formatted = array_filter($formatted, function ($translation) {
                return preg_match('/[А-Яа-яЁё]/u', $translation);
            });

            return Vocabulary::create([
                'en'           => $word,
                'translations' => array_values($formatted),
            ]);
        });

        return $vocabulary->translations;
    }

    /**
     * Удаляет перевод слова по id
     * Мы передаем 2 параметра в request из-за разных кодеровок в бд и браузере
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function deleteTranslation(Request $request): void {
        $request->validate([
            'vocabularyId'       => 'required|int',
            'removalTranslation' => 'required|string',
        ]);

        $vocabulary = Vocabulary::findOrFail($request['vocabularyId']);
        $vocabulary->translations = $vocabulary->translations
            ->filter(fn($str) => $str !== $request['removalTranslation'])->values();
        $vocabulary->save();
    }

    public function deletePoster(Request $request): void {
        $request->validate(['filename' => 'required|string']);

        $this->deleteFileIfExist($request['filename']);

        Vocabulary::where('poster_url', $request['filename'])->update(['poster_url' => null]);
    }

    public function destroy(int $id): RedirectResponse {
        $vocabulary = Vocabulary::findOrFail($id);
        $this->deleteFileIfExist($vocabulary->poster_url);
        $vocabulary->delete();

        return to_route('admin.vocabulary.index');
    }
}
