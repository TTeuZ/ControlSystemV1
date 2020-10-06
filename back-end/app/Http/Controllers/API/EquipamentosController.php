<?php

namespace App\Http\Controllers\API;

use App\Equipamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Status;
use Validator;

class EquipamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= [];

        foreach(Equipamento::all() as $equipamento){
           array_push($data, [$equipamento, Status::all()->where('equipamento_id', $equipamento->id)]);
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
            'name' => 'required|string|max:15',
            'done' => 'boolean'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors());

        $request->request->add(['user_name_created' => $request->user()->name]);
        $equipamento = Equipamento::create($request->all());
        return response()->json($equipamento);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Equipamento  $equipamento
     * @return \Illuminate\Http\Response
     */
    public function show(Equipamento $equipamento)
    {
        $data = [$equipamento, $equipamento->status()];
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Equipamento  $equipamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipamento $equipamento) // nao funciona
    {
        $validator = Validator::make($request->all(),[
            'name' => 'string|max:15',
            'done' => 'boolean'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors());

        $request->request->add(['user_name_updated' => $request->user()->name]);
        $equipamento->update($request->all());
        return response()->json($equipamento);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Equipamento  $equipamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipamento $equipamento)
    {
        $equipamento->delete();
    }
}
