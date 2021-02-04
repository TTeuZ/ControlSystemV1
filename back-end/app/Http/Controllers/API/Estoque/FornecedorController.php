<?php

namespace App\Http\Controllers\API\Estoque;

use App\zModalEstoque\Fornecedor;
use App\zModalEstoque\FornecedorItens;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= [];

        foreach(Fornecedor::all() as $fornecedor){
           array_push($data, [$fornecedor, FornecedorItens::all()->where('fornecedor_id', $fornecedor->id)->values()]);
        }
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'required|string|max:100',
            'telefone' => 'required|string|max:100',
            'email' => 'required|string|max:100'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        $fornecedor = Fornecedor::create($request->all());
        return response()->json($fornecedor);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'string|max:100',
            'telefone' => 'string|max:100',
            'email' => 'string|max:100'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        $fornecedor = Fornecedor::find($id);
        $fornecedor->update($request->all());
        $fornecedor->save();
        return response()->json($fornecedor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
    }
}
