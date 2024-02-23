<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuthorSong extends Model {

    protected $fillable = [
        'name',
        'biography',
        'poster_url',
    ];

    public function musics(): HasMany {
        return $this->hasMany(Music::class);
    }

    public function albums(): HasMany {
        return $this->hasMany(Album::class);
    }
    
}
