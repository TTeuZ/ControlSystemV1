<?php

namespace App\Http\Controllers\API\AutoEletrica;

use App\zModalAutoEletrica\EquipamentoAutoEletrica;
use App\zModalAutoEletrica\LogEquipamentoAutoEletrica;
use App\zModalAutoEletrica\AutoEletrica;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

            //Criação do item no log
            $log_equip = [
                'nome' => $request->nome,
                'tipo' => $request->tipo,
                'auto_eletrica_id' => $request->auto_eletrica_id,
                'user_name' => $request->user_name_created,
                'acao' => 'Criado'
            ];
            LogEquipamentoAutoEletrica::create($log_equip);

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

            //Criação do item no log
            $log_equip = [
                'nome' => $request->nome,
                'tipo' => $request->tipo,
                'auto_eletrica_id' => $equipamento->auto_eletrica_id,
                'user_name' => $request->user_name_updated,
                'acao' => 'Atualizado'
            ];
            LogEquipamentoAutoEletrica::create($log_equip);

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

            //Criação do item no log
            if ($equipamento->situacao == 1) {
                $log_equip = [
                    'nome' => $equipamento->nome,
                    'tipo' => $equipamento->tipo,
                    'auto_eletrica_id' => $equipamento->auto_eletrica_id,
                    'user_name' => $equipamento->user_name_updated,
                    'acao' => 'Para defeito'
                ];
            } else {
                $log_equip = [
                    'nome' => $equipamento->nome,
                    'tipo' => $equipamento->tipo,
                    'auto_eletrica_id' => $equipamento->auto_eletrica_id,
                    'user_name' => $equipamento->user_name_updated,
                    'acao' => 'Para estoque'
                ];
            }
            LogEquipamentoAutoEletrica::create($log_equip);

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

            //Criação do item no log
            $log_equip = [
                'nome' => $equipamento->nome,
                'tipo' => $equipamento->tipo,
                'auto_eletrica_id' => $equipamento->auto_eletrica_id,
                'user_name' => $equipamento->user_name_created,
                'acao' => 'Retirado'
            ];
            LogEquipamentoAutoEletrica::create($log_equip);

            $equipamento->delete();
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para deletar equipamentos'
            ]);
        }
    }
}
