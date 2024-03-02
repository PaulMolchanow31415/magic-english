<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuthorSong extends Model {
    use Searchable;

    protected $fillable = [
        'name',
        'biography',
        'poster_url',
    ];

    public function musics(): HasMany {
        return $this->hasMany(Music::class);
    }

}
