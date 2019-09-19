<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Pour une connection, passer par la methode login du PassportController
Route::post('/login', 'PassportController@login');

// Pour une inscription, passer par la methode register du PassportController
Route::post('/register', 'PassportController@register');

// Une fois que l'utilisateur est authenifié alors il peut accéder aux détails
Route::middleware('auth:api')->get('/user', function (Request $request) {

    // Envoyer les détails sur l'utilisateur
    Route::get('/details','PassportController@details');
   // return $request->user();
});
