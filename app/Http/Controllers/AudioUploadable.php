<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rules\File;

// todo: extract to the trait
trait AudioUploadable {
    use Uploadable;

    /**
     * Сохраняет аудио и удаляет старое, если оно существует
     *
     * @param  string|null  $oldPath
     *
     * @return string|null
     */
    public function upload(?string $oldPath): ?string {
        request()->validate([
            'audio' => [
                'nullable',
                File::types(['mp3', 'wav'])->min('3mb')->max('100mb'),
            ],
        ]);

        $path = null;

        if (!empty(request('audio'))) {
            $this->deleteFileIfExist($oldPath);
            $path = self::getPrefix().request()->file('audio')->store('uploads', 'public');
        }

        return $path;
    }

}
