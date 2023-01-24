<?php
namespace App\Filters;



use Illuminate\Http\Client\Request;

class CarsFilter extends QueryFilter{

    public function region_id_all($id)
        {
            return $this->builder->all();
        }

    public function region_id($id)
    {
        return $this->builder->where('region_id', $id);
    }

    public function city_id($id)
    {
        return $this->builder->where('city_id', $id);
    }

    public function mark_id($id)
    {
        return $this->builder->where('mark_id', $id);
    }

    public function auto_model_id($id)
    {
        return $this->builder->where('auto_model_id', $id);
    }

    public function transmission_id($id)
    {
        return $this->builder->where('transmission_id', $id);
    }

    public function fuel_id($id)
    {
        return $this->builder->where('fuel_id', $id);
    }



    /*public function min_cost($id)
    {

        return $this->builder->where('cost', '>=' , $id);
    }

    public function max_cost($id)
    {
        return $this->builder->where('cost',  '<=', $id );
    }*/


}
