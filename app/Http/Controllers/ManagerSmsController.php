<?php

namespace App\Http\Controllers;
use App\Models\Sickness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ManagerSmsController extends Controller
{
    public function sickness()
    {

        $worker = worker();
        return view('worker.sickness.sickness_report', compact('worker'));
    }
    public function sicknessSubmition(Request $request)
    {
        $worker = worker();
        $manager_phone_country = user()->phone_country;
        $manager_phone = user()->phone_no;
        // $manager_phone_code ='+'. 45;
        // $manager_phone =52731183;
        $manager_phone = '+'.$manager_phone_country.$manager_phone;

        // ---------------------
        $request->validate([
            'report' => 'required',
            'report_type' => 'required_if:report,==,sickness',
            'description' => 'required_if:report,==,sickness',   
            'date_to' => 'required_if:report,==,sickness', 
            'request_type' => 'required_if:report,==,holiday', 
            'reason_description' => 'required_if:report,==,holiday', 
        ]);
            $holiday = new Sickness();
            $holiday->worker_id = worker()->id;
            $holiday->company_id = $worker->company->id; //worker()->company_id;

            $holiday->report = $request->get('report');
            $holiday->report_type = $request->get('report_type');
            $holiday->description = $request->get('description');
            $holiday->date_from = $request->get('date_from');
            $holiday->date_to = $request->get('date_to');
            $holiday->request_type = $request->get('request_type');
            $holiday->period = $request->get('period');
            $holiday->time_from = $request->get('time_from');
            $holiday->time_to = $request->get('time_to');
            $holiday->reason_description = $request->get('reason_description');
                if($holiday->save()){
                    // sms($manager_phone, $worker->first_name . ' ' . $worker->last_name . ' is request for ' . $report_text = Sickness::$ReportType[$request->get('report_type')]
                    //  . ' ' .request('date_from'). ' ' .request('date_to'). ' ' .request('description') );
                    return redirect()->route('worker.sickness')
                    ->with('success', 'report submitted successfully.');
                    }   
    }
}