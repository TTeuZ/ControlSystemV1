<?php

namespace App\Http\Controllers\API\CentralEquip;

use App\zModalCentralEquip\AllEquipamento;
use App\zModalAutoEletrica\EquipamentoAutoEletrica;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class AllEquipamentoController extends Controller
{
    public function index()
    {
        return response()->json(AllEquipamento::orderBy('name')->get());
    }

    public function disp_index()
    {
        $equipamento = AllEquipamento::all();

        $equipamento = $equipamento->filter(function ($item) use ($equipamento) {
            return $item->disponivel == true;
        })->values();

        $equipamento = $equipamento->filter(function ($item) use ($equipamento) {
            $auto_equip = EquipamentoAutoEletrica::all();
            $exist = false;
            foreach ($auto_equip as $key) {
                if ($key->nome == $item->name) {
                    $exist = true;
                break;
                };
            };
            return !$exist;
        })->values();

        return response()->json($equipamento);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:150',
            'tipo' => 'required|string|max:150'
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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'string|max:150',
            'tipo' =>  'string|max:150',
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if ($request->user()->id <= 5) {
            $equipamento = AllEquipamento::find($id);
            $equipamento->update($request->all());

            return response()->json($equipamento);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para atualizar equipamentos'
            ]);
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

    public function changeDisponibilidade($id)
    {
        $equipamento = AllEquipamento::find($id);
        $equipamento->disponivel = !$equipamento->disponivel;
        $equipamento->update();
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
