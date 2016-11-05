<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Address;


class Address extends Model
{

	protected $table = 'address';

	public $timestamps = false;
	
	protected $fillable = [
        'street', 'number', 'complement', 'cep'
    ];

    protected $hidden = [];

}