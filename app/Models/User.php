<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'kitchen_name',
        'bio',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    

    public function meals()
    {
        return $this->hasMany(Meal::class, 'chef_id');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }




    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isChef()
    {
        return $this->role === 'chef';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }
}
