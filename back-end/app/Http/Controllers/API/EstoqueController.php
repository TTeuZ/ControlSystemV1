<?php

namespace App\Http\Controllers\API;

use App\Estoque;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class EstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Estoque::all());
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
            'name' => 'requerid|string|max:50',
            'tipe' => 'requerid|string|max:50',
            'quantidade' => 'required|integer',
            'quantidade_min' => 'required|integer',
            'flag' => 'boolean'
        ]);
        // if ($validator->fails())
        //     return response()->json($validator->errors());

        if ($request->user()->id <= 5) {
            $estoque = Estoque::create($request->all());
            return response()->json($estoque);
        }
        else {
            return $this->enviarRespostaErro('Vocẽ não pode criar itens');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function show(Estoque $estoque)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estoque $estoque)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'string|max:50',
            'tipe' => 'string|max:50',
            'quantidade' => 'integer',
            'quantidade_min' => 'integer',
            'flag' => 'boolean'
        ]);
        if ($validator->fails())
            return response()->json($validator->erros());
        
        $estoque->update($request->all());
        return response()->json($estoque);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Estoque $estoque)
    {
        if ($request->user()->id <= 5) {
            $estoque->delete();
        }
        else {
            return $this->enviarRespostaErro('Vocẽ não pode deletar itens');
        }
    }
}
