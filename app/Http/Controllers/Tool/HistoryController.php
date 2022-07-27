<?php

namespace App\Http\Controllers\Tool;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;

class HistoryController extends Controller
{
    public function index($tool_id)
    {
        $tool = user()->company->findTool($tool_id);
        $histories = $tool->histories()->orderByDesc('id')->get();
        $histories->load('possessor');
        
        //return view('tools.histories.index', compact('histories', 'tool'));
        return view('tools.histories.content', compact('histories', 'tool'));
    }
}
