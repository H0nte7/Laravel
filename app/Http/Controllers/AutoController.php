<?php

namespace App\Http\Controllers;

use App\Auto;
use App\AutoModel;
use App\AutosImage;
use App\City;
use App\Doc;
use App\Equipment;
use App\Fuel;
use App\Kit;
use App\Mark;
use App\Pay;
use App\Pledge;
use App\Region;
use App\Transmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;


class AutoController extends Controller
{

    public function showPublication_carForm()//виведення шаблона публікації авто
    {
        $regions=Region::all();
        $marks=Mark::all();
        $pays = Pay::all();
        $pledges = Pledge::all();
        $docs = Doc::all();
        $fuels = Fuel::all();
        $transmissions = Transmission::all();
        $kits = Kit::all();

        return view("publication.publication-car", compact(['regions', 'marks', 'pays', 'pledges', 'docs', 'fuels', 'transmissions', 'kits']));
    }

    public function getCity(Request $request)//отримання міста залежно від вибраної області
    {
        $data=City::select('name', 'id')->where('region_id', $request->id)->get();
        return response()->json($data);
    }

    public function getModel(Request $request)//отримання моделі залежно від вибраної марки
    {
        $data=AutoModel::select('name', 'id')->where('mark_id', $request->id)->get();
        return response()->json($data);
    }

    public function publication_car(Request $request)//публікація авто
    {

        $request->validate([
            "region_id" => ["required", "string"],
            "city_id" => ["required", "string"],
            "images"=>'required|array|min:3|max:6|',
            "mark_id"=>["required", "string"],
            "auto_model_id"=>["required", "string"],
            "transmission_id"=>["required", "string"],
            "fuel_id"=>["required", "string"],
            "cost" => 'required|numeric|min:1|max:1000000',
            "kits"=>'required|array|min:1',
            "conditions" => 'required|max:1500',
            "delivery" => ["required", "date_format:H:i"],
            "return" => ["required", "date_format:H:i"],
            "pledge_id" => ["required", "string"],
            "doc_id" => ["required", "string"],
            "age" => 'required|integer|min:18|max:1000',
            "lease_term" => 'required|integer|min:1|max:1000',
            "pay_id" => ["required", "string"],
        ]);

        $request->old('region_id');
        $request->old('city_id');
        $request->old('mark_id');
        $request->old('auto_model_id');
        $request->old('transmission_id');
        $request->old('fuel_id');
        $request->old('cost');
        $request->old('kits');
        $request->old('conditions');
        $request->old('delivery');
        $request->old('return');
        $request->old('pledge_id');
        $request->old('doc_id');
        $request->old('age');
        $request->old('lease_term');
        $request->old('pay_id');



       


        $car = new Auto();
        $car->user_id = Auth::user()->id;
        $car->region_id = $request->input('region_id');
        $car->city_id = $request->input('city_id');
        $car->mark_id = $request->input('mark_id');
        $car->auto_model_id = $request->input('auto_model_id');
        $car->transmission_id = $request->input('transmission_id');
        $car->fuel_id = $request->input('fuel_id');
        $car->cost = $request->input('cost');
        $car->conditions = $request->input('conditions');
        $car->delivery = $request->input('delivery');
        $car->return = $request->input('return');
        $car->pledge_id = $request->input('pledge_id');
        $car->doc_id = $request->input('doc_id');
        $car->age = $request->input('age');
        $car->lease_term = $request->input('lease_term');
        $car->pay_id = $request->input('pay_id');

        $car->save();

        if ($request->input('kits')) {
            $car->kits()->attach($request->input('kits'));
        }

        if($request->hasFile('images'))//накладання водяного знаку на додаткові фото
        {
            $files=$request->file('images');
            foreach ($files as $file)
            {
                $imageName=time().'_'.$file->getClientOriginalName();
                $request['auto_id']=$car->id;
                $request['image']=$imageName;
                $path= public_path('/photo/add/autos/'. $imageName);
                $imageResize = Image ::make($file->getRealPath());
                $imageResize->resize(400,300);
                $imageResize->text('Hello World', 180, 275, function($font)
                {
                    $font->file(public_path('fonts/Roboto/Roboto-Black.ttf'));
                    $font->size(24);
                    $font->color('#fdf6e3');
                })->save($path);
                $imageResize->save();
                AutosImage::create($request->all());
            }

        }
        return redirect(route("office"));
    }

    public function show_car_page($id)//виведення сторінки з певним авто
    {
        $car = Auto::findorFail($id);
        $kits = Kit::findorFail($id);
        return view('page.car-page', compact(['kits', 'car']));
    }

    public function car_edit($id)
    {
        $car = Auto::findOrFail($id);
        $regions = Region::all();
        $marks=Mark::all();
        $kits = Kit::all();
        $transmissions = Transmission::all();
        $fuels = Fuel::all();
        $pays = Pay::all();
        $pledges = Pledge::all();
        $docs = Doc::all();

        return view("page.edit.car-edit", compact(['regions', 'kits', 'pays', 'pledges', 'docs', 'marks', 'kits', 'transmissions', 'fuels', 'car']));
    }

    public function car_edit_process($id, Request $request)
    {
        $car = Auto::find($id);


        $request->validate([
            "region_id" => ["required", "string"],
            "city_id" => ["required", "string"],
            "mark_id"=>["required", "string"],
            "auto_model_id"=>["required", "string"],
            "transmission_id"=>["required", "string"],
            "fuel_id"=>["required", "string"],
            "cost" => 'required|numeric|min:1|max:1000000',
            "kits"=>'required|array|min:1',
            "conditions" => 'required|max:1500',
            "delivery" => ["required"],
            "return" => ["required"],
            "pledge_id" => ["required", "string"],
            "doc_id" => ["required", "string"],
            "age" => 'required|integer|min:18|max:1000',
            "lease_term" => 'required|integer|min:1|max:1000',
            "pay_id" => ["required", "string"],
        ]);

        $car->update([
        ]);
        $car->region_id = $request->input('region_id');
        $car->city_id = $request->input('city_id');
        $car->mark_id = $request->input('mark_id');
        $car->auto_model_id = $request->input('auto_model_id');
        $car->transmission_id = $request->input('transmission_id');
        $car->fuel_id = $request->input('fuel_id');
        $car->cost = $request->input('cost');
        $car->conditions = $request->input('conditions');
        $car->delivery = $request->input('delivery');
        $car->return = $request->input('return');
        $car->pledge_id= $request->input('pledge_id');
        $car->doc_id = $request->input('doc_id');
        $car->age = $request->input('age');
        $car->lease_term = $request->input('lease_term');
        $car->pay_id = $request->input('pay_id');

        $car->save();
        
        $car->kits()->detach();
        if ($request->input('kits')) {
            $car->kits()->attach($request->input('kits'));
        }

        return redirect(route('car-page', $id));
    }

    public function car_delete($id)
    {
        $car = Auto::findOrFail($id);
        $files = AutosImage::where("auto_id", $car->id)->get();
        foreach ($files as $file)
        {
            if (File::exists('photo/add/autos/'. $file->image)) {
                File::delete('photo/add/autos/'. $file->image);
            }
        }
        $car->delete();
        return back();
    }





}
