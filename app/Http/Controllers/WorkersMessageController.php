<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkersMessageController extends Controller
{
    public function create()
    {
        $workers = user()->company->workers()->orderDefault()->get();
        return view('message.create', compact('workers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'worker_ids' => 'required',
            'text' => 'required|max:'.config('constants.SMS_TEXT_MAX_LENGTH'),
        ]);
        if ($validator->fails()) {
            return redirect()->route('message.create')->with('danger', $validator->getMessageBag()->first());
        } else {
            try {
                $workers = user()->company->workers()->whereIn('id',request('worker_ids'))->orderDefault()->get();
            
                foreach ($workers as $key => $worker) {
                    if (!empty($worker->phone_country) && !empty($worker->phone_number)) {
                        $worker_phone_country =$worker->phone_country;
                        $worker_phone = $worker->phone_number; 
                        $worker_phone = '+'.$worker_phone_country.$worker_phone;
                        sms($worker_phone, request('text'));
                    }

                }
                return redirect()->route('message.create')->with('success', 'SMS was sent to workers');
            } catch (\Exception $e) {
                return redirect()->route('message.create')->with('danger', $e->getMessage());
            }
        }
    }
}

