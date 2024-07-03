<?php

namespace App\Models;

use App\Complexity;
use Laravel\Scout\Searchable;
use App\Events\LessonCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Lesson extends Model {
    use Searchable;

    protected $fillable = [
        'number',
        'content',
        'complexity',
    ];

    protected $with = ['discussion'];

    protected $casts = [
        'complexity' => Complexity::class,
    ];

    protected $dispatchesEvents = [
        'created' => LessonCreated::class,
    ];

    public function discussion(): MorphOne {
        return $this->morphOne(Discussion::class, Discussion::ALIAS);
    }

    public function students(): MorphToMany {
        return $this->morphToMany(User::class, User::LEARNABLE);
    }
}
