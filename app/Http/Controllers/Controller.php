<?php

namespace App\Http\Controllers;

use App\Fuel;
use App\Transmission;
use App\HomeType;
use App\Guest;
use App\Room;
use App\Auto;
use App\City;
use App\Filters\CarsFilter;
use App\Filters\HomeFilter;
use App\House;
use App\FavoritHouse;
use App\Http\Request\Post\FilterRequest;
use App\Mark;
use App\Region;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function search_city_region()//пошук на головній сторінці за пареметрами: 1)"Популярные города", 2)"Популярные направления для отдыха"
    {
        $homes=House::where('status', '1')->paginate(9);
        $regions=Region::all();

        return view("search.search-home",compact(['regions' , 'homes']));
    }


    public function search_home(HomeFilter $request)//виведення шаблону пошуку житла
    {
        $homes_types = HomeType::all();
        $guests = Guest::all();
        $rooms = Room::all();
        $regions=Region::all();
        $homes = House::filter($request)->where('admin_status', '1')->paginate(9);
        return view('search.search-home',compact(['regions' , 'homes', 'homes_types', 'guests', 'rooms',]));
    }


    public function search_car(CarsFilter $request )//виведення шаблону пошуку авто
    {
        $regions=Region::all();
        $fuels = Fuel::all();
        $transmissions = Transmission::all();
        $marks=Mark::all();
        $cars = Auto::filter($request)->where('admin_status', '1')->paginate(9);


        return view('search.search-car', compact(['regions', 'marks', 'cars', 'transmissions', 'fuels',]));
    }


}
