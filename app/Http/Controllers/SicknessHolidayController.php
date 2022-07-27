<?php

namespace App\Http\Controllers;
use App\Models\{Holiday,Worker};
use Illuminate\Http\Request;

class SicknessHolidayController extends Controller
{
    public function index()
    {
        $holiday = user()->company->holiday()->get();
        $sickness = user()->company->sickness()->get();

        return view('worker.sickness.index',compact('sickness','holiday'));
        
    }

    public function changeStatus(Request $request)
    {
        $worker = Worker::find($request->get('worker_id'));
        $holiday = Holiday::find($request->get('sickness_id'));
        $date_from = $holiday->date_from;
        $date_to = $holiday->date_to;
        //$days = date_diff(date_create($date_to), date_create($date_from));
        $diffInSeconds = strtotime($holiday->date_to) - strtotime($holiday->date_from);
        $days = $diffInSeconds / 86400;
        $worker_phone_country = $worker->phone_country;
        $worker_phone = $worker->phone_number;
        $worker_phone = '+'.$worker_phone_country.$worker_phone;
        Holiday::where('id',$request->get('sickness_id'))->update(['status' => $request->get('status'), 'comment'=> $request->get('comment')]);
        $smsText = '';
        if($request->get('status') === "1")
        {
            holidayApproved($worker,$date_from,$date_to,$days);

            // $smsText = 'Hello '. $worker->fullName() .' your holidays was approved. You will be on holidays from: '.$date_from.' to '.$date_to.'. Total: '.$days.'d. Have a nice holiday. '.request('comment');
            // sms($worker_phone, $smsText);
        }
        elseif($request->get('status') === "2")
        {
            holidayNotApproved($worker,$date_from,$date_to);

            // $smsText = 'Hello '. $worker->fullName() .' your holidays from: '.$date_from.' to '.$date_to.' was not approved. '.request('comment');
            // sms($worker_phone, $smsText);
        }
        // elseif($request->get('status') === "3")
        // {
        //     $company = user();
        //     $status_text = Holiday::$leaveStatus[$request->get('status')];
        //     $smsText = $company->name .' '. $status_text .' your request: '.request('comment');
        //     sms($worker_phone, $smsText);
        // }
        
        
        
        return redirect()->route('sickness.holiday')
        ->with('success', 'Status changed successfully.');
    }
}