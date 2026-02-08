<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = ['chef_id', 'category_id', 'name', 'description', 'price', 'number_persons'];


    public function chef()
    {
        return $this->belongsTo(User::class, 'chef_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    public function averageRating()
    {
        return $this->ratings()->avg('star') ?: 0;
    }
}
