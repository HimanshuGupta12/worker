<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\ToolHistory;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function chooseStorage()
    {
        abort_if(!worker()->inventory_storage, 401);

        $storages = worker()->company->storages()->orderDefault()->get();

        return view('worker.inventory_storage.choose_storage', compact('storages'));
    }

    public function inventory($storage_id)
    {
        abort_if(!worker()->inventory_storage, 401);
        $storage = worker()->company->storages()->find($storage_id);

        $tool_id = request('code');
        $tool = $storage->findTool($tool_id);
        $tool->inventory();
        if ($tool->possessor::class === Storage::class) {
            ToolHistory::log($tool, worker()->fullName() . ' balanced storage "'.$storage->name.'" tool');
        }elseif ($tool->possessor::class === Worker::class) {
            ToolHistory::log($tool, worker()->fullName() . ' balanced "personal" tool');
        }

        if ($storage->toolsNeedInventorization()->count() === 0) {
            return redirect()->route('worker')->with('success', __('All tools are balanced in this storage.'));
        }

        return redirect()->route('scan', [
            'redirect' => route('worker.inventory-storage', $storage->id),
            'back' => route('worker'),
        ])->with('success', __('inventoried'));
    }
}
