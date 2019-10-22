<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'price', 'category_id', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id'
    ];
}
