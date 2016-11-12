<?php

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\models\Denunciante;
use App\models\Denuncia;
use App\models\Address;
use App\models\location;
use Response;


class DenunciaControllerAPI extends Controller
{


	protected function validator($data)
    {   
        if(array_key_exists('type', $data)){
            return Validator::make($data, [
                'type'      			=> 'required',
                'status'                => 'required',
                'epidem_vigilance'      => 'boolean',
                'diagnosticated_cases'	=> 'boolean'
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

    public function createDenuncia(Request $request)
    {   
    	//Implement photo
        if($this->validator($request->input('location_data'))->fails()){
            return Response::json($this->validator($request->input('location_data'))->errors(), 404);
        
        }else if($this->validator($request->input('denuncia'))->fails()){
            return Response::json($this->validator($request->input('denuncia'))->errors(), 404);
        }
        else{
            
            $location = new Location();
            $location->fill($request->input('location_data'));
            $location->save();

            $denuncia = new Denuncia();
            $denuncia->fill($request->input('denuncia'));
            $denuncia->location_id = $location->id;
            //$denuncia->denunciante_id = $request->user()->id;
            $denuncia->denunciante_id = 1;
            if($request->hasFile('photo')){
                $file = new UploadedFile();
                $file = $request->file('photo');
                $path = $file->store('images');
                $denuncia->photo = $path;
            }
            $denuncia->save();

            return Response::json($denuncia, 200);
        }
    }




}