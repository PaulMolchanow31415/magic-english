<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vocabulary extends Model {
    use Searchable;

    public $timestamps = false;

    protected $fillable = [
        'en',
        'translations',
        'poster_url',
    ];

    protected $casts = [
        'translations' => AsCollection::class,
    ];

    public function toSearchableArray(): array {
        return [
            'en'           => $this->en,
            'translations' => $this->translations,
        ];
    }

    public function dictionaries(): BelongsToMany {
        return $this->belongsToMany(Dictionary::class);
    }

    public function students(): MorphToMany {
        return $this->morphToMany(User::class, User::STUDENTABLE);
    }
}
