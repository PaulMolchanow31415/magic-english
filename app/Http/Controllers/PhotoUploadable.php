<?php

namespace App\Http\Controllers;

trait PhotoUploadable {
    use Uploadable;

    public static function imageRules(int $maxSize): array {
        return ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:'.$maxSize];
    }

    /**
     * Сохраняет файл фотографии и удаляет старый, если он существует
     *
     * @param  string|null  $oldPath
     * @param  int|null     $maxFileSize  - максимальный размер загружаемого файла
     *
     * @return string|null
     */
    public function upload(?string $oldPath, ?int $maxFileSize = 20_000): ?string {
        request()->validate(['photo' => self::imageRules($maxFileSize)]);

        $path = null;

        if (!empty(request('photo'))) {
            $this->deleteFileIfExist($oldPath);
            $path = self::getPrefix().request()->file('photo')->store('uploads', 'public');
        }

        return $path;
    }
}
