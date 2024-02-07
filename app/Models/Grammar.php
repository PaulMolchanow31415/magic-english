<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grammar extends Model {
    use Searchable;

    protected $fillable = [
        'title',
        'content',
        'order',
        'phonetics',
        'course_id',
    ];

    protected $casts = [
        'phonetics' => AsCollection::class,
    ];

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }
}
