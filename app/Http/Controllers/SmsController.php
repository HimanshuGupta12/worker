<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function create()
    {
        $worker_id = request('worker_id');
        $worker = user()->company->workers()->findOrFail($worker_id);

        return view('sms.create', compact('worker'));
    }

    public function store()
    {
        $worker_id = request('worker_id');
        $worker = user()->company->workers()->find($worker_id);
        request()->validate([
            'text' => 'max:320',
        ]);
        if (!$worker->phone()) {
            return back()->withErrors("Worker doesn't a have phone number");
        }
        sms($worker->phone(), request('text') . ' ' . $worker->workerLink());
        return redirect()->route('workers.index')->with('success', 'SMS was sent');
    }
}
