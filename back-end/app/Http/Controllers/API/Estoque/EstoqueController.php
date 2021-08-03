<?php

namespace App\Http\Controllers\API\Estoque;

use App\zModalEstoque\Estoque;
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
        $estoque = Estoque::all();

        $estoque = $estoque->map(function ($item) use ($estoque) {
            $items = $estoque->where('name', $item->name);
            if ($items->count() > 1) {
                if ($item->user_name_updated) {
                    return $item;
                }
                else {
                    return null;
                } 
            }
            else {
                return $item;
            }
        })->reject(function ($item) {
            return $item == null;
        })->values();

        return response()->json($estoque);
    }

    public function attHis()
    {
        return response()->json(Estoque::all()->reject(function ($item) {
            return $item->user_name_updated || $item->created_at == $item->updated_at;
        })->values());
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
            'name' => 'required|string|max:50',
            'tipe' => 'required|string|max:50',
            'quantidade' => 'required|integer',
            'quantidade_min' => 'required|integer',
            'color' => 'string',
            'flag' => 'boolean'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if ($request->user()->id <= 5) {
            $request->request->add(['user_name_created' => $request->user()->name]);
            $estoque = Estoque::create($request->all());
            return response()->json($estoque);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para adicionar itens'
            ], 401);
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
            'color' => 'string',
            'flag' => 'boolean'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);
        
        $request->request->add(['user_name_updated' => $request->user()->name]);

        $estoque->update($request->all());

        if ($request->name) {
            $historico = new Estoque;
            $historico->name = $estoque->name;
            $historico->tipe = $estoque->tipe;
            $historico->quantidade = $estoque->quantidade;
            $historico->quantidade_min = $estoque->quantidade_min;
            $historico->flag = $estoque->flag;
            $historico->user_name_created = $estoque->user_name_created;
            $historico->created_at = $estoque->created_at;
            
            $historico->save();
        }
        
        return response()->json($estoque);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ($request->user()->id <= 5) {
            $estoque = Estoque::find($id);
            $estoque->delete();
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para deletar itens'
            ]);
        }
    }
}
