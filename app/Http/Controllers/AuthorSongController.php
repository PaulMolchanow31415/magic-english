<?php

namespace App\Http\Controllers;

use App\Models\AuthorSong;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthorSongController extends Controller {
    use PhotoUploadable;

    public function index() {
        return inertia('Admin/Author', [
            'authors' => AuthorSong::search(request('search'))->paginate(5),
            'filters' => request()->only(['search']),
        ]);
    }

    public function show(AuthorSong $singer) {
        if ($singer->musics()->count() === 0) {
            return redirect()->back();
        }

        return inertia('Music/Index', [
            'singer' => $singer,
            'musics' => $singer->musics()->select(['id', 'name', 'audio_url', 'lyrics'])->get(),
        ]);
    }

    public function list(string $search): JsonResponse {
        return response()->json(
            AuthorSong::search($search)->paginate(15),
        );
    }

    public function store(Request $request) {
        $request->validate([
            'id'                  => 'nullable|int',
            'name'                => 'string|required|max:1024|unique:author_songs',
            'biography'           => 'string|required|min:5',
            'photo_external_path' => 'active_url|nullable',
        ]);

        $author = AuthorSong::firstOrNew(['id' => $request['id']]);
        $oldPath = $author->poster_url;
        $updatedPath = $this->upload($oldPath);

        AuthorSong::updateOrCreate(['id' => $request['id']], [
            'name'       => $request['name'],
            'biography'  => $request['biography'],
            'poster_url' => $updatedPath ?? $request['photo_external_path'] ?? $oldPath,
        ]);
    }

    public function deletePoster(Request $request): void {
        $this->handleDeleteFile();
        AuthorSong::wherePosterUrl($request['filename'])->update(['poster_url' => null]);
    }

    public function destroy(AuthorSong $author): void {
        $this->deleteFileIfExist($author->poster_url);
        $author->delete();
    }
}
