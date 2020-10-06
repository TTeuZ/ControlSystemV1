<?php

namespace App\Http\Controllers\API;

use App\AutoEletrica;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cabos;
use App\Responsavel;
use App\EquipamentoAutoEletrica;
use Validator;

class AutoEletricaController extends Controller
{
    public function index()
    {
        $data= [];
        foreach(AutoEletrica::all() as $autoEletrica){
            array_push($data, [$autoEletrica, EquipamentoAutoEletrica::all()->where('auto_eletrica_id', $autoEletrica->id), Cabos::all()->where('auto_eletrica_id', $autoEletrica->id)]);
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'required|string|max:15',
            'endereco' => 'required|string|max:50',
        ]);
        if ($validator->fails())
            return response()->json($validator->errors());

        $request->request->add(['user_name_created' => $request->user()->name]);
        $autoEletrica = AutoEletrica::create($request->all());
        return response()->json($autoEletrica);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'string|max:15',
            'endereco' => 'string|max:50'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors());

        $autoEletrica = AutoEletrica::find($id);
        $autoEletrica->update($request->all());
        $autoEletrica->save();
        return response()->json($autoEletrica);
    }

    public function destroy($id)
    {
        $autoEletrica = AutoEletrica::find($id);
        $autoEletrica->delete();
    }
}
