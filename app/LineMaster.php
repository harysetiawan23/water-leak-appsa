<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LineMaster extends Model
{
    //
    protected $fillable = [
        'name', 'start', 'end','distance','diameter','thicknes','manufacture','user_id','start_node_id','end_node_id'
    ];

}
