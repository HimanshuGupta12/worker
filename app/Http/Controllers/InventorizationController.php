<?php

namespace App\Http\Controllers;

use App\Models\Inventorization;
use Illuminate\Http\Request;

class InventorizationController extends Controller
{
    public function edit()
    {
        $page = 'create';
        $month_day = '';
        if (user()->company->inventorization) {
            $page = 'edit';
            $month_day = user()->company->inventorization->month_day;
        }
        $workers = user()->company->workers()->orderDefault()->get();
        $storages = user()->company->storages()->orderDefault()->get();
        $sms_message = '';
        if (user()->company->inventorization && user()->company->inventorization->sms_message) {
            $sms_message = user()->company->inventorization->sms_message;
        }

        return view('inventorization.create_or_edit', compact('month_day', 'page', 'workers', 'storages', 'sms_message'));
    }

    public function enable()
    {
        $v = request()->validate([
            'month_day' => 'required|numeric|between:1,28',
            'sms_message' => 'required|string|max:255',
        ]);

        $company = user()->company;
        Inventorization::enableInventorization($company, $v['month_day'], $v['sms_message']);

        return back()->with('success', 'saved');
    }

    public function disable()
    {
        $company = user()->company;
        Inventorization::disableInventorization($company);

        return back()->with('success', 'disabled');
    }

    public function inventoryWorker()
    {
        request()->validate([
            'sms_text' => 'required|string|max:320',
            'worker_ids' => 'required|array',
        ]);

        $workers = user()->company->workers()->whereIn('id', request('worker_ids'))->get();
        foreach ($workers as $worker) {
            $worker->requestInventorization(request('sms_text'));
        }

        return back()->with('success', 'SMS was sent');
    }

    public function inventoryStorage()
    {
        $storage = user()->company->storages()->find(request('storage_id'));
        $storage->requestInventorization();

        return back()->with('success', 'Requested');
    }
}
