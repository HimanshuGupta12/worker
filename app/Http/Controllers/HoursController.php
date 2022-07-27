<?php

namespace App\Http\Controllers;
use App\Models\{Hour,Project,Worker};
use DateTime;
use PDF;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\HoursNotification;

class HoursController extends Controller
{
    public function index()
    {
        $hours = Hour::where('company_id',user()->company_id)->filter()->with('project','worker')->orderBy('work_day','DESC')->paginate(500);
        $stats = Hour::calculateHoursStats($hours);
        $projects = Project::where('company_id',user()->company_id)->where('status', '=', 'active')->orderBy('created_at','DESC')->get();
        $workers = user()->company->workers()->orderDefault()->where('status', '=', 1)->get();
        $worker = null;
        $project = null;
        $date = (isset($_GET['date']) && $_GET['date'] != 'Select date') ? $_GET['date'] : null;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
        $stamp_invoice = isset($_GET['stamp_invoice']) ? $_GET['stamp_invoice'] : null;

        $workerSubmittedHours = null;
        $projectSubmittedHoursInDate = null;
        $projectSubmittedHoursInTotal = null;
        $projectIndividualHoursByWorker = [];
        $workerIndividualHoursByProject = [];
        if (!empty($stats['worker']['members'])) {
            $data = Hour::whereIn('worker_id', $stats['worker']['members'])->filter()->with('worker')->get();
            foreach($data as $row) {
                if (!isset($projectIndividualHoursByWorker[$row->worker_id])) {
                    $projectIndividualHoursByWorker[$row->worker_id] = ['id'=>$row['worker']->id, 'worker_name' => $row['worker']->fullname(), 'sum' => $row->working_hours];
                } else {
                    $projectIndividualHoursByWorker[$row->worker_id]['sum'] += $row->working_hours;
                }
            }
        }
        if (!empty($stats['project']['members'])) {
            $data = Hour::whereIn('project_id', $stats['project']['members'])->filter()->with('project')->get();
            foreach($data as $row) {
                if (!isset($workerIndividualHoursByProject[$row->project_id])) {
                    $workerIndividualHoursByProject[$row->project_id] = ['id'=>$row['project']->id,'project_name' => $row['project']->nameAndNumber(), 'sum' => $row->working_hours];
                } else {
                    $workerIndividualHoursByProject[$row->project_id]['sum'] += $row->working_hours;
                }
            }
        }
        if (request('worker') && trim(request('worker')) != 'Select worker') {
            $worker = Worker::where('id',request('worker'))->first();
//            if (request('project') && trim(request('project')) != 'Select project') {
//                $workerSubmittedHours = Hour::where('worker_id', request('worker'))->where('project_id', request('project'))->sum('working_hours');
//            } else {
//                $workerSubmittedHours = Hour::where('worker_id', request('worker'))->sum('working_hours');
//            }
            $workerSubmittedHours = Hour::where('company_id',user()->company_id)->filter()->sum('working_hours');
        }
        if (request('project') && trim(request('project')) != 'Select project') {
            $project = Project::where('id',request('project'))->with('workers')->first();
//            if (request('worker') && trim(request('worker')) != 'Select worker') {
//                $projectSubmittedHours = Hour::where('project_id', request('project'))->where('worker_id', request('worker'))->sum('working_hours');
//            } else {
//                $projectSubmittedHours = Hour::where('project_id', request('project'))->sum('working_hours');
//            }
            $projectSubmittedHoursInDate = Hour::where('company_id',user()->company_id)->filter()->sum('working_hours');
            $projectSubmittedHoursInTotal = Hour::where('company_id',user()->company_id)->where('project_id', request('project'))->sum('working_hours');
        }

        $view_settings = getCustomCookie('hours_settings_wrkr');
        $pdf_settings = getCustomCookie('pdf_settings');

        return view('hours.hours-report',compact('workers','hours','projects','worker','project', 'stats', 'workerSubmittedHours', 'projectSubmittedHoursInDate', 'projectSubmittedHoursInTotal', 'projectIndividualHoursByWorker', 'workerIndividualHoursByProject', 'date', 'start_date', 'end_date', 'stamp_invoice', 'view_settings', 'pdf_settings'));
    }

    public function generatePDF()
    {
        $hours = Hour::where('company_id',user()->company_id)->filter()->with('project','worker')->orderBy('work_day','asc')->get();
        $totalentries = count($hours);
        $totaluniquedays = $hours->unique('work_day')->count();
        $stats = Hour::calculateHoursStats($hours);
        $company = user()->company;
        $user = user();
        $worker = Worker::where('id',request('worker'))->first();
        $project = Project::where('id',request('project'))->with('workers')->first();

        $pdf_settings['hide_worker_position'] = $hide_worker_position = request('hide_worker_position');
        $pdf_settings['hide_contractor'] = $hide_contractor = request('hide_contractor');
        $pdf_settings['hide_breaks'] = $hide_breaks = request('hide_breaks');
        $pdf_settings['hide_comments'] = $hide_comments = request('hide_comments');
        $pdf_settings['hide_pictures'] = $hide_pictures = request('hide_pictures');
        Cookie::queue('pdf_settings', json_encode($pdf_settings));

        $dateRange = getDateRangeFromDateOption();
        $minDate = $dateRange['start_date'];
        $maxDate = $dateRange['end_date'];
        /*$minDate = $stats['worker']['min_date'];
        if ($stats['project']['min_date'] < $stats['worker']['min_date'])
        {
            $minDate = $stats['project']['min_date'];
        }
        $maxDate = $stats['worker']['max_date'];
        if ($stats['project']['max_date'] > $stats['worker']['max_date'])
        {
            $maxDate = $stats['project']['max_date'];
        }*/

        $data = [
            'title' => 'Hours report',
            'date' => date('m/d/Y'),
            'hours' => $hours,
            'stats' => $stats,
            'totalentries' => $totalentries,
            'totaluniquedays' => $totaluniquedays,
            'company' => $company,
            'user' => $user,
            'minDate' => $minDate,
            'maxDate' => $maxDate,
            'worker' => $worker,
            'project' => $project,
            'hide_worker_position' => $hide_worker_position,
            'hide_contractor' => $hide_contractor,
            'hide_breaks' => $hide_breaks,
            'hide_comments' => $hide_comments,
            'hide_pictures' => $hide_pictures
        ];

        $pdf =PDF::loadView('hours.myPDF', $data)->setPaper('a4', 'landscape')->setWarnings(false);//->save('myfile.pdf');

        $fileName = 'Normal Hours '. date("d.m.Y", strtotime($minDate)).' to '. date("d.m.Y", strtotime($maxDate)).'.pdf';
        // return $pdf->download($fileName);
        return $pdf->stream($fileName);
    }

    public function showimage($hashed_hour_id)
    {
        $hour_id = Hour::decodeHourId($hashed_hour_id);
        $hour = Hour::where('id', $hour_id)->firstOrFail();
        return view('hours.show-image',compact('hour'));
    }

    public function edit($hour_id)
    {
        if (Auth::check()) {
            $user = user();
        } elseif (\App\Models\Worker::isLoggedIn()) {
            $user = worker();
        }
        $hour = Hour::where('id', $hour_id)->firstOrFail();
        $projects = Project::where('company_id',$user->company_id)->where('status', '=', 'active')->orderBy('name')->get();
        $translations = js_translations(App::getLocale());

        return view('hours.edit-hours-report',['hour'=> $hour,'projects'=> $projects,'translations'=> $translations]);
    }

    public function getOverlapping ($worker_id)
    {
        $v = request()->validate(Hour::$rules);
        $start_time = $v['start_time'];
        $end_time = $v['end_time'];
        $v['work_day'] = date("Y-m-d", strtotime($v['work_day']));
        $hours = Hour::where('id', '!=', request('id'))->where('worker_id', $worker_id)->where('project_id', $v['project_id'])->where('work_day', $v['work_day'])
                ->where(function($query) use ($start_time, $end_time){
                    $query->where(function($q) use ($start_time, $end_time){
                        $q->where('hours.start_time', '>', $start_time);
                        $q->where('hours.end_time', '>', $end_time);
                        $q->where('hours.start_time', '<', $end_time);
                    });
                    $query->orWhere(function($q) use ($start_time, $end_time){
                        $q->where('hours.start_time', '<=', $start_time);
                        $q->where('hours.start_time', '<', $end_time);
                        $q->where('hours.end_time', '>=', $end_time);
                        $q->where('hours.end_time', '>', $start_time);
                    });
                    $query->orWhere(function($q) use ($start_time, $end_time){
                        $q->where('hours.start_time', '<', $start_time);
                        $q->where('hours.end_time', '<', $end_time);
                        $q->where('hours.end_time', '>', $start_time);
                    });
                    $query->orWhere(function($q) use ($start_time, $end_time){
                        $q->where('hours.start_time', '>=', $start_time);
                        $q->where('hours.end_time', '<=', $end_time);
                    });
                })->with('project', 'worker')->get()->toArray();
        return ['hours' => $hours];
    }

    public function update()
    {
        $oldHour = Hour::where('id', request('id'))->firstOrFail();

        if (Auth::check()) {
            $user = user();
        } elseif (\App\Models\Worker::isLoggedIn()) {
            $user = worker();
        }
        $v = request()->validate(Hour::$rules);
        $workingHours = Hour::calculateHours($v['start_time'], $v['end_time'], $v['break_time']);
        $work_day = date('Y-m-d', strtotime(str_replace(',', '', $v['work_day'])));// strtotime works only if comma is not present in this format of date.
        $lateHours = Hour::lateSubmissionHours($v['end_time'], $work_day);

        $hour = Hour::where('id', request('id'))->firstOrFail();
        $hour->worker_id = request('worker_id');
        $hour->company_id = $user->company_id;
        $hour->project_id = $v['project_id'];
        $hour->work_day = $work_day;
        $hour->start_time = $v['start_time'];
        $hour->end_time = $v['end_time'];
        $hour->lunch_break = (isset($v['lunch_break']) && $v['lunch_break'] == "on") ? true : false;
        $hour->break_time = $v['break_time'];
        $hour->working_hours = $workingHours;
        $hour->late_submission_hours =  $lateHours;
        $hour->no_of_words_in_comments = str_word_count($v['comments']);
        if (isset($_FILES['images']['name'])) {
            $hour->no_of_images = count($_FILES['images']['name']);
        }
        $hour->comments = request('comments') ? $v['comments']: NULL;
        $hour->save();
        //$hour->addImages($v['images'] ?? []);
        if (\App\Models\Worker::isLoggedIn()) {
            $newHour = Hour::where('id', request('id'))->firstOrFail();
            if (isset($user->company->notification_email)) {
                $user->company->user->email = $user->company->notification_email;// overwrite the company notification email if it is configured.
            }
            Mail::to($user->company->user)->send(new HoursNotification($oldHour, $newHour));
            \Illuminate\Support\Facades\Log::info('email sent at : '. $user->company->user->email);
        }
            return redirect()->back()->with('success', 'Hours updated successfully');
    }


    public function updateInLine($hour_id, Request $request){
        $hour = Hour::findOrFail( $hour_id);
        $request->validate([
            'type' => 'string|required',
            'from' => 'date_format:H:i',
            'to' => 'date_format:H:i|after:from',
            'date' => 'date'
        ]);
        switch (request('type')) {
            case 'hour':
                $hour->start_time = request('from');
                $hour->end_time = request('to');
                $lateHours = Hour::lateSubmissionHours($hour->end_time, $hour->work_day);
                $hour->late_submission_hours =  $lateHours;
                break;

            case 'date':
                $hour->work_day = request('date');
                $lateHours = Hour::lateSubmissionHours($hour->end_time, $hour->work_day, $hour->created_at);
                $hour->late_submission_hours =  $lateHours;
                break;
        }
        $hour->save();

        return [
            'data' => $hour->only('id', 'work_day', 'start_time', 'end_time', 'work_day'),
            'status' => 'success'
        ];
    }

    public function updateReport ()
    {
        $action = request('action');
        $hour_ids = request('hour_ids');
        if ($action == "Invoiced") {
            Hour::whereIn('id', $hour_ids)->update(['stamp_invoice'=>1]);
        } elseif ($action == "Not Invoiced") {
            Hour::whereIn('id', $hour_ids)->update(['stamp_invoice' => 0]);
        } elseif ($action == "Deleted") {
            Hour::whereIn('id', $hour_ids)->delete();
        }
        return ['redirect' => route('hours.index'), 'success'=> "Selected hours marked as '$action' successfully!"];
        //return redirect()->route('hours.index')->with('success', "Selected hours marked as '$action' successfully!");
    }

    public function updateComments ()
    {
        $hour = Hour::where('id', request('id'))->firstOrFail();

        $hour->comments = request('comments');
        $hour->no_of_words_in_comments = str_word_count(request('comments'));
        $hour->save();

        return ['redirect' => route('hours.index'), 'success'=> __("Comments updated successfully!")];
        //return redirect()->route('hours.index')->with('success', "Selected hours marked as '$action' successfully!");
    }

    public function destroy($hour_id)
    {
        $hour = Hour::where('id',$hour_id)->first();
        if (!$hour) {
            abort(400, 'Already deleted');
        }
//        foreach ($hour->images as $k => $image) {
//            \Illuminate\Support\Facades\Storage::delete($image);
//        }
        $hour->delete();
        return back()->with('success', 'Hour Deleted Successfully');
    }

    public function hourImages($hour_id)
    {
        $hour = Hour::where('id', $hour_id)->firstOrFail();
        return view('hours.edit-image',compact('hour'));
    }

    public function updateImages($hour_id)
    {
        $hour = Hour::where('id', $hour_id)->firstOrFail();
        $imageData = request('images') ?? [];
        $hour->addImages($imageData, user()->company_id);
        $hour->no_of_images = count($hour->images);
        $hour->save();
        return back()->with('success', 'New images added.');
    }

    public function deleteHourImages($hour_id, $image_nr)
    {
        $hour = Hour::where('id', $hour_id)->firstOrFail();
        $image_name = $hour->images[$image_nr];
        $hour->deleteImage($image_name);
        $hour->no_of_images = count($hour->images);
        $hour->save();
        //return back()->with('success', 'deleted');
        return ['sucess' => true, 'message'=> 'deleted'];
    }

    public function markAsInvoice ($hour_id)
    {
        $hour = Hour::where('id',$hour_id)->first()->update(['stamp_invoice'=>1]);
        return back();
    }
    public function markAsNotInvoice ($hour_id)
    {
        $hour = Hour::where('id',$hour_id)->first()->update(['stamp_invoice'=>0]);
        return back();
    }
}
