<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['user_id', 'chef_id', 'meal_id', 'star', 'body'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function chef()
    {
        return $this->belongsTo(User::class, 'chef_id');
    }
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
    //
}
