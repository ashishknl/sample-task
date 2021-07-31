<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DemoTask extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'title','order', 'status','column_id',
    ];
}