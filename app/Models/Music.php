<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Music extends Model {
    use Searchable, HasAudio;

    protected $appends = ['audio_url'];

    protected $fillable = [
        'singer_id',
        'name',
        'lyrics',
    ];

    protected $with = ['singer'];

    public function singer(): BelongsTo {
        return $this->belongsTo(Singer::class);
    }

    public function toSearchableArray(): array {
        return [
            'name'   => $this->name,
            'lyrics' => $this->lyrics,
        ];
    }

}
