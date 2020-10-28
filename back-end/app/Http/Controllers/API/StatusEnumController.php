<?php

namespace App\Http\Controllers\API;

use App\StatusEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Status;
use Validator;

class StatusEnumController extends Controller
{
    public function index()
    {
        return response()->json(StatusEnum::orderBy('title')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:150'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if ($request->user()->id <= 5) {
            $statusEnum = StatusEnum::create($request->all());
            return response()->json($statusEnum);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para adicionar itens'
            ], 401);
        }
    }

    public function update(Request $request, StatusEnum $statusEnum)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'string|max:150'
        ]);
        if ($validator->fails())
            return response()->json([
                'mensagem' => 'Preencha todos os campos',
                $validator->errors()
            ], 400);

        if ($request->user()->id <= 5) {
            $statusEnum->update($request->all());
            return response()->json($statusEnum);
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para adicionar itens'
            ], 401);
        }
    }

    public function destroy(Request $request, StatusEnum $statusEnum) // wtf como isso funciona, mas ta funcionando
    {
        if ($request->user()->id <= 5) {
            $statusEnum->delete();
        } else {
            return response()->json([
                'mensagem' => 'você não tem permissão para adicionar itens'
            ], 401);
        }
    }
}
