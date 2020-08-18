<?php

namespace App\Http\Controllers\API;

use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Equipamento;
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
            'info' => 'required|string|max:15',
            'flag' => 'boolean',
            'equipamento_id' => 'required|integer'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors());

        if (!Equipamento::find($request->equipamento_id))
            return response()->json('Equipamento nao existe');

        $request->request->add(['user_name_created' => $request->user()->name]);
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
            'info' => 'string|max:15',
            'flag' => 'boolean',
        ]);
        if ($validator->fails())
            return response()->json($validator->errors());

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
