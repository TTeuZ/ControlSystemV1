<?php

namespace App\Http\Controllers\API;

use App\EquipamentoAutoEletrica;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AutoEletrica;
use Validator;

class EquipamentosAutoEletricaController extends Controller
{
    public function index()
    {
        return response()->json(EquipamentoAutoEletrica::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'required|string|max:150',
            'tipo' =>  'required|string|max:150',
            'situacao' => 'boolean',
            'auto_eletrica_id' => 'required|integer'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors());

        if (!AutoEletrica::find($request->auto_eletrica_id))
            return response()->json('Auto Eletrica nao existe');

        $request->request->add(['user_name_created' => $request->user()->name]);
        $equip_auto = EquipamentoAutoEletrica::create($request->all());
        return response()->json($equip_auto);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'string|max:150',
            'tipo' =>  'string|max:150',
        ]);
        if ($validator->fails())
            return response()->json($validator->errors());

        $equipamento = EquipamentoAutoEletrica::find($id);
        $request->request->add(['user_name_updated' => $request->user()->name]);
        $equipamento->update($request->all());
        return response()->json($equipamento);
    }

    public function changeSituation($id) {
        $equipamento = EquipamentoAutoEletrica::find($id);

        $equipamento->situacao = !$equipamento->situacao;

        $equipamento->update();
        return response()->json($equipamento);
    }

    public function destroy($id)
    {
        $equipamento = EquipamentoAutoEletrica::find($id);
        $equipamento->delete();
    }
}
