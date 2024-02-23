<?php

namespace App\Http\Controllers;

use App\Models\AuthorSong;
use Illuminate\Http\Request;

class AuthorSongController extends Controller {
    use PhotoUploadable;

    public function index() {
        return inertia('Admin/Author', [
            'authors' => AuthorSong::search(request('search'))->paginate(5),
            'filters' => request()->only(['search']),
        ]);
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

    public function show(AuthorSong $authorSong) {
        //
    }

    public function deletePoster(Request $request): void {
        $this->handleDeletePoster();
        AuthorSong::wherePosterUrl($request['filename'])->update(['poster_url' => null]);
    }

    public function destroy(AuthorSong $author): void {
        $this->deleteFileIfExist($author->poster_url);
        $author->delete();
    }
}
