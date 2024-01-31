<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dictionary extends Model {
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'vocabulary_category_id',
        'complexity',
        'poster_url',
    ];

    protected $with = ['category', 'vocabularies'];

    protected $casts = [
        'complexity' => Complexity::class,
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(VocabularyCategory::class, 'vocabulary_category_id');
    }

    public function vocabularies(): BelongsToMany {
        return $this->belongsToMany(Vocabulary::class);
    }

    public function discussion(): MorphOne {
        return $this->morphOne(Discussion::class, Discussion::$alias);
    }

    public function toSearchableArray(): array {
        return [
            'category'   => $this->category,
            'complexity' => $this->complexity->value,
            'updated_at' => $this->updated_at,
        ];
    }
}
