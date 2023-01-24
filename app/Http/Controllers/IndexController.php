<?php

namespace App\Http\Controllers;

use App\Auto;
use App\House;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()//лічильники кількості публікацій житло\авто на головній сторінці
    {
        $homes = House::all()->count();
        $cars = Auto::all()->count();

        return view('home', ['homes' =>$homes], ['cars' =>$cars]);


    }
}
