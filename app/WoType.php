<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WoType extends BaseModel
{
    protected $table = 'wo_type';

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}
