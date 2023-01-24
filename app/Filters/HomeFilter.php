<?php
 namespace App\Filters;



 use Illuminate\Http\Client\Request;

 class HomeFilter extends QueryFilter{

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

        public function home_type($id)
        {
            return $this->builder->where('home_type_id', $id);
        }

        public function rooms($id)
        {
            return $this->builder->where('room_id', $id);
        }

        public function guest($id)
        {
            return $this->builder->where('guest_id', $id);
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
