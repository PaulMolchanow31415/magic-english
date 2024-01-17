<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model {
    use HasFactory;

    protected $fillable = ['for_route_name'];

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }
}
