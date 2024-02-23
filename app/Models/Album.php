<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Album extends Model {

    protected $fillable = [
        'author_song_id',
        'name',
        'description',
        'poster_url',
        'release_year',
    ];

    public function authorSong(): BelongsTo {
        return $this->belongsTo(AuthorSong::class);
    }

}
