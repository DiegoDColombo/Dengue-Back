<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Denuncia;

class Denuncia extends Model
{

	protected $fillable = [
        'type', 'status', 'photo', 'epidem_vigilance', 'diagnosticated_cases'
    ];

    protected $hidden = ['denunciante_id', 'location_id', 'created_at', 'updated_at'];

    public function showAlso($fields)
	{
		$hiddens = $this->getHidden();
		$hiddens = array_diff($hiddens, $fields);
		$this->setHidden($hiddens);
	}

	public function searchDenuncia($id){
		$denuncia = Denuncia::find($id);
		return $denuncia;
	}
}
