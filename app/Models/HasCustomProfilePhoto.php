<?php

namespace App\Models;

use Laravel\Jetstream\HasProfilePhoto;

trait HasCustomProfilePhoto {
    use HasProfilePhoto;

    public function updatePosterPhotoIfExist($photoOrEmpty): void {
            $photoOrEmpty ?? $this->updateProfilePhoto($photoOrEmpty);
    }

}
