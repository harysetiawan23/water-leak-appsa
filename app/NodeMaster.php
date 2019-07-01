<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NodeMaster extends Model
{
    //
    protected $fillable = [
        'sn', 'phone_number', 'lng','lat','isStartNode','isOnline','user_id','pressOffset','liquidFlowKonstanta','flow_rate_model','pressure_tranducer_model'
    ];
}
