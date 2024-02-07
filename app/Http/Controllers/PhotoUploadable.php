<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

trait PhotoUploadable {

    public static function getPrefix() {
        return config('filesystems.upload_prefix');
    }

    public static function imageRules(int $maxSize): array {
        return ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:'.$maxSize];
    }

    public function isExternalUrl($url): bool {
        $components = parse_url($url);

        // файлы внутри /storage/uploads не имеют схемы в бд
        return !empty($components['scheme']);
    }

    public function deleteFileIfExist(?string $filepath): bool {
        if ($this->isExternalUrl($filepath)) {
            return true;
        }

        if (!empty($filepath)) {
            $filepath = mb_ereg_replace(self::getPrefix(), '', $filepath);

            return Storage::disk('public')->delete($filepath);
        }

        return false;
    }

    /**
     * Сохраняет файл и удаляет старый, если он существует
     *
     * @param  string|null  $oldPath
     * @param  int|null     $maxFileSize  - максимальный размер загружаемого файла
     *
     * @return string|null
     */
    public function upload(?string $oldPath, ?int $maxFileSize = 20_000): ?string {
        request()->validate(['photo' => self::imageRules($maxFileSize)]);

        $path = null;
        $this->deleteFileIfExist($oldPath);

        if (!empty(request('photo'))) {
            $path = self::getPrefix().request()->file('photo')->store('uploads', 'public');
        }

        return $path;
    }

    public function handleDeletePoster() {
        request()->validate(['filename' => 'required|string']);
        $this->deleteFileIfExist(request('filename'));
    }
}
