<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\ToolHistory;
use App\Models\ToolStatus;
use Illuminate\Http\Request;

class CompanyToolController extends Controller
{
    public function companyTools()
    {
        abort_if(!worker()->see_company_tools, 401);

        $tools = worker()->company->tools()->filter()->orderByDesc('created_at')->paginate(25);
        $tools->load('possessor', 'status');
        $workers = worker()->company->workers()->orderDefault()->get();
        $storages = worker()->company->storages()->orderDefault()->get();
        $categories = worker()->company->toolCategories()->orderDefault()->get();

        return view('worker.company_tools.index', compact('tools', 'workers', 'storages', 'categories'));
    }

    public function changeStatus($tool_id)
    {
        abort_if(!worker()->change_tool_status, 401);

        $tool = worker()->company->findTool($tool_id);
        $statuses = ToolStatus::get();

        return view('worker.company_tools.change_status', compact('tool', 'statuses'));
    }

    public function changeStatusPost($tool_id)
    {
        abort_if(!worker()->change_tool_status, 401);

        request()->validate([
            'status_id' => 'required|exists:tool_statuses,id',
            'description' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,png|max:10000|dimensions:max_width=5000,max_height=5000',
        ]);

        $tool = worker()->company->findTool($tool_id);
        $status = ToolStatus::find(request('status_id'));
        $tool->changeStatus($status, request('description'), request('photo'));
        ToolHistory::log($tool, worker()->fullName() . ' changed status to "' . $status->name . '"');

        return redirect()->route('worker.company-tools.index')->with('success', 'Updated');
    }
}
