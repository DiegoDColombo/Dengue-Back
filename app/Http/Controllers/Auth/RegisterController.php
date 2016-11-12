<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\models\Denunciante;
use App\models\Address;
use Response;
use Hash;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator($data)
    {   
        if(array_key_exists('email', $data)){
            return Validator::make($data, [
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|max:255|unique:denunciantes',
                'password'  => 'required|min:6|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])([a-zA-Z0-9]{6,})$/',
                'cpf'       => 'required|min:11|unique:denunciantes|regex:/^[0-9]+$/'
                ]);
        }
        else{
            return Validator::make($data, [
                'street'    => 'required',
                'number'    => 'required|numeric',
                'cep'       => 'required|numeric' 
                ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     * Check how will photo be updated and stored
     * @param  array  $data
     * @return User
     */
    public function createDenunciante(Request $request)
    {   

        if($this->validator($request->input('address_data'))->fails()){
            return Response::json($this->validator($request->input('address_data'))->errors(), 404);
        
        }else if($this->validator($request->input('data'))->fails()){
            return Response::json($this->validator($request->input('data'))->errors(), 404);
        }
        else{
            
            $address = new Address();
            $address->fill($request->input('address_data'));
            $address->save();

            $denunciante = new Denunciante();
            $denunciante->fill($request->input('data'));
            $denunciante->password = Hash::make($denunciante->password);
            $denunciante->address_id = $address->id;
            $denunciante->active = true;
            if($request->hasFile('photo')){
                $file = new UploadedFile();
                $file = $request->file('photo');
                $path = $file->store('images');
                $denunciante->photo = $path;
            }
            $denunciante->save();

            return Response::json($denunciante, 200);
        }
    }

}
