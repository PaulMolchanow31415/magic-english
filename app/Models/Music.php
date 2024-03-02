<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Music extends Model {
    use Searchable;

    protected $fillable = [
        'author_song_id',
        'album_id',
        'name',
        'lyrics',
        'audio_url',
    ];

    protected $with = ['authorSong'];

    public function authorSong(): BelongsTo {
        return $this->belongsTo(AuthorSong::class);
    }

    public function album(): BelongsTo {
        return $this->belongsTo(Album::class);
    }

}
