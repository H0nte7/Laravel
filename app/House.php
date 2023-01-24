<?php

namespace App;



use App\Filters\QueryFilter;
use Dotenv\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;




class House extends Model
{


    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        return $filter->apply($builder);
    }

    protected $table = 'houses';

    protected $fillable = [
        'user_id',  'region_id',  'city_id', 'home_type_id', 'room_id', 'guest_id', 'street', 'home_number', 'total_area',
        'cost', 'conditions', 'district', 'micro_district', 'settling', 'eviction', 'pledge_id', 'doc_id', 'age', 'lease_term',
        'pay_id'
    ];

    protected $guarded = [ 'id', 'vip', 'admin_status', 'user_status',];

    

    public function images()
    {
      return $this->hasMany(HousesImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function home_type()
    {
        return $this->belongsTo(HomeType::class);
    }

    public function comforts()
    {
        return $this->belongsToMany(Comfort::class);
    }

    public function pay()
    {
        return $this->belongsTo(Pay::class);
    }

    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }

    public function doc()
    {
        return $this->belongsTo(Doc::class);
    }


    public function favorit_house()
    {
        return $this->belongsTo(FavoritHouse::class);
    }

    
    public function imagesOrnot(){
        return (empty($this->images))?true:false;
    }

}
