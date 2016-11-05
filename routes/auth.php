<?php

Route::post('/signUp', 'Auth\RegisterController@createDenunciante');
Route::get('/teste', 'Auth\RegisterController@testeControllerConn');