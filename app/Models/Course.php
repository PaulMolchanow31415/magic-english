<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Course extends Model {
    use Searchable;

    protected $fillable = [
        'name',
        'description',
        'poster_url',
        'complexity',
    ];

    //protected $with = ['grammarRules'];

    public function grammarRules(): HasMany {
        return $this->hasMany(Grammar::class);
    }

    public function discussion(): MorphOne {
        return $this->morphOne(Discussion::class, Discussion::ALIAS);
    }
}
