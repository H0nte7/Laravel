<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class HousesImage extends Model
{

    protected $table = 'houses_images';

    protected $fillable = [
      'image', 'house_id',
    ];

    protected $guarded = ['id'];



    public function houses()
    {
        return $this->belongsTo(House::class);
    }


    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }


    


}
