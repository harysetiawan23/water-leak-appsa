<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeakEvent extends Model
{
    //
    protected $fillable = [
        'line_id','solved','user_id','informed'
    ];
}
