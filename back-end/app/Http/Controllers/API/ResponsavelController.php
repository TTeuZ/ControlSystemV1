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
            'nome' => 'required|string|max:15',
            'telefone' =>  'required|string|max:20',
            'auto_eletrica_id' => 'required|integer'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors());

        if (!AutoEletrica::find($request->auto_eletrica_id))
            return response()->json('Auto Eletrica nao existe');

        $request->request->add(['user_name_created' => $request->user()->name]);
        $responsavel = Responsavel::create($request->all());
        return response()->json($responsavel);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'string|max:15',
            'telefone' =>  'string|max:20',
        ]);
        if ($validator->fails())
            return response()->json($validator->errors());

        $responsavel = Responsavel::find($id);
        $request->request->add(['user_name_updated' => $request->user()->name]);
        $responsavel->update($request->all());
        return response()->json($responsavel);
    }

    public function destroy($id)
    {
        $responsavel = Responsavel::find($id);
        $responsavel->delete();
    }
}
