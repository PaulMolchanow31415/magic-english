<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

trait Uploadable {

    public static function getPrefix() {
        return config('filesystems.upload_prefix');
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

    public function handleDeleteFile() {
        request()->validate(['filename' => 'required|string']);
        $this->deleteFileIfExist(request('filename'));
    }

}