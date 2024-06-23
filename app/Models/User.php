<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone_number',
        'phone_verified',
        'gender',
        'role',
        'avatar',
        'provider',
        'provider_id',
        'provider_token',
    ];

    protected $hidden = [
        'password',
        'role',
        'remember_token',
        'provider_id',
        'provider_token',
        'provider',
        'phone_verified',
        'email_veified_at',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function preferences()
    {
        return $this->hasMany(Preference::class);
    }
    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id', 'id');
    }

    public function shops()
    {
        return $this->hasOne(Shop::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'user_id', 'id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }

    public static function generateUserName($username)
    {
        if ($username == null) {
            $username = Str::lower(Str::random(8));
        }
        if (User::where('username', '=', $username)->exists()) {
            $newUsername = $username . Str::lower(Str::random(3));
            return self::generateUsername($newUsername);
        }
        return $username;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function styles()
    {
        return $this->belongsToMany(Style::class, 'preferences');
    }

    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    public function isSeller()
    {
        return $this->role === 'Seller';
    }

    public function coupons()
    {
        return $this->hasMany(AppCoupon::class);
    }

   public function followings(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'followable', 'followers', 'follower_id', 'followable_id')
            ->withPivot('followable_type')
            ->withTimestamps();
    }

    // Define relationship for following shops
    public function followingShops(): MorphToMany
    {
        return $this->morphedByMany(Shop::class, 'followable', 'followers', 'follower_id', 'followable_id')
            ->withPivot('followable_type')
            ->withTimestamps();
    }

    // Define relationship for followers
    public function follows(): MorphMany
    {
        return $this->morphMany(Follower::class, 'followable');
    }

    // Check if following a particular entity
    public function isFollowing($followableType, $followableId)
    {
        return $this->followings()
            ->where('followable_id', $followableId)
            ->where('followable_type', ucfirst($followableType))
            ->exists();
    }

    // Unfollow a particular entity
    public function unfollow($followableType, $followableId)
    {
        $followableType = ucfirst($followableType); // Ensure proper casing

        $pivotQuery = $this->followings()
            ->wherePivot('followable_id', $followableId)
            ->wherePivot('followable_type', $followableType);

        if ($pivotQuery->exists()) {
            $pivotQuery->detach();
            return true;
        }

        return false;
    }


}
