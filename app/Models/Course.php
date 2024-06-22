<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use App\Events\CourseCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Course extends Model {
    use Searchable, CustomHasProfilePhoto;

    protected $appends = ['profile_photo_url'];

    protected $fillable = [
        'name',
        'description',
        'poster_url',
        'complexity',
    ];

    protected $with = ['discussion'];

    protected $casts = [
        'complexity' => Complexity::class,
    ];

    protected $dispatchesEvents = [
        'created' => CourseCreated::class,
    ];

    public function grammarRules(): HasMany {
        return $this->hasMany(Grammar::class);
    }

    public function discussion(): MorphOne {
        return $this->morphOne(Discussion::class, Discussion::ALIAS);
    }

    public function students(): MorphToMany {
        return $this->morphToMany(User::class, User::LEARNABLE);
    }

    public function deleteWithPhoto(): void {
        $this->deleteProfilePhoto();
        $this->delete();
    }
}
