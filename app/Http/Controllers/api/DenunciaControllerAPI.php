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
use DB;


class DenunciaControllerAPI extends Controller
{


	protected function validator($data)
    {   
        if(array_key_exists('type', $data)){
            return Validator::make($data, [
                'type'      			=> 'required',
                'epidem_vigilance'      => 'required|boolean',
                'diagnosticated_cases'	=> 'required|boolean'
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
        if($request->input('epidem_vigilance') === "true"){

        }


    	$data = [
            'type' => $request->input('type'),
            'epidem_vigilance' => filter_var($request->input('epidem_vigilance'), FILTER_VALIDATE_BOOLEAN),
            'diagnosticated_cases' => filter_var($request->input('diagnosticated_cases'), FILTER_VALIDATE_BOOLEAN)
        ];

        $location_data = [
            'street' => $request->input('street'),
            'number' => $request->input('number'),
            'cep' => $request->input('cep'),
            'complement' => $request->input('complement')
        ];

        if($this->validator($location_data)->fails()) {
            return Response::json($this->validator($location_data)->errors(), 400);
        
        }else if($this->validator($data)->fails()){
            return Response::json($this->validator($data)->errors(), 400);
        }
        else{
            
            $location = new Location();
            $location->fill($location_data);
            $location->save();

            $denuncia = new Denuncia();
            $denuncia->type = $request->input('type');
            $denuncia->epidem_vigilance = $data['epidem_vigilance'];
            $denuncia->diagnosticated_cases = $data['diagnosticated_cases'];
            $denuncia->location_id = $location->id;
            $denuncia->denunciante_id = $request->user()->id;
            $denuncia->status = "open";
            
            if($request->hasFile('photo')){
                $path = $request->photo->store('images');
                $denuncia->photo = $path;
            }
            $denuncia->save();

            return Response::json($denuncia, 200);
        }
    }

    public function updateDenunciaStatus(Request $request, $denuncia_id){

        $denuncia = new Denuncia();
        $denuncia = $denuncia->searchDenuncia($denuncia_id);
        if($denuncia->status === "resolved"){
            return Response::json('Not modified', 304);
        }
        DB::table('denuncias')->where('denuncia_id', $denuncia_id)->update(['status' => "resolved"]);
        
        return Response::json($denuncia, 200);

    }

    public function getAllDenuncias(){
        $denuncias = new Denuncia();
        $denuncias = $denuncias->getAllDenuncias();

        return Response::json($denuncias, 200);
    }

}