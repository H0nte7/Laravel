<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\House;
use App\Auto;

class User extends Authenticatable
{
    use Notifiable;


    protected $fillable = [
        'name', 'phone', 'second_phone', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $guarded = ['id', 'admin', 'ban'];

    public function cars()
    {
        return $this->hasMany(Auto::class);
    }

    public function homes()
    {
        return $this->hasMany(House::class);
    }

    public function favorit_houses()
    {
        return $this->hasMany(FavoritHouse::class);
    }






}
