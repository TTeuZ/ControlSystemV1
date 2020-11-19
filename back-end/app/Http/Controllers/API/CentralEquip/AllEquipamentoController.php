<?php

namespace App\Http\Controllers\API\CentralEquip;

use App\zModalCentralEquip\AllEquipamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class AllEquipamentoController extends Controller
{
    public function index()
    {
        return response()->json(AllEquipamento::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:15',
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if ($request->user()->id <= 5) {
            $request->request->add(['user_name' => $request->user()->name]);
            $request->request->add(['situacao' => 'Adicionado no sistema']);
            $equipamento = AllEquipamento::create($request->all());
            return response()->json($equipamento);
        }
    }

    public function changeSituation(Request $request, $id)
    {
        $equipamento = AllEquipamento::find($id);
        $validator = Validator::make($request->all(),[
            'situacao' => 'string|max:150',
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);
        $request->request->add(['user_name' => $request->user()->name]);
        $equipamento->update($request->all());
        return response()->json($equipamento);
    }

    public function destroy($id, Request $request)
    {
        if ($request->user()->id <= 5) {
            $equipamento = allEquipamento::find($id);
            $equipamento->delete();
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para deletar equipamentos'
            ]);
        }
    }
}
