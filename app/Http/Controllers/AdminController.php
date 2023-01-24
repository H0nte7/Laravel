<?php

namespace App\Http\Controllers;

use App\Transmission;
use App\Equipment;
use App\Fuel;
use App\Kit;
use App\AutoModel;
use App\AutosImage;
use App\Mark;
use App\Auto;
use App\City;
use App\Comfort;
use App\Doc;
use App\Guest;
use App\HomeType;
use App\House;
use App\HousesImage;
use App\Pay;
use App\Pledge;
use App\Region;
use App\Room;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AdminController extends Controller
{
    public function admin_home_search(Request $request)//пошук житла по id
    {
        $ahs = $request->ahs;
        $homes = House::all()->where('id', 'LIKE', "{$ahs}");

        return view("admin.admin-house", ['homes' => $homes]);
    }

    public function admin_car_search(Request $request)//пошук авто по id
    {
        $acs = $request->acs;
        $cars = Auto::all()->where('id', 'LIKE', "{$acs}");

        return view("admin.admin-car", ['cars' => $cars]);
    }
    public function admin_user_search(Request $request)//пошук користувачів за номером телефона
    {
        $aus = $request->aus;
        $users = User::all()->where('phone', 'LIKE', "{$aus}");

        return view("admin.admin-user", ['users' => $users]);
    }

    public function admin()//виведення шаблона з жилом в адмін-панелі
    {
        $homes = House::all();
        $cars = Auto::all();
        return view("admin.admin", ['homes' =>$homes], ['cars' =>$cars]);
    }

    public function admin_house()
    {
        return view("admin.admin-house", ['homes' =>House::all()]);
    }

    public function admin_car()//виведення шаблона з автом в адмін-панелі
    {
        return view("admin.admin-car", ['cars' =>Auto::all()]);
    }

    public function admin_user()//виведення шаблона з користувачами в адмін-панелі
    {
        return view("admin.admin-user", ['users' =>User::all()]);
    }

    public function moder_house($home_id, $admin_code)//модерація публікацій житло\авто
    {
        try {
            $house_moder = House::whereId($home_id)->update([
                'admin_status' => $admin_code
            ]);

            if($house_moder){
                return back()->with('success', 'Статус модерации публикации изменен.');
            }

            return back()->with('error', 'Статус модерации публикации не изменен.');
        } catch (\Throwable $th) {
            throw $th;
        }
        // redirect()->route('admin')
    }

    public function moder_auto($car_id, $admin_code)//модерація публікацій житло\авто
    {
        try {
            $auto_moder = Auto::whereId($car_id)->update([
                'admin_status' => $admin_code
            ]);

            if($auto_moder){
                return back()->with('success', 'Статус модерации публикации изменен.');
            }

            return back()->with('error', 'Статус модерации публикации не изменен.');
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function admin_page()//виведення шаблона адмін-панелі
    {
        return view("admin.admin");
    }

    public function getCity(Request $request)//отримання міста залежно від вибраної області
    {
        $data=City::select('name', 'id')->where('region_id', $request->id)->get();
        return response()->json($data);
    }


    public function admin_house_edit($id)
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

        return view("admin.edit.admin-house-edit", compact(['regions', 'comforts', 'pays', 'pledges', 'docs', 'rooms', 'guests', 'homes_types', 'home']));
    }

    public function admin_house_edit_process($id, Request $request)
    {
        $home = House::find($id);

        if ($home->imagesOrnot()){
            $request->validate(['images' => 'required' ]);
        }

        $request->validate([
            "region_id" => ["required", "string"],
            "city_id" => ["required", "string"],
            "home_type_id"=>["required", "string"],
            "images"=>'array|min:3|max:6|',
            "images.*"=>'mimes:jpeg,png,jpg',
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
            'cover'=>$home->cover,
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
                $imageResize->text('TOPARENDA.INFO', 180, 275, function($font)
                {
                    $font->file(public_path('fonts/Roboto/Roboto-Black.ttf'));
                    $font->size(24);
                    $font->color('#fdf6e3');
                })->save($path);
                $imageResize->save();

                if (File::exists($file))
                 {
                     File::delete($file);
                 }
                 
                HousesImage::create($request->all());
            }
        }
        
        $home->comforts()->detach();
        if ($request->input('comforts')) {
            $home->comforts()->attach($request->input('comforts'));
        }

        return redirect(route('house-page', $id));
    }

    public function admin_house_delete($id)
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

    public function admin_house_delete_all_images($id)
    {
        $home = House::findOrFail($id);
        $images = HousesImage::where("house_id", $home->id)->get();
    
        foreach($images as $image)
        {
            if (File::exists('photo/add/houses/'.$image->image)){
                File::delete('photo/add/houses/'.$image->image);
            }
        }
        
        HousesImage::where("house_id", $home->id)->delete();
        return back();
    }

    public function admin_house_delete_image($id)
     {
        $image = HousesImage::findOrFail($id);
        if (File::exists('photo/add/houses/'.$image->image))
        {
            File::delete('photo/add/houses/'.$image->image);
        }

        HousesImage::find($id)->delete();
         return back();
     }

     public function admin_car_edit($id)
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

        return view("admin.edit.admin-car-edit", compact(['regions', 'kits', 'pays', 'pledges', 'docs', 'marks', 'kits', 'transmissions', 'fuels', 'car']));
     }

     public function getModel(Request $request)//отримання моделі залежно від вибраної марки
    {
        $data=AutoModel::select('name', 'id')->where('mark_id', $request->id)->get();
        return response()->json($data);
    }

     public function admin_car_edit_process($id, Request $request)
     {
        $car = Auto::find($id);


        $request->validate([
            "region_id" => ["required", "string"],
            "city_id" => ["required", "string"],
            "images"=>'array|min:3|max:6|',
            "images.*"=>'mimes:jpeg,png,jpg',
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

        if ($car->imagesOrnot()){
            $request->validate(['images' => 'required' ]);
        }

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

        if($request->hasFile('images'))//додавання водяного знаку на додоткові фото
        {
            $files=$request->file('images');
            foreach ($files as $file)
            {
                $imageName=time().'_'.$file->getClientOriginalName();
                $request['auto_id']=$car->id;
                $request['image']=$imageName;
                $path= public_path("/photo/add/autos/". $imageName);
                $imageResize = Image ::make($file->getRealPath());
                $imageResize->resize(400,300);
                $imageResize->text('TOPARENDA.INFO', 180, 275, function($font)
                {
                    $font->file(public_path('fonts/Roboto/Roboto-Black.ttf'));
                    $font->size(24);
                    $font->color('#fdf6e3');
                })->save($path);
                $imageResize->save();

                if (File::exists($file))
                 {
                     File::delete($file);
                 }
                 
                AutosImage::create($request->all());
            }
        }
        
        $car->kits()->detach();
        if ($request->input('kits')) {
            $car->kits()->attach($request->input('kits'));
        }

        return redirect(route('car-page', $id));
     }

     public function admin_car_delete($id)
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

    public function admin_car_delete_all_images($id)
    {
        $car = Auto::findOrFail($id);
        $images = AutosImage::where("auto_id", $car->id)->get();
    
        foreach($images as $image)
        {
            if (File::exists('photo/add/autos/'.$image->image)){
                File::delete('photo/add/autos/'.$image->image);
            }
        }
        
        AutosImage::where("auto_id", $car->id)->delete();
        return back();
    }

    public function admin_car_delete_image($id)
     {
        $image = AutosImage::findOrFail($id);
        if (File::exists('photo/add/autos/'.$image->image))
        {
            File::delete('photo/add/autos/'.$image->image);
        }

        AutosImage::find($id)->delete();
         return back();
     }

     public function admin_user_ban($user_id, $ban_code)
     {
        try {
            $user_ban = User::whereId($user_id)->update([
                'ban' => $ban_code
            ]);

            if($user_ban){
                return redirect()->route('admin-user')->with('Выполнено', 'Пользователь был забанен.');
            }

            return redirect()->route('admin-user')->with('Ошибка', 'Пользователь  не был забанен.');
        } catch (\Throwable $th) {
            throw $th;
        }
     }



}
