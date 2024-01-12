<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabularyCategory extends Model {
    use HasFactory;
    use Searchable;

    protected $fillable = ['name'];
}
