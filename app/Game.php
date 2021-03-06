<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    public function owner() {
        return $this->belongsTo('App\User', 'user_id');
    }

}
