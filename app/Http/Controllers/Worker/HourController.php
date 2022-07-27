<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Mail\LateHourSubmissionNotificationMail;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Models\Hour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class HourController extends Controller
{
    public function index() {
        $worker = worker();
        $total_hours = Hour::calculateTotalWorkerHours ($worker->id);
        $projects = $worker->projects()->orderBy('created_at','DESC')->get();
        $lastWorkDay = Hour::where('worker_id', $worker->id)->orderByDesc('work_day')->value('work_day');
        $workDays = Hour::where('worker_id', $worker->id)->pluck('work_day');
        $translations = js_translations(App::getLocale());
        $url = route('hours.store');
        $lateSubmissionSettings = $worker->company->settings ? ($worker->company->settings['late'] ?? []) : [];
        $maxDayForLateSubmission = $lateSubmissionSettings['maxDay'] ?? 999;
        $messageForLateSubmission = $lateSubmissionSettings['message'] ?? "";
        return view('worker.hours.register_hours', compact('projects','url','total_hours', 'lastWorkDay', 'translations', 'workDays', 'messageForLateSubmission', 'maxDayForLateSubmission'));
    }

    public function getProjectHourByDay($pid, $work_day) {
        $worker = worker();
        $work_day = date('Y-m-d', (integer)$work_day);
        $hours = Hour::with('project')->where('worker_id', $worker->id)/*->where('project_id', $pid)*/->where('work_day', $work_day)->get()->toArray();
        return $hours;
    }

    public function getProjectHourByTime($pid, $work_day, $start_time, $end_time) {
        $worker = worker();
        $work_day = date("Y-m-d", strtotime($work_day));
        $start_time = str_replace(";",":",$start_time);
        $end_time = str_replace(";",":",$end_time);
        $start_time = $start_time .":00";
        $end_time = $end_time .":00";
        $hours = Hour::where('worker_id', $worker->id)->where('project_id', $pid)->where('work_day', $work_day)
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
                })->with('project')->get()->toArray();

        return ['hours' => $hours];
    }

    public function store() {
        $v = request()->validate(Hour::$rules);
        $work_day = date_create($v['work_day']);
        $v['work_day'] = date_format($work_day,"Y-m-d");
        $v['break_time'] = empty($v['break_time']) ? 0 : $v['break_time'];
        $workingHours = Hour::calculateHours($v['start_time'], $v['end_time'], $v['break_time']);
        $lateHours = Hour::lateSubmissionHours($v['end_time'], $v['work_day']);
        
        $hour = new Hour();
        $worker = worker();
        $hour->worker_id = $worker->id;
        $hour->company_id = $worker->company->id;
        $hour->project_id = $v['project_id'];
        $hour->work_day = $v['work_day'];
        $hour->start_time = $v['start_time'];
        $hour->end_time = $v['end_time'];
        $hour->lunch_break = $v['lunch_break'];
        $hour->break_time = $v['break_time'];
        $hour->working_hours = $workingHours;
        $hour->late_submission_reason =  $v['late_submission_reason'] ?? NULL;
        $hour->late_submission_hours =  $lateHours;
        $hour->no_of_words_in_comments = str_word_count($v['comments']);
        $hour->no_of_images = isset($_FILES['images']['name']) ? count($_FILES['images']['name']) : 0;
        $hour->comments = request('comments') ? $v['comments']: NULL;
        $hour->save();
        if($hour->late_submission_reason) {
//            send email for the notification mail of the company
            $notificationReceiverEmail = $worker->company->notification_email ?? $worker->company->user->email;

            $settings = $worker->company->settings ?? [];
            $lateHourSettings = $settings['late']?? [];
            $dontSendEmail = $lateHourSettings['disableNotifications'] ?? false;
            if(!$dontSendEmail){
                Mail::to($notificationReceiverEmail)->send( new LateHourSubmissionNotificationMail(
                    $worker->company->name, $hour
                ));
                if (Mail::failures() != 0) {

                }
            }
        }
        if (request('images')) {
            $imageData = $v['images'];
            $hour->addCompressedImages($imageData, $worker->company->id);
            return ['redirect' => route('hours.add-more', $hour->id)];
        } else {
            return redirect()->route('hours.add-more', $hour->id);
        }
    }

    public function edit($hour_id) {

        $hour = Hour::where('id', $hour_id)->firstOrFail();
        $worker = worker();
        $total_hours = Hour::calculateTotalWorkerHours ($worker->id);
        $projects = $worker->projects()->orderBy('created_at','DESC')->get();
        $lastWorkDay = Hour::where('worker_id', $worker->id)->orderByDesc('work_day')->value('work_day');
        $workDays = Hour::where('worker_id', $worker->id)->pluck('work_day');
        $translations = js_translations(App::getLocale());
        $url = route('worker.update', $hour->id);

        return view('worker.hours.register_hours', compact('hour','url','projects', 'total_hours', 'lastWorkDay', 'translations', 'workDays'));
    }

    public function update()
    {
        return ('dfg');
    }

    public function addMore($hour_id)
    {
        $hour = Hour::where('id', $hour_id)->firstOrFail();
        $worker = worker();
        if ($worker->company_id != $hour->company_id) {
            abort(404, __('Not found'));
        }
        $time1 = strtotime($hour->start_time);
        $time2 = strtotime($hour->end_time);
        $break_time = $hour->break_time*60; // get break time in seconds
//        if($time2 < $time1) {
//            $time2 += 24 * 60 * 60;
//        }
        $hours = floor(($time2 - $time1 - $break_time) / 3600);
        $mins = ($time2 - $time1 - $break_time) % 3600; // seoconds are returned as modulus.
        $mins = sprintf("%02d", $mins/60);
        $work_duration = $hours .':'. $mins;

        return view('worker.hours.add-more', compact('hour', 'work_duration', 'worker'));
    }

    public function destroy($hourId) {
        $hour = Hour::find($hourId);
        if (!$hour) {
            abort(400, __('Already deleted'));
        }
        if (!empty($hour->images)) {
            foreach ($hour->images as $k => $image) {
                \Illuminate\Support\Facades\Storage::delete($image);
            }
        }
        //$hour->delete();
        $hour->forceDelete();

        return ['success' => true, 'message'=> __('hour deleted')];
    }

    public function seeHour()
    {
        $worker = worker();
        $edithour = $worker->edit_hours;
        $unpaginatedHours = Hour::where('worker_id',worker()->id)->filter()->with('project','worker')->orderBy('work_day','DESC');
        $hours = $unpaginatedHours->clone()->paginate(25);
        $now = \Carbon\Carbon::now();
        $myHours = Hour::where('worker_id', worker()->id);
        $thisweek = $myHours->clone()->where('work_day', '>=' , $now->clone()->startOfWeek()->format('Y-m-d'))->where('work_day', '<=' , $now->clone()->endOfWeek()->format('Y-m-d'))->sum('working_hours'). " h";
        $lastweek = $myHours->clone()->where('work_day', '>=' , $now->clone()->startOfWeek()->subDays(7)->format('Y-m-d'))->where('work_day', '<=' , $now->clone()->endOfWeek()->subDays(7)->format('Y-m-d'))->sum('working_hours'). " h";
        $startToEnd = __("Total ");
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
        
        if(request('start_date') && request('end_date')){
            $s_date = date('d.m', strtotime(request('start_date')));
            $e_date = date('d.m', strtotime(request('end_date')));
            $startToEnd = $s_date ." - ". $e_date;
        }
        $startToEndHr = $unpaginatedHours->clone()->sum('working_hours') . " h";
        $days = $unpaginatedHours->clone()->distinct('work_day')->count('work_day') . __(" days");
        return view('worker.hours.see-hours',compact('hours','edithour','thisweek','lastweek','startToEnd','startToEndHr', 'start_date', 'end_date', 'days'));
    }

}
