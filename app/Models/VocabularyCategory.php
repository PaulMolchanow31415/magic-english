<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VocabularyCategory extends Model {
    use HasFactory;
    use Searchable;

    protected $fillable = ['name'];

    public function dictionary(): HasOne {
        return $this->hasOne(Dictionary::class);
    }
}
