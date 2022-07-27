<?php

namespace App\Http\Controllers;

use App\Models\Storage;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function index()
    {
        $storages = user()->company->storages()->orderDefault()->withCount(['tools', 'toolsNeedInventorization'])->paginate(25);

        return view('storages.index', compact('storages'));
    }

    public function create()
    {
        return view('storages.create');
    }

    public function store()
    {
        $v = request()->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        Storage::createStorage(user()->company, $v['name'], $v['address']);

        return redirect()->route('storages.index')->with('success', 'saved');
    }

    public function destroy($storage_id)
    {
        $storage = user()->company->storages()->find($storage_id);
        if ($storage->trashed()) {
            abort(400, 'Already deleted');
        }
        $storage->delete();

        return back()->with('success', 'deleted');
    }
}
