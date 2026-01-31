<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Models\Address;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code',
        'address',
        'building',
        'profile_image',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function likedItems()
    {
        return $this->belongsToMany(Item::class, 'likes')->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function purchasedItems()
    {
        return $this->belongsToMany(Item::class, 'orders', 'user_id', 'item_id')->withPivot('status')->withTimestamps();
    }

    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'target_user_id');
    }

    public function averageRating()
    {
        if ($this->receivedReviews()->count() === 0) {
            return null;
        }

        return $this->receivedReviews()->avg('rating');
    }
}
