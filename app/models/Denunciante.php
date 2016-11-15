<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Validator;
use App\models\Denunciante;

class Denunciante extends Model
{

	public $timestamps = false; //Indicates that Denunciante Table has no created and updated_at columns

	protected $fillable = [
        'name', 'email', 'photo', 'cpf', 'password', 'nickname'
    ];

    protected $hidden = ['access_token','remember_token', 'active', 'password'];

    public function showAlso($fields)
	{
		$hiddens = $this->getHidden();
		$hiddens = array_diff($hiddens, $fields);
		$this->setHidden($hiddens);
	}

	public function searchDenunciante($id){
		$denunciante = Denunciante::find($id);
		return $denunciante;
	}

	public function getDenuncias(){
		return $this->hasMany('App\models\Denuncia')->get();
	}
	
}