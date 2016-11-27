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
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'cpf' => $request->input('cpf'),
            'password' => $request->input('password')
        ];

        $address_data = [
            'street' => $request->input('street'),
            'number' => $request->input('number'),
            'cep' => $request->input('cep'),
            'complement' => $request->input('complement')
        ];

        if($this->validator($address_data)->fails()) {
            return Response::json($this->validator($address_data)->errors(), 400);
        
        }else if($this->validator($data)->fails()) {
            return Response::json($this->validator($data)->errors(), 400);
        }
        else{
            
            $address = new Address();
            $address->fill($address_data);
            $address->save();

            $denunciante = new Denunciante();
            $denunciante->fill($request->only(['name','email','cpf','password']));
            $denunciante->password = Hash::make($denunciante->password);
            $denunciante->address_id = $address->id;
            $denunciante->active = true;
            if($request->hasFile('photo')){
                $path = $request->photo->store('images');
                $denunciante->photo = $path;
            }
            $denunciante->save();

            return Response::json($denunciante, 200);
        }
    }

}
