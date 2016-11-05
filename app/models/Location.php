<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Location;


class Location extends Model
{

	protected $table = 'location';

	public $timestamps = false;

	protected $fillable = [
        'street', 'number', 'complement', 'cep'
    ];

    protected $hidden = [];

}