<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function index()
    {
        $data = Program::latest()->get();
        $json = ProgramResource::collection($data);
        return response()->json([
            'status' => 'success fetching data',
            'data' => $json,
        ], 200);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validate = Validator::make($input, [
            'name' => 'required | string | max:255',
            'desc' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors(),
            ], 400);
        }
        $data = Program::create([
            'name' => $request->name,
            'desc' => $request->desc,
        ]);
        $json = new ProgramResource($data);
        return response()->json([
            'status' => 'success create data',
            'data' => $json,
        ], 200);
    }

    public function show($id)
    {
        $data = Program::find($id);
        if (is_null($data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'data not found',
            ], 404);
        }
        $json = new ProgramResource($data);
        return response()->json([
            'status' => 'success get detail',
            'data' => $json,
        ], 200);
    }

    public function update(Request $request, Program $program)
    {
        $input = $request->all();
        $validate = Validator::make($input, [
            'name' => 'required|string|max:255',
            'desc' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'Error'
            ], 500);
        }
        $program->name = $request->name;
        $program->desc = $request->desc;
        $program->save();
        $data = new ProgramResource($program);
        return response()->json([
            'message' => 'Success update',
            'data'    => $data
        ], 200);
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return response()->json([
            'message' => 'Success delete data'
        ]);
    }
}
