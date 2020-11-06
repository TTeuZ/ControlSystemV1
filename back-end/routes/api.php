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

// Rota de login
Route::post('login', 'API\AuthController@login')->name('login');

Route::middleware(['auth:api'])->group(function () {
    // Rotas de Usuario
    Route::post('registro', 'API\AuthController@registro')->name('registro');
    Route::post('deleta', 'API\AuthController@destroy');
    Route::get('usuarios', 'API\AuthController@show');
    Route::post('atualiza', 'API\AuthController@atualizar');
    Route::get('meu-perfil', 'API\AuthController@meuPerfil')->name('perfil');

    // Rotas de Manutenção
    Route::apiResource('status', 'API\Manutencao\StatusController');
    Route::apiResource('statusEnum', 'API\Manutencao\StatusEnumController');
    Route::apiResource('equipamento', 'API\Manutencao\EquipamentosController');

    // Rotas de Estoque
    Route::apiResource('estoque', 'API\Estoque\EstoqueController');
    Route::get('atthis', 'API\Estoque\EstoqueController@attHis');

    // Rotas de Auto Elétrica
    Route::apiResource('autoeletrica', 'API\AutoEletrica\AutoEletricaController');
    Route::apiResource('cabos', 'API\AutoEletrica\CabosController');
    Route::post('cabo_change/{id}', 'API\AutoEletrica\CabosController@changeSituation');
    Route::apiResource('responsaveis', 'API\AutoEletrica\ResponsavelController');
    Route::apiResource('equip_auto', 'API\AutoEletrica\EquipamentosAutoEletricaController');
    Route::post('equip_change/{id}', 'API\AutoEletrica\EquipamentosAutoEletricaController@changeSituation');
});
