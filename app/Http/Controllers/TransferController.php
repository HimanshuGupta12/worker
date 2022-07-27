<?php

namespace App\Http\Controllers;

use App\Models\ToolHistory;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function transfer($tool_id)
    {
        $tool = user()->company->findTool($tool_id);
        $storages = user()->company->storages()->orderDefault()->get();
        $workers = user()->company->workers()->orderDefault()->get();

        return view('transfer_popup', compact('tool', 'storages', 'workers'));
    }

    public function store($tool_id)
    {
        if (request('to') === 'worker') {
            $possessor = user()->company->workers()->find(request('worker_id'));
        } elseif (request('to') === 'storage') {
            $possessor = user()->company->storages()->find(request('storage_id'));
        } else {
            abort(400);
        }

        $tool = user()->company->findTool($tool_id);
        $tool->transfer($possessor);
        ToolHistory::log($tool, user()->email . ' transferred tool to ' . $possessor->possessorName());

        return redirect()->route('tools.index')->with('success', 'saved');
    }
}
