<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
   protected $guarded = ['id'];

    public function Country()
    {
        return $this->hasMany(Country::class, 'coutntry_id');
    }
}