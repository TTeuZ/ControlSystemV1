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
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if (!AutoEletrica::find($request->auto_eletrica_id))
            return response()->json('Auto Eletrica nao existe');

        if ($request->user()->id <= 5) {
            $request->request->add(['user_name_created' => $request->user()->name]);
            $equip_auto = EquipamentoAutoEletrica::create($request->all());
            return response()->json($equip_auto);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para adicionar equipamentos'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'string|max:150',
            'tipo' =>  'string|max:150',
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if ($request->user()->id <= 5) {
            $request->request->add(['user_name_updated' => $request->user()->name]);
            $equipamento = EquipamentoAutoEletrica::find($id);
            $equipamento->update($request->all());
            return response()->json($equipamento);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para atualizar equipamentos'
            ]);
        }
    }

    public function changeSituation(Request $request, $id) {
        $equipamento = EquipamentoAutoEletrica::find($id);

        if ($request->user()->id <= 5) {
            $equipamento->situacao = !$equipamento->situacao;
            $equipamento->user_name_updated = $request->user()->name;
            $equipamento->update();
            return response()->json($equipamento);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão'
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        if ($request->user()->id <= 5) {
            $equipamento = EquipamentoAutoEletrica::find($id);
            $equipamento->delete();
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para deletar equipamentos'
            ]);
        }
    }
}
