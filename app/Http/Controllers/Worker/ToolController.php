<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolHistory;
use App\Models\ToolStatus;
use App\Models\Worker;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ToolController extends Controller
{
    public function index()
    {
        $tools = worker()->tools()->filter()->orderByDesc('created_at')->paginate(25);
        $tools->load('status');
        $show_balance = worker()->tools()->needInventorization()->exists();
        $statuses = ToolStatus::get();
        $value = worker()->tools()->filter()->sum('price');

        return view('worker.tools.index', compact('tools', 'show_balance', 'statuses', 'value'));
    }

    public function take()
    {
        $qr = request('code');
        $worker = worker();

        // if it is a worker tool, find it in the list
        try {
            $tool = $worker->findTool($qr);
            if ($tool) {
                return redirect()->route('scan', ['redirect' => route('worker.tools.take')])->withErrors(__("Tool is already yours"));
            }
        } catch (ValidationException) {} // do nothing

        $tool = $worker->company->findTool($qr);
        if ($tool->status->name !== 'operational') {
            return back()->withErrors(__("Tool is not operational"));
        }

        return view('worker.tools.take', compact('tool', 'worker'));
    }

    public function takePost()
    {
        $tool_id = request('tool_id');
        $worker = worker();
        $tool = $worker->company->findTool($tool_id);
        if ($tool->status->name !== 'operational') {
            return back()->withErrors(__("Tool is not operational"));
        }
        $tool->transfer($worker);
        ToolHistory::log($tool, worker()->fullName() . ' took the tool');

        return redirect()->route('scan', ['redirect' => route('worker.tools.take')])->with('success', __("Tool was added"));
    }

    public function inventorize()
    {
        $tool_qr = request('code');
        $worker = worker();

        //Check if tool exists in worker's company.
        $tool = $worker->company->findTool($tool_qr);
        //Check if worker has permission to balance storage. Or allow worker if tool is taken by him even if he has no permission.
        if (!$worker->inventory_storage) {
            $workerToolIds = worker()->tools()->pluck('id')->toArray();
            if (!in_array($tool->id, $workerToolIds)) {
                return redirect()->route('worker.scan.inventory', ['redirect' => route('worker.tools.inventorize')])->withErrors(__('This tool is assigned to'). ' "' . $tool->possessor->possessorName() . '" '. __('and you have no permission to balance it.'));
            }
        }
        if ($tool->needsInventorization() !== true) {
            return redirect()->route('worker.scan.inventory', ['redirect' => route('worker.tools.inventorize')])->withErrors('"' . $tool->name . '" ' . __("doesn't need balancing"));
        }
        $tool->inventory();
        if ($tool->possessor::class === Storage::class) {
            $storage = Storage::find($tool->possessor_id);
            ToolHistory::log($tool, $worker->fullName() . ' balanced storage "'.$storage->name.'" tool');
        }elseif ($tool->possessor::class === Worker::class) {
            ToolHistory::log($tool, $worker->fullName() . ' balanced "personal" tool');
        }

        if (!$worker->toolsNeedInventorization()->exists()) {
            return redirect()->route('worker.tools.index')->with('success', 'All tools were balanced');
        }

        return redirect()->route('worker.scan.inventory', ['redirect' => route('worker.tools.inventorize')])->with('success', '"' . $tool->name . '" '.__('was balanced'));
    }

    public function scanToStorage0()
    {
        abort_if(!worker()->scan_to_storage, 401);

        $storages = worker()->company->storages()->orderDefault()->get();

        return view('worker.tools.scan_to_storage0', compact('storages'));
    }

    public function scanToStorage1()
    {
        abort_if(!worker()->scan_to_storage, 401);
        return redirect()->route('scan', [
            'redirect' => route('worker.tools.scan-to-storage', request('storage_id')),
            'back' => route('worker'),
        ]);
    }

    public function scanToStorage($selected_storage_id)
    {
        abort_if(!worker()->scan_to_storage, 401);

        $tool_id = request('code');
        $tool = worker()->company->findTool($tool_id);
        $storage = worker()->company->storages()->find($selected_storage_id);

        if ($tool->possessor?->is($storage)) {
            return back()->withErrors(__('This tool is already in this storage'));
        }
        $tool->transfer($storage);
        ToolHistory::log($tool, worker()->fullName() . ' transferred tool to storage "' . $storage->name . '"');

        return redirect()
            ->route('scan', ['redirect' => route('worker.tools.scan-to-storage', request('storage_id'))])
            ->with('success', __('transferred'));
    }

    public function changeStatus($tool_id)
    {
        $tool = worker()->findTool($tool_id);
        abort_if(!$tool->canWorkerReport(), 400);
        return view('worker.tools.change_status', compact('tool'));
    }

    public function changeStatusPost($tool_id)
    {
        request()->validate([
            'problem_type' => 'required|in:broken,lost,in service',
            'problem_description' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,png|max:10000|dimensions:max_width=5000,max_height=5000',
        ]);

        $tool = worker()->findTool($tool_id);
        abort_if(!$tool->canWorkerReport(), 400);
        $tool->workerReportProblem(request('problem_type'), request('problem_description'), request('photo'));

        $status_name = request('problem_type');
        ToolHistory::log($tool, worker()->fullName() . ' changed status to "' . $status_name . '"');

        return redirect()->route('worker.tools.index')->with('success', __('Reported'));
    }
}
