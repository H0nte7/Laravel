<?php

namespace App\Http\Controllers;

use App\FavoritHouse;
use App\Auto;
use App\House;
use App\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\Console\Input\Input;

class AuthController extends Controller
{

    //вивеення шаблонів документації додатку
    public function regulations()
    {
        return view("document.regulations");
    }

    public function security_policy()
    {
        return view("document.security-policy");
    }

    public function cookie_rules()
    {
        return view("document.cookie-rules");
    }

    public function publishing_rules()
    {
        return view("document.publishing-rules");
    }

    public function public_offer()
    {
        return view("document.public-offer");
    }
    //


    public function showLoginForm()//виведення шаблона авторизації
    {
        return view("auth.login");
    }

    public function login(Request $request)//авторизація
    {
        $data = $request->validate([
            "phone" => ["required", "string",],
            "password" => ["required"],

        ]);

        $remember = $request->has('remember') ? true : false;

        $check = $request->only('phone', 'password');

        if(Auth::attempt($check, $remember))
        {
            return redirect(route("office"));
        }

        if(auth("web")->attempt($data)){
            return redirect(route("office"));
        }

        return redirect(route("login"))->withErrors(["password" => "Пользователь не найден, или данные введены неправильно"]);

    }


    //виведення шаблонів кабінету користувача
    public function office()
    {
        return view("office.office");
    }

    public function contact_info()
    {
        return view("office.office-contact-info");
    }

    public function my_publication($id)
    {

        $user = User::findorFail($id);

        return view("office.office-my-publication", ['homes' => $user->homes, 'cars'=> $user->cars, 'user'=>$user]);
    }

   


    public function logout()//вихід з акаунту
    {
        auth("web")->logout();

        return redirect(route("home"));
    }

    public function showRegisterForm()//виведення шаблона реєстрації
    {
        return view("auth.register");
    }

    public function register(Request $request)//реєстрація
    {
        $data = $request->validate([
            "name" => ["required", "string", "min:3", "max:35"],
            "phone" => ["required", "string", "unique:users,phone", "unique:users,second_phone"],
            "password" => ["required", "confirmed", "min:5"],
            "agreement" =>["required", "string"]
        ]);

        $user = User::create([
            "name" => $data["name"],
            "phone" => $data["phone"],
            "password" => bcrypt($data["password"]),
        ]);

        if($user) {
            auth("web")->login($user);
        }

        return redirect(route("home"));
    }

    public function showEditForm()//виведення шаблона редагування контакнтої інформації
    {
        return view("auth.edit");
    }

    public function edit(Request $request, $id)//редагування контактної інформації
    {
        $this->validate($request, array(
                "second_phone" => ["unique:users,phone,$id", "unique:users,second_phone,$id"],
                "email" => "unique:users,email,$id",
        ));

        $user = User::findOrFail($id);

        
        $user->second_phone = $request->second_phone;
        $user->email = $request->email;

        $user->save();

        if($user) {
            auth("web")->login($user);
        }

        return redirect(route("contact-info"));

    }

    public function showDeleteForm(Request $request)//виведення шаблона видалення акаунта
    {
        return view("auth.delete");
    }

    public function delete(Request $request)//видалення акаунта
    {
        $user_id = Auth::user()->id;
        User::destroy($user_id);


        return redirect(route("home"));
    }

    public function status_house($home_id, $status_code)
    {
    
        try {
            $publication_status = House::whereId($home_id)->update([
                'user_status' => $status_code
            ]);

            if($publication_status){

                return back()->with('Выполнено', 'Статус публикации изменен.');
               
            }

            return back()->with('Ошибка', 'Статус публикации не был изменен.');
        } catch (\Throwable $th) {
            throw $th;
        }
         
    }

    public function status_auto($car_id, $status_code)
    {
        try {
            $publication_status = Auto::whereId($car_id)->update([
                'user_status' => $status_code
            ]);

            if($publication_status){

                return back()->with('Выполнено', 'Статус публикации изменен.');
                
            }

            return back()->with('Ошибка', 'Статус публикации не был изменен.');
        } catch (\Throwable $th) {
            throw $th;
        }

    }






}
