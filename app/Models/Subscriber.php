<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subscriber extends Model {
    use Notifiable;
    use Searchable;

    protected $fillable = [
        'email',
        'is_enabled',
    ];

}
