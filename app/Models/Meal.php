<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chef_id',
        'category_id',
        'name',
        'description',
        'price',
        'number_persons',
        'is_available',
        'prep_time_minutes',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
            'number_persons' => 'integer',
            'prep_time_minutes' => 'integer',
        ];
    }

    public function chef()
    {
        return $this->belongsTo(User::class, 'chef_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function image()
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
