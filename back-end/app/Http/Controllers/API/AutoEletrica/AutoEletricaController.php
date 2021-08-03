<?php

namespace App\Http\Controllers\API\AutoEletrica;

use App\zModalAutoEletrica\AutoEletrica;
use App\zModalAutoEletrica\Cabos;
use App\zModalAutoEletrica\Responsavel;
use App\zModalAutoEletrica\EquipamentoAutoEletrica;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class AutoEletricaController extends Controller
{
    public function index()
    {
        $data= [];
        foreach(AutoEletrica::all() as $autoEletrica){
            array_push($data, [$autoEletrica, EquipamentoAutoEletrica::all()->where('auto_eletrica_id', $autoEletrica->id)->values(), 
            Cabos::all()->where('auto_eletrica_id', $autoEletrica->id)->values(), 
            Responsavel::all()->where('auto_eletrica_id', $autoEletrica->id)->values()]);
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'required|string|max:150',
            'endereco' => 'required|string|max:150',
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if ($request->user()->id <= 5) {
            $request->request->add(['user_name_created' => $request->user()->name]);
            $autoEletrica = AutoEletrica::create($request->all());
            return response()->json($autoEletrica);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para criar auto elétricas'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'string|max:150',
            'endereco' => 'string|max:150'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if ($request->user()->id <= 5) {
            $autoEletrica = AutoEletrica::find($id);
            $autoEletrica->update($request->all());
            $autoEletrica->save();
            return response()->json($autoEletrica);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para atualizar auto elétricas'
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        if ($request->user()->id <= 5) {
            $autoEletrica = AutoEletrica::find($id);
            $autoEletrica->delete();
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para deletar auto elétricas'
            ]);
        }
    }
}
