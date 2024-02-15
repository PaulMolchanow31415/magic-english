<?php

namespace App\Service;

use App\Models\Discussion;
use Illuminate\Database\Eloquent\Model;

class DiscussionService {

    public function createOrUpdate(Model $model): void {
        Discussion::updateOrCreate(
            ['id' => $model->discussion?->id],
            [
                'discussionable_id'   => $model->id,
                'discussionable_type' => $model::class,
            ],
        )->discussionable()->associate($model);
    }

    public function deleteFrom(Model $model): void {
        $model->discussion->delete();
    }
}