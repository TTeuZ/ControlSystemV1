<?php

namespace App\Http\Controllers\API\Manutencao;

use App\zModalManutencao\Status;
use App\zModalManutencao\Equipamento;
use App\zModalManutencao\StatusEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Status::all());
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
            'info' => 'required|string|max:500',
            'flag' => 'boolean',
            'equipamento_id' => 'required|integer',
            'status_enum_id' => 'required|integer'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if (!Equipamento::find($request->equipamento_id))
            return response()->json('Equipamento nao existe');
        if (!StatusEnum::find($request->status_enum_id))
            return response()->json('Status base nao existe');

        $request->request->add(['user_name_created' => $request->user()->name]);
        // dd($request->status_enum_id);
        $status = Status::create($request->all());
        return response()->json($status);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show(Status $status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
        $validator = Validator::make($request->all(),[
            'info' => 'string|max:500',
            'flag' => 'boolean',
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        $request->request->add(['user_name_updated' => $request->user()->name]);
        $status->update($request->all());
        return response()->json($status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        $status->delete();
    }
}
