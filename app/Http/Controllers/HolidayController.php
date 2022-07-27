<?php

namespace App\Http\Controllers;
use App\Models\Holiday;

use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        // return $reports = user()->company->holiday()->get();
        return view('holiday.index');
    }

    public function holidaySubmition(Request $request)
    {
        $worker = worker();
        $worker_user = $worker->company->user;
        $manager_phone_code = $worker_user->phone_country;
        $manager_phone = $worker_user->phone_no;

        // $worker = worker();
        // $manager_phone_code = user()->phone_country;
        // $manager_phone = user()->phone_no;
        // $manager_phone_code ='+'. 45;
        // $manager_phone =52731183;
        $manager_phone = '+'.$manager_phone_code.$manager_phone;

        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'description' => 'max:500',    //required_if:request_type,==,requested_period 
        ]);
        $holiday = new Holiday();
        $holiday->worker_id = worker()->id;
        $holiday->company_id = $worker->company->id; //worker()->company_id;
        $holiday->request_type = $request->get('request_type');
        $holiday->leave_duration = $request->get('leave_duration');
        $holiday->time_from = $request->get('time_from');
        $holiday->time_to = $request->get('time_to');
        $holiday->date_from = $request->get('date_from');
        $holiday->date_to = $request->get('date_to');
        $holiday->description = $request->get('description');
        $redirectUrl = route('worker').'?worker='. worker()->login;
        if($holiday->save()){
            sms($manager_phone, $worker->first_name . ' ' . $worker->last_name . ' is request for holiday ' . ' ' .request('date_from'). ' ' .request('date_to'). ' ' .request('description') );
            // return redirect()->route('worker')
            return redirect($redirectUrl)
            ->with('success', __('Holiday request was sent'));
        }   
    }
}