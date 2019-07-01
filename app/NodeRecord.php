<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NodeRecord extends Model
{
    //
    protected $fillable = [
        'node_id', 'flow', 'pressure','isStartNode','liters'
    ];
}
