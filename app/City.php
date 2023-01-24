<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    public function regions()
    {
        return $this->belongsTo(Region::class);
    }

    public function cars()
    {
        return $this->hasMany(Auto::class);
    }

    public function homes()
    {
        return $this->hasMany(House::class);
    }

}
