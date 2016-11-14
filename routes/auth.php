<?php

Route::post('/signUp', 'Auth\RegisterController@createDenunciante');
Route::post('/login', 'Auth\LoginController@login');