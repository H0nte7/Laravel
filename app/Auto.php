<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class Auto extends Model
{
    protected $table = 'autos';

    protected $fillable = [
        'user_id', 'region_id', 'city_id', 'mark_id', 'auto_model_id', 'transmission_id', 'fuel_id',
        'cost', 'conditions', 'delivery', 'return', 'pledge_id', 'doc_id',
       'age', 'lease_term', 'pay_id'
     ];

     protected $guarded = [ 'id', 'vip', 'admin_status', 'user_status'];
   

   public function auto_type()
   {
       return $this->belongsTo(AutoType::class);
   }

    public function mark()
    {
        return $this->belongsTo(Mark::class);
    }

    public function auto_model()
    {
        return $this->belongsTo(AutoModel::class);
    }


    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        return $filter->apply($builder);
    }

    public function images()
    {
        return $this->hasMany(AutosImage::class);
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


    public function fuel()
    {
        return $this->belongsTo(Fuel::class);
    }

    public function transmission()
    {
        return $this->belongsTo(Transmission::class);
    }

    public function kits()
    {
        return $this->belongsToMany(Kit::class);
    }

    public function pay()
    {
        return $this->belongsTo(Pay::class);
    }

    public function doc()
    {
        return $this->belongsTo(Doc::class);
    }

    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }

    public function imagesOrnot(){
        return (empty($this->images))?true:false;
    }

}
