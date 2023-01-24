<?php

namespace App\Http\Controllers;

use App\FavoritHouse;
use App\City;
use App\Comfort;
use App\Doc;
use App\Guest;
use App\HomeType;
use App\HousesImage;
use App\Pay;
use App\Pledge;
use App\Region;
use App\Room;
use App\User;
use Illuminate\Http\Request;
use App\House;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;


class HouseController extends Controller
{

    public function showPublication_homeForm()//виведення шаблона публікації житла
    {
        $regions = Region::all();
        $homes_types = HomeType::all();
        $guests = Guest::all();
        $rooms = Room::all();
        $comforts = Comfort::all();
        $pays = Pay::all();
        $pledges = Pledge::all();
        $docs = Doc::all();

        return view("publication.publication-home", compact(['regions', 'homes_types', 'guests', 'rooms', 'comforts', 'pays', 'pledges', 'docs']));
    }

    public function getCity(Request $request)//отримання міста залежно від вибраної області
    {
         $data=City::select('name', 'id')->where('region_id', $request->id)->get();
         return response()->json($data);
    }

    public function publication_home(Request $request)//публікація житла
    {
        $request->validate([
            "region_id" => ["required", "string"],
            "city_id" => ["required", "string"],
            "home_type_id"=>["required", "string"],
            "images"=>'required|array|min:3|max:6|',
            "room_id"=>'required|integer|min:1|max:8',
            "guest_id"=>'required|integer|min:1|max:8',
            "street" => 'required|string|min:5|max:30',
            "home_number" => 'required|string|min:1|max:30',
            "total_area" => 'required|regex:/^(\d+(?:[\.\,]\d{2})?)$/|min:1|max:10000',
            "cost" => 'required|numeric|min:1|max:1000000',
            "comforts"=>'required|min:1',
            "conditions" => 'required|max:1500',
            "district" => 'required|string|min:3|max:30',
            "micro_district" => 'nullable|string|min:3|max:20',
            "settling" => ["required", "date_format:H:i"],
            "eviction" => ["required", "date_format:H:i"],
            "pledge_id" => ["required", "string"],
            "doc_id" => ["required", "string"],
            "age" => 'required|integer|min:14|max:1000',
            "lease_term" => 'required|integer|min:1|max:1000',
            "pay_id" => ["required", "string"],

        ]);

        $request->old('region_id');
        $request->old('city_id');
        $request->old('home_type_id');
        $request->old('images');
        $request->old('room_id');
        $request->old('guest_id');
        $request->old('street');
        $request->old('home_number');
        $request->old('total_area');
        $request->old('cost');
        $request->old('comforts');
        $request->old('conditions');
        $request->old('district');
        $request->old('micro_district');
        $request->old('settling');
        $request->old('eviction');
        $request->old('pledge_id');
        $request->old('doc_id');
        $request->old('age');
        $request->old('lease_term');
        $request->old('pay_id');


        $home = new House([
            
        ]);
        $home->region_id = $request->input('region_id');
        $home->city_id = $request->input('city_id');
        $home->home_type_id = $request->input('home_type_id');

        $home->user_id = Auth::user()->id;
        $home->room_id = $request->input('room_id');
        $home->guest_id = $request->input('guest_id');
        $home->street = $request->input('street');
        $home->home_number = $request->input('home_number');
        $home->total_area = $request->input('total_area');
        $home->cost = $request->input('cost');
        $home->conditions = $request->input('conditions');
        $home->district = $request->input('district');
        $home->micro_district = $request->input('micro_district');
        $home->settling = $request->input('settling');
        $home->eviction = $request->input('eviction');
        $home->pledge_id= $request->input('pledge_id');
        $home->doc_id = $request->input('doc_id');
        $home->age = $request->input('age');
        $home->lease_term = $request->input('lease_term');
        $home->pay_id = $request->input('pay_id');

        $home->save();

        if ($request->input('comforts')) {
            $home->comforts()->attach($request->input('comforts'));
        }


        if($request->hasFile('images'))//додавання водяного знаку на додоткові фото
        {
            $files=$request->file('images');
            foreach ($files as $file)
            {
                $imageName=time().'_'.$file->getClientOriginalName();
                $request['house_id']=$home->id;
                $request['image']=$imageName;
                $path= public_path("/photo/add/houses/". $imageName);
                $imageResize = Image ::make($file->getRealPath());
                $imageResize->resize(400,300);
                $imageResize->text('Hello World', 180, 275, function($font)
                {
                    $font->file(public_path('fonts/Roboto/Roboto-Black.ttf'));
                    $font->size(24);
                    $font->color('#fdf6e3');
                })->save($path);
                $imageResize->save();
                HousesImage::create($request->all());
            }
        }

        return redirect(route("office"));
    }

    public function show_house_page($id)//виведення сторінки з певним житлом
    {
            $home = House::findorFail($id);
            $comforts = Comfort::findorFail($id);
            return view('page.house-page', compact(['comforts', 'home']));
    }



    public function home_edit($id)
    {
        $home = House::findOrFail($id);
        $regions = Region::all();
        $homes_types = HomeType::all();
        $guests = Guest::all();
        $rooms = Room::all();
        $comforts = Comfort::all();
        $pays = Pay::all();
        $pledges = Pledge::all();
        $docs = Doc::all();

        return view("page.edit.home-edit", compact(['regions', 'comforts', 'pays', 'pledges', 'docs', 'rooms', 'guests', 'homes_types', 'home']));
    }

    public function home_edit_process($id, Request $request)
    {
        $home = House::find($id);

        $request->validate([
            "region_id" => ["required", "string"],
            "city_id" => ["required", "string"],
            "home_type_id"=>["required", "string"],
            "room_id"=>'required|integer|min:1|max:8',
            "guest_id"=>'required|integer|min:1|max:8',
            "street" => 'required|string|min:5|max:30',
            "home_number" => 'required|string|min:1|max:30',
            "total_area" => 'required|regex:/^(\d+(?:[\.\,]\d{2})?)$/|min:1|max:10000',
            "cost" => 'required|numeric|min:1|max:1000000',
            "comforts"=>'required|min:1',
            "conditions" => 'required|max:1500',
            "district" => 'required|string|min:3|max:30',
            "micro_district" => 'nullable|string|min:3|max:20',
            "settling" => ["required"],
            "eviction" => 'required',
            "pledge_id" => ["required", "string"],
            "doc_id" => ["required", "string"],
            "age" => 'required|integer|min:14|max:1000',
            "lease_term" => 'required|integer|min:1|max:1000',
            "pay_id" => ["required", "string"],
        ]);

        $home->update([
            
        ]);
        $home->region_id = $request->input('region_id');
        $home->city_id = $request->input('city_id');
        $home->home_type_id = $request->input('home_type_id');
        $home->room_id = $request->input('room_id');
        $home->guest_id = $request->input('guest_id');
        $home->street = $request->input('street');
        $home->home_number = $request->input('home_number');
        $home->total_area = $request->input('total_area');
        $home->cost = $request->input('cost');
        $home->conditions = $request->input('conditions');
        $home->district = $request->input('district');
        $home->micro_district = $request->input('micro_district');
        $home->settling = $request->input('settling');
        $home->eviction = $request->input('eviction');
        $home->pledge_id= $request->input('pledge_id');
        $home->doc_id = $request->input('doc_id');
        $home->age = $request->input('age');
        $home->lease_term = $request->input('lease_term');
        $home->pay_id = $request->input('pay_id');

        $home->save();
        
        $home->comforts()->detach();
        if ($request->input('comforts')) {
            $home->comforts()->attach($request->input('comforts'));
        }

        return redirect(route('house-page', $id));
    }

    public function house_delete($id)
    {
        $home = House::findOrFail($id);
        $files = HousesImage::where("house_id", $home->id)->get();
        foreach ($files as $file)
        {
            if (File::exists('photo/add/houses/'. $file->image)) {
                File::delete('photo/add/houses/'. $file->image);
            }
        }
        $home->delete();
        return back();
    }

    




}


