<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leak_event extends Model
{
    //
    protected $fillable = [
        'line_id','solved','user_id','informed'
    ];
}
