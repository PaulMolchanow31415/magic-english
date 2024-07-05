<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasPoster {

    protected ?string $posterExternalPath = null;

    public function posterPhotoDisk() {
        return config('posters.disk', 'public');
    }

    public function updatePosterPhotoIfExist(UploadedFile|string|null $photoOrEmpty): void {
        is_string($photoOrEmpty)
            ? $this->posterExternalPath = $photoOrEmpty
            : $photoOrEmpty ?? $this->updatePosterPhoto($photoOrEmpty);
    }

    public function updatePosterPhoto(UploadedFile $photo, $storagePath = 'posters'): void {
        tap($this->poster_path, function ($previous) use ($photo, $storagePath) {
            $this->forceFill([
                'poster_path' => $photo->storePublicly($storagePath, [
                    'disk' => $this->posterPhotoDisk(),
                ]),
            ])->save();

            if ($previous) {
                Storage::disk($this->posterPhotoDisk())->delete($previous);
            }
        });
    }

    public function deletePosterPhoto(): void {
        if (isset($this->poster_path)) {
            Storage::disk($this->posterPhotoDisk())->delete($this->poster_path);
        }
        $this->forceFill(['poster_path' => null])->save();
    }

    public function posterPhotoUrl(): Attribute {
        return Attribute::get(fn() => $this->poster_path
            ? Storage::disk($this->posterPhotoDisk())->url($this->poster_path)
            : $this->posterExternalPath);
    }

    public function getPosterUrlAttribute(): Attribute {
        return $this->posterPhotoUrl();
    }

}
