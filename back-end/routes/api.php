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


Route::post('login', 'AuthController@login')->name('login');
Route::post('registro', 'AuthController@registro')->name('registro');

Route::get('arquivos/{arquivo}', 'ArquivoController@show');

Route::apiResource('status', 'API\StatusController');
Route::apiResource('equipamento', 'API\EquipamentosController');


Route::middleware(['auth:api'])->group(function () {

    Route::get('meu-perfil', 'AuthController@meuPerfil')->name('perfil');

    Route::apiResource('arquivos', 'ArquivoController')->except([
        'show'
    ]);

});

Route::get('teste', function(){
    return 'abc';
});

