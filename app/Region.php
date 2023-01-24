<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';

    public function cities()
    {
        return $this->hasMany(City::class);
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
