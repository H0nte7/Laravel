<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class AutosImage extends Model
{
    protected $fillable=[
        'image', 'auto_id',
    ];

    public function autos(){
        return $this->belongsTo(Auto::class);
    }



    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
