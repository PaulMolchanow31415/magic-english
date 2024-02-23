<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Music extends Model {

    protected $fillable = [
        'author_song_id',
        'album_id',
        'name',
        'audio_url',
        'lyrics',
    ];

    protected $casts = [
        'lyrics' => AsArrayObject::class,
    ];

    public function authorSong(): BelongsTo {
        return $this->belongsTo(AuthorSong::class);
    }

    public function album(): BelongsTo {
        return $this->belongsTo(Album::class);
    }

}
