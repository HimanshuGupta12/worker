<?php

namespace App\Http\Controllers;
use App\Models\Sickness;
use Illuminate\Http\Request;

class SicknessController extends Controller
{
    public function index()
    {
        return view('sickworkers.index');
    }

    public function sicknessSubmition(Request $request)
    {
        $worker = worker();
        $worker_user = $worker->company->user;
        $manager_phone_code = $worker_user->phone_country;
        $manager_phone = $worker_user->phone_no;

        // $manager_phone_code = user()->phone_country;
        // $manager_phone = user()->phone_no;
        // $manager_phone_code ='+'. 45;
        // $manager_phone =52731183;
        $manager_phone = '+'.$manager_phone_code.$manager_phone;

        $request->validate([
            'description' => 'max:500',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',   
        ]);
        $diffInSeconds = strtotime($request->get('date_to')) - strtotime($request->get('date_from'));
        $days = ($diffInSeconds / 86400)+1; //+1 is for purpose to include both start and end dates.
        $sickness = new Sickness();
        $sickness->worker_id = worker()->id;
        $sickness->company_id = worker()->company_id; //user()->company->id;
        $sickness->description = $request->get('description');
        $sickness->date_from = $request->get('date_from');
        $sickness->date_to = $request->get('date_to');
        $sickness->leave_duration = $days;
        $redirectUrl = route('worker').'?worker='. worker()->login;
        if($sickness->save()){
            sms($manager_phone, $worker->first_name . ' ' . $worker->last_name . ' is request for Sickleave ' . ' ' .request('date_from'). ' ' .request('date_to'). ' ' .request('description') );

            // return redirect()->route('sickworker.index')
            return redirect($redirectUrl)
            ->with('success', __('Report submitted successfully.'));
        }   
    }
}
