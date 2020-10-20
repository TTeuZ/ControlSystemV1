<?php

namespace App\Http\Controllers\API;

use App\Cabos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AutoEletrica;
use Validator;

class CabosController extends Controller
{
    public function index()
    {
        return response()->json(Cabos::all());
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
            $cabo = Cabos::create($request->all());
            return response()->json($cabo);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para criar cabos'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'string|max:150',
            'tipo' =>  'string|max:150',
            'quantidade' => 'integer'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if ($request->user()->id <= 5) {
            $cabo = Cabos::find($id);
            $request->request->add(['user_name_updated' => $request->user()->name]);
            $cabo->update($request->all());
            return response()->json($cabo);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para atualizar cabos'
            ]);
        }
    }

    public function changeSituation(Request $request, $id) {
        $cabo = Cabos::find($id);

        if ($request->user()->id <= 5) {
            $cabo->situacao = !$cabo->situacao;
            $cabo->user_name_updated = $request->user()->name;
            $cabo->update();
            return response()->json($cabo);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão'
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        if ($request->user()->id <= 5) {
            $cabo = Cabos::find($id);
            $cabo->delete();
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para deletar cabos'
            ]);
        }
    }
}
