<?php

namespace App\Http\Controllers\API\Estoque;

use App\zModalEstoque\Fornecedor;
use App\zModalEstoque\FornecedorItens;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class FornecedorItensController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(FornecedorItens::all());
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
            'quantidade' => 'required|string|max:100',
            'valor' => 'required|string|max:100',
            'fornecedor_id' => 'required|integer',
            'data' => 'required|string|max:100',
            'observacao' => 'required|string|max:500'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);
        
        if (!Fornecedor::find($request->fornecedor_id))
            return response()->json('Fornecedor nao existe');

        $fornecedorItem = FornecedorItens::create($request->all());
        return response()->json($fornecedorItem);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'string|max:100',
            'quantidade' => 'string|max:100',
            'valor' => 'string|max:100',
            'data' => 'string|max:100',
            'observacao' => 'string|max:500'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        $fornecedorItem = FornecedorItens::find($id);
        $fornecedorItem->update($request->all());
        $fornecedorItem->save();
        return response()->json($fornecedorItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(FornecedorItens $fornecedorItem)
    {
        $fornecedorItem->delete();
    }
}
