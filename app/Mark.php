<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $table = 'marks';

    public function auto_model()
    {
        return $this->hasMany(AutoModel::class);
    }

    public function auto_type()
    {
        return $this->belongsTo(AutoType::class);
    }

    public function cars()
    {
        return $this->hasMany(Auto::class);
    }
}
