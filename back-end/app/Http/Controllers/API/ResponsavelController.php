<?php

namespace App\Http\Controllers\API;

use App\Responsavel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AutoEletrica;
use Validator;

class ResponsavelController extends Controller
{
    public function index()
    {
        return response()->json(Responsavel::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'required|string|max:150',
            'telefone' =>  'required|string|max:20',
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
            $responsavel = Responsavel::create($request->all());
            return response()->json($responsavel);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para adicionar responsáveis'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'string|max:150',
            'telefone' =>  'string|max:20',
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if ($request->user()->id <= 5) {
            $responsavel = Responsavel::find($id);
            $request->request->add(['user_name_updated' => $request->user()->name]);
            $responsavel->update($request->all());
            return response()->json($responsavel);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para atualizar responsáveis'
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        if ($request->user()->id <= 5) {
            $responsavel = Responsavel::find($id);
            $responsavel->delete();
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para deletar responsáveis'
            ]);
        }
    }
}
