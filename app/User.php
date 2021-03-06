<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    public function games() {
        return $this->hasMany('App\Game');
    }

}
