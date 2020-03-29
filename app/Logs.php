<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    public function ticket(){
        return $this->belongsTo('App\Ticket');
    }
}
