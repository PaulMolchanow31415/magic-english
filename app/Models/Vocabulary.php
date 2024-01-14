<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vocabulary extends Model {
    use HasFactory;
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
        return ['en' => $this->en];
    }
}
