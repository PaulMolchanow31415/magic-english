<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class User extends Authenticatable implements MustVerifyEmail {
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Searchable;

    public const STUDENTABLE = 'studentable';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_banned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }

    public function vocabularies(): MorphToMany {
        return $this->morphedByMany(Vocabulary::class, self::STUDENTABLE);
    }

    public function courses(): MorphToMany {
        return $this->morphedByMany(Course::class, self::STUDENTABLE);
    }

    public function studiedVocabulary(): MorphToMany {
        return $this->vocabularies()->wherePivotIn('is_completed', 0);
    }

    public function completedVocabulary(): MorphToMany {
        return $this->vocabularies()->wherePivotIn('is_completed', 1);
    }

    public function studiedCourses(): MorphToMany {
        return $this->courses()->wherePivotIn('is_completed', 0);
    }

    public function completedCourses(): MorphToMany {
        return $this->courses()->wherePivotIn('is_completed', 1);
    }

}
