<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $fillable = ['user_id', 'chef_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function chef()
    {
        return $this->belongsTo(User::class, 'chef_id');
    }
    //
}
