<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dictionary extends Model {
    use Searchable;

    protected $fillable = [
        'category',
        'complexity',
        'poster_url',
    ];

    protected $with = ['vocabularies', 'discussion'];

    protected $casts = [
        'complexity' => Complexity::class,
    ];

    public function vocabularies(): BelongsToMany {
        return $this->belongsToMany(Vocabulary::class);
    }

    public function discussion(): MorphOne {
        return $this->morphOne(Discussion::class, Discussion::ALIAS);
    }

    public function toSearchableArray(): array {
        return [
            'category'   => $this->category,
            'complexity' => $this->complexity->value,
            'updated_at' => $this->updated_at,
        ];
    }
}
