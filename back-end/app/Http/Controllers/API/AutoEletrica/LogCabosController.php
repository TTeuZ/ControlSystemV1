<?php

namespace App\Http\Controllers\API\AutoEletrica;

use App\zModalAutoEletrica\LogCabos;
use App\zModalAutoEletrica\AutoEletrica;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class LogCabosController extends Controller
{
    public function index()
    {
        return response()->json(LogCabos::all());
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
        $log_cabo = LogCabos::create($request->all());
        return response()->json($log_cabo);
    }

    public function destroy($id, Request $request)
    {
        if ($request->user()->id <= 5) {
            $cabo = LogCabos::find($id);
            $cabo->delete();
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para deletar equipamentos'
            ]);
        }
    }
}
