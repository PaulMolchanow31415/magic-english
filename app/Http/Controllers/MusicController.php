<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;

class MusicController extends Controller {
    use AudioUploadable;

    public function index(SearchRequest $request) {
        return inertia('Admin/Music', [
            'musics'  => Music::search($request['search'])->paginate(5),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'id'        => 'nullable|int',
            'singer_id' => 'required|int',
            'name'      => 'string|required',
            'lyrics'    => 'required|string|min:5',
        ]);

        $music = Music::firstOrNew(['id' => $request['id']]);
        $oldUrl = $music->audio_url;
        $updatedPath = $this->upload($oldUrl);

        Music::updateOrCreate(['id' => $request['id']], [
            'singer_id' => $request['singer_id'],
            'name'      => $request['name'],
            'lyrics'    => $request['lyrics'],
            'audio_url' => $updatedPath ?? $oldUrl,
        ]);
    }

    public function deleteAudio(Request $request) {
        $this->handleDeleteFile();
        Music::whereAudioUrl($request['filename'])->update(['audio_url' => null]);
    }

    public function destroy(Music $music) {
        $this->deleteFileIfExist($music->audio_url);
        $music->delete();
    }
}
