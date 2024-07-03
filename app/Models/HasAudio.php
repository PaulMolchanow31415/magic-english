<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasAudio {

    public function audioDisk() {
        return config('posters.disk', 'public');
    }

    public function updateAudioIfExist(?UploadedFile $audioOrEmpty): void {
            $audioOrEmpty ?? $this->updateAudio($audioOrEmpty);
    }

    public function updateAudio(UploadedFile $audio, $storagePath = 'audios'): void {
        tap($this->audio_path, function ($previous) use ($audio, $storagePath) {
            $this->forceFill([
                'audio_path' => $audio->storePublicly($storagePath, ['disk' => $this->audioDisk()]),
            ])->save();

            if ($previous) {
                Storage::disk($this->audioDisk())->delete($previous);
            }
        });
    }

    public function deleteAudio(): void {
        if (isset($this->audio_path)) {
            Storage::disk($this->audioDisk())->delete($this->audio_path);
        }
        $this->forceFill(['audio_path' => null])->save();
    }

    public function audioUrl(): Attribute {
        return Attribute::get(function () {
            return $this->audio_path ?? Storage::disk($this->audioDisk())->url($this->audio_path);
        });
    }

    public function getAudioUrlAttribute(): Attribute {
        return $this->audioUrl();
    }
}
