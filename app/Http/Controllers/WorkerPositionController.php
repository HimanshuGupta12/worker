<?php

namespace App\Http\Controllers;

use App\Models\WorkerPosition;
use Illuminate\Http\Request;

class WorkerPositionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $wp = WorkerPosition::create([
            'company_id' => user()->company_id,
            'name' => $request['name']
        ]);

        return response()->json([
            'status' => 'success' ,
            'data' => $wp->only('id', 'name')
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'string|required|unique:worker_positions',
            'id' => 'integer'
        ]);

        $cwp = WorkerPosition::find($request['id']);
        $cwp->name = $request['name'];
        $cwp->save();
        return response()->json([
            'data' => 'success'
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate(['id' => 'integer']);

        $cwp = WorkerPosition::find($request['id']);
        $cwp->delete();
        return response()->json([
            'data' => 'success'
        ]);
    }
}
