<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model {
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'email',
        'is_enabled',
    ];

}
