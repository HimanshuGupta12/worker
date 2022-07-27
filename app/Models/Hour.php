<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hour extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
    ];

    public static $rules = [
        'project_id' => 'required',
        'work_day' => 'required',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'lunch_break' => 'string',
        'break_time' => 'nullable',
        'comments' => 'nullable',
        'images' => 'array',
        'images.*' => 'image|mimes:jpg,png|max:20000|dimensions:max_width=5000,max_height=5000',
        'late_submission_reason' => 'nullable',
        'late_submission_hours' => 'nullable'
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function hours()
    {
        return $this->all();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function addImages(array $image_paths, $company_id)
    {
        // $max_images = 5;
        // if ((count((array)$this->images) + count($image_paths)) > $max_images) {
        //     throw ValidationException::withMessages(['Tool can have up to 5 images']);
        // }

        $files = $this->images;
        foreach ($image_paths as $image) {
            //\Image::make($image)->resize(700,700)->orientate()->stream(null, 75);
            \Image::make($image)->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(null, 95);
            $files[] = $image->store($company_id . '/worker/hours');
        }
        $this->images = $files;
        $this->save();
    }
    
    public function addCompressedImages(array $image_paths, $company_id)
    {
        $files = $this->images;
        foreach ($image_paths as $image) {
            \Image::make($image)->save(null, 95);
            $files[] = $image->store($company_id . '/worker/hours');
        }
        $this->images = $files;
        $this->save();
    }
    
    public static function calculateHours ($start_time, $end_time, $break_time)
    {
        $startArr = explode(":", $start_time);
        $endArr = explode(":", $end_time);
        
        $startingMins = ($startArr[0]*60) + $startArr[1];
        $endingMins = ($endArr[0]*60) + $endArr[1];
        
        return ($endingMins-$startingMins-$break_time)/60;
    }
    /**
     * 
     * @param type $end_time
     * @param type $work_day
     * @return type : No. of hours the worker logs after end time.
     */
    public static function lateSubmissionHours_bk($end_time, $work_day)
    {
        $diff = date_diff(date_create($work_day), date_create(date('Y-m-d')));
        $days = $diff->format("%a");
        $end_date_time = date("Y-m-d").' '.$end_time.':00';
        $hours = (time()- strtotime($end_date_time))/3600;
        
        return ($days*24)+$hours;
    }
    
    public static function lateSubmissionHours($end_time, $work_day, $created_at = null)
    {
        $end_time_arr = explode(":", $end_time);
        $end_time = count($end_time_arr) == 2 ? $end_time.':00' : $end_time;
        if (empty($created_at)) {// New entry.
            $created_at = date('Y-m-d H:i:s');
            $end_date_time = date("Y-m-d").' '.$end_time;
        } else {// Old entry.
            $created_at = date('Y-m-d H:i:s', strtotime($created_at));
            $created_at_date = explode(' ', $created_at);
            $end_date_time = $created_at_date[0].' '.$end_time;
        }
        $diff = date_diff(date_create($work_day), date_create(date('Y-m-d', strtotime($created_at))));
        $days = $diff->format("%a");
        
        $hours = (strtotime($created_at)- strtotime($end_date_time))/3600;
        
        return ($days*24)+round($hours, 2);
    }
    
    public static function calculateTotalWorkerHours ($workerId)
    {
        $total_hours = self::where('worker_id', $workerId)->sum('working_hours');
        $whole = floor($total_hours);
        $fraction = $total_hours - $whole;
        
        return $whole.':'.round($fraction*100*0.6);
    }
    
    public static function calculateHoursStats ($hours)
    {
        $dateRange = getDateRangeFromDateOption();
        if (count($hours) > 0) {
            $stats = array(
                'worker' => ['min_date' => $hours[0]->work_day, 'max_date' => $hours[0]->work_day, 'work_days'=>[], 'members'=>[], 'total_hours'=>0],
                'project' => ['min_date' => $hours[0]->work_day, 'max_date' => $hours[0]->work_day, 'work_days'=>[], 'members'=>[], 'total_hours'=>0]
            );
        } else {
            return $stats = array(
                'worker' => ['min_date' => null, 'max_date' => null, 'work_days'=>null, 'members'=>[], 'total_hours'=>null],
                'project' => ['min_date' => null, 'max_date' => null, 'work_days'=>null, 'members'=>[], 'total_hours'=>null]
            );
        }
        /*#### According to new requirement, min & max dates will be from selected filter & not from work_day ####*/
        $stats['worker']['min_date'] = $dateRange['start_date'];
        $stats['project']['min_date'] = $dateRange['start_date'];
        $stats['worker']['max_date'] = $dateRange['end_date'];
        $stats['project']['max_date'] = $dateRange['end_date'];
        
        foreach ($hours as $key => $hr) {
            /*#### Calculate total working hours ###*/
            $stats['worker']['total_hours'] += $hr->working_hours;
            $stats['project']['total_hours'] += $hr->working_hours;
            
            /*#### Calculate Min work date ###*/
            /*
            if ($hr->work_day < $stats['worker']['min_date']) {
                $stats['worker']['min_date'] = $hr->work_day;
            }
            if ($hr->work_day < $stats['project']['min_date']) {
                $stats['project']['min_date'] = $hr->work_day;
            }
            */
            
            /*#### Calculate Max work date ###*/
            /*
            if ($hr->work_day > $stats['worker']['max_date']) {
                $stats['worker']['max_date'] = $hr->work_day;
            }
            if ($hr->work_day > $stats['project']['max_date']) {
                $stats['project']['max_date'] = $hr->work_day;
            }
             */
            
            /*#### Calculate total working days ###*/
            if (!in_array($hr->work_day, $stats['worker']['work_days'])){
                $stats['worker']['work_days'][] = $hr->work_day;
            }
            if (!in_array($hr->work_day, $stats['project']['work_days'])){
                $stats['project']['work_days'][] = $hr->work_day;
            }
            
            /*#### Calculate total worker or project members ###*/
            if (!in_array($hr->worker_id, $stats['worker']['members'])){
                $stats['worker']['members'][] = $hr->worker_id;
            }
            if (!in_array($hr->project_id, $stats['project']['members'])){
                $stats['project']['members'][] = $hr->project_id;
            }
        }
        return $stats;
    }

    public function scopeFilter($q)
    {
        if (request('worker') && trim(request('worker')) != 'Select worker') {
            $q->where('worker_id', request('worker'));
        }

        if (request('project') && trim(request('project')) != 'Select project') {
            $q->where('project_id', request('project'));
        }
        
        if (request('date')=='Last week') {
            $previous_week = strtotime("-1 week +1 day");
            $start_week = strtotime("last sunday midnight",$previous_week);
            $end_week = strtotime("next saturday",$start_week);
            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);
            // return $start_week.' '.$end_week ;
            $q->where('work_day', '>=' , $start_week)->where('work_day', '<=' , $end_week);
        }
        
        elseif (request('date')=='Previous two weeks') {
            $previous_two_week = strtotime("-2 week +1 day");
            $start_two_week = strtotime("last sunday midnight",$previous_two_week);//1
            
            $previous_week = strtotime("-1 week +1 day");
            $start_week = strtotime("last sunday midnight",$previous_week);
            $end_week = strtotime("next saturday",$start_week);//2
            
            $start_two_week = date("Y-m-d",$start_two_week);
            $end_week = date("Y-m-d",$end_week);
            //echo $start_two_week.' '.$end_week ;exit;
            $q->where('work_day', '>=' , $start_two_week)->where('work_day', '<=' , $end_week);
        }

        elseif (request('date')=='This week') {
            $currentDate=date('y-m-d');
            $start_week = strtotime("last sunday midnight");
            $end_week = strtotime($currentDate);
            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);
            // return $start_week.' '.$end_week ;
            $q->where('work_day', '>=' , $start_week)->where('work_day', '<=' , $end_week);
        }

        elseif (request('date')=='Last and this week') {
            $currentDate=date('y-m-d');
            $previous_week = strtotime("-1 weeks +1 day");
            $start_week = strtotime("last sunday midnight",$previous_week);
            $end_week = strtotime($currentDate);
            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);
            // return $start_week.' '.$end_week ;
            $q->where('work_day', '>=' , $start_week)->where('work_day', '<=' , $end_week);
        }

        elseif (request('date')=='Last month') {
            $start_month = date("Y-n-j", strtotime("first day of previous month"));
            $end_month = date("Y-n-j", strtotime("last day of previous month"));
            // return $start_month.' '.$end_month ;
            $q->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
         }

        elseif (request('date')=='This month') {
            $currentDate=date('y-m-d');
            $start_month = date("Y-n-j", strtotime("first day of this month"));
            $end_month = date("Y-n-j", strtotime($currentDate));
            // return $start_month.' '.$end_month ;
            $q->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
        }
        
        elseif (request('date')=='Last three months') {
            $start_month = date("Y-n-j", strtotime("-3 Months"));
            $currentDate=date('y-m-d');
            $end_month = date("Y-n-j", strtotime($currentDate));
            //echo $start_month.' '.$end_month ;
            $q->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
        }
        
        elseif (request('date')=='Last six months') {
            $start_month = date("Y-n-j", strtotime("-6 Months"));
            $currentDate=date('y-m-d');
            $end_month = date("Y-n-j", strtotime($currentDate));
            //echo $start_month.' '.$end_month ;
            $q->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
        }
        
        elseif (request('date')=='This year') {
            $start_month = date("Y-n-j", strtotime("first day of january this year"));
            $currentDate=date('y-m-d');
            $end_month = date("Y-n-j", strtotime($currentDate));
            //echo $start_month.' '.$end_month ;
            $q->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
        }
        
        elseif (request('date')=='Last year') {
            $start_month = date("Y-n-j", strtotime("first day of january last year"));
            $end_month = date("Y-n-j", strtotime("last day of december last year"));
            //echo $start_month.' '.$end_month ;exit;
            $q->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
        }
        
        elseif (request('date')=='Last twelve months') {
            $start_month = date("Y-n-j", strtotime("-12 Months"));
            $currentDate=date('y-m-d');
            $end_month = date("Y-n-j", strtotime($currentDate));
            //echo $start_month.' '.$end_month ;
            $q->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
        }
        // custom--------------
        elseif (request('date') == 'Custom' && !empty(request('start_date')) && !empty(request('end_date'))) {
            $start_date = request('start_date');
            $end_date = request('end_date');
            // return $start_month.' '.$end_month ;
            $q->where('work_day', '>=' , $start_date)->where('work_day', '<=' , $end_date);
        }
        
        // custom for see hours--------------
        elseif (request('start_date') && request('end_date')) {
            $start_date = date('Y-m-d', strtotime(request('start_date')));
            $end_date = date('Y-m-d', strtotime(request('end_date')));
            // return $start_month.' '.$end_month ;
            $q->where('work_day', '>=' , $start_date)->where('work_day', '<=' , $end_date);
        }
        
        if (request('stamp_invoice') === '1' || request('stamp_invoice') === '0') {
            $q->where('stamp_invoice', (bool)request('stamp_invoice'));
        }
    }
    
    public function deleteImage(string $image_name)
    {
        if (!in_array($image_name, $this->images)) {
            throw new \Exception('no image ' . $image_name);
        }

        \Illuminate\Support\Facades\Storage::delete($image_name);
        $images = $this->images;
        foreach ($images as $k => $image) {
            if ($image === $image_name) {
                break;
            }
        }
        unset($images[$k]);
        $this->images = array_values($images);
        $this->save();
    }
    
    public static function decodeHourId($hour_id)
    {
        $hashids = new \Hashids\Hashids('yXoXZlnXO3Rohvi6Xi9l', 10);
        $decoded = $hashids->decode($hour_id);
        if (!empty($decoded)) {
            return $decoded[0]; // was encoded tool id
        }

        return null;
    }

    public static function encodeHourId(int $hour_id)
    {
        $hashids = new \Hashids\Hashids('yXoXZlnXO3Rohvi6Xi9l', 10);
        return $hashids->encode($hour_id);
    }

}
