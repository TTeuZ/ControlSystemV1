<?php

namespace App\Http\Controllers\API\AutoEletrica;

use App\zModalAutoEletrica\LogEquipamentoAutoEletrica;
use App\zModalAutoEletrica\AutoEletrica;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class LogEquipamentoAutoEletricaController extends Controller
{
    public function index()
    {
        return response()->json(LogEquipamentoAutoEletrica::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'required|string|max:150',
            'tipo' =>  'required|string|max:150',
            'auto_eletrica_id' => 'required|integer',
            'acao' => 'required|string|max:100'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if (!AutoEletrica::find($request->auto_eletrica_id))
            return response()->json('Auto Eletrica nao existe');

        $request->request->add(['user_name' => $request->user()->name]);
        $log_equip_auto = LogEquipamentoAutoEletrica::create($request->all());
        return response()->json($log_equip_auto);
    }

    public function destroy($id, Request $request)
    {
        if ($request->user()->id <= 5) {
            $equipamento = LogEquipamentoAutoEletrica::find($id);
            $equipamento->delete();
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para deletar equipamentos'
            ]);
        }
    }
}
