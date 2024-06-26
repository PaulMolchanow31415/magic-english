<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail {
    use HasApiTokens;
    use HasFactory;
    use CustomHasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Searchable;
    use Billable;

    public const LEARNABLE = 'learnable';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_banned',
        'is_accept_cookies',
        'social_id',
        'social_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = ['email_verified_at' => 'datetime'];

    protected $appends = ['profile_photo_url'];

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }

    public function vocabularies(): MorphToMany {
        return $this->morphedByMany(Vocabulary::class, self::LEARNABLE);
    }

    public function courses(): MorphToMany {
        return $this->morphedByMany(Course::class, self::LEARNABLE);
    }

    public function lessons(): MorphToMany {
        return $this->morphedByMany(Lesson::class, self::LEARNABLE);
    }

    public function products(): BelongsToMany {
        return $this->belongsToMany(\App\Models\Product::class);
    }

    public function cart(): HasOne {
        return $this->hasOne(Cart::class);
    }

    public function deleteWithPhoto(): void {
        $this->deleteProfilePhoto();
        $this->delete();
    }

    public static function isUnlocked(): bool {
        return auth()->check() && !auth()->user()->is_banned;
    }

    public function toSearchableArray(): array {
        return [
            'name'      => $this->name,
            'email'     => $this->email,
            'password'  => $this->password,
            'role'      => $this->role,
            'is_banned' => $this->is_banned,
        ];
    }

}
