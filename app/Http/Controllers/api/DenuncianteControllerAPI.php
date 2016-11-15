<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Denunciante;
use App\models\Denuncia;
use Response;

class DenuncianteControllerAPI extends Controller
{

	public function getDenuncias(Request $request){

		$denunciante = Denunciante::find($request->user()->id);
		$denuncias = $denunciante->getDenuncias();
		
		if(empty($denuncias)){
			return Response::json('Not found', 404);
		}

		return Response::json($denuncias, 200);
	}
}