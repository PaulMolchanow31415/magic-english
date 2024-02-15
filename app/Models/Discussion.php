<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model {
    
    public const ALIAS = 'discussionable';

    public $timestamps = false;

    protected $fillable = [
        'discussionable_id',
        'discussionable_type',
    ];

    protected $with = ['comments'];

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }

    public function discussionable(): MorphTo {
        return $this->morphTo();
    }
}
