<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \Firebase\JWT\JWT;
use App\models\Denunciante;
use Config;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'denunciantes';
    public $timestamp = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email', 'password', 'access_token', 'remember_token', 'active'
    ];

    public function jwtProvider(){

        $payload = [
            'iss' => Config::get('app.url'),
            'iat' => time(),
            'exp' => time() + Config::get('auth.expirationTime'),
            'aud' => $this->id
        ];

        $jwt = JWT::encode($payload, Config::get('auth.secret'));
        return $jwt;

    }
}
