<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model {

    protected $fillable = [
        'creator_id',
        'reply_to_id',
        'discussion_id',
        'message',
        'is_reported',
    ];

    protected $appends = ['creator'];

    public function getCreatorAttribute() {
        return $this->creator()->get(['name', 'role', 'profile_photo_path'])->first();
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function discussion(): BelongsTo {
        return $this->belongsTo(Discussion::class);
    }

    public function repliers(): HasMany {
        return $this->hasMany(Comment::class);
    }

    public function reply_to(): BelongsTo {
        return $this->belongsTo(Comment::class, 'reply_to_id');
    }

}
