<?php

namespace App\Models;

use Laravel\Jetstream\HasProfilePhoto;

trait HasCustomProfilePhoto {
    use HasProfilePhoto;

    public function updatePosterPhotoIfExist($photoOrEmpty, $storagePath = 'posters'): void {
        if (isset($photoOrEmpty)) {
            $this->updateProfilePhoto($photoOrEmpty, $storagePath);
        }
    }

}
