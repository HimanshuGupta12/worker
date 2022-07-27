<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class Worker extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $guarded = [];

    protected $casts = [
        'images' => 'string',
    ];

    public static $langs = [
        'en' => 'English',
        'lt' => 'Lithuanian',
        'ru' => 'Russian',
    ];

    // ------------------------------------------ relationships --------------------------------------------------------

    protected function allPositions(): Attribute
    {
        return new Attribute(
            get: function($value, $attributes){
                $p = $attributes['worker_position'];
                if(!$p) return collect([]);
                $l = explode(',', $p);
                $w1 = WorkerPosition::findMany($l);
                $w2 = WorkerPosition::whereIn('name', $l)->get();
                return $w1->merge($w2);
            }
        );
    }

    public function tools()
    {
        return $this->morphMany(Tool::class, 'possessor');
    }


    public function toolsNeedInventorization()
    {
        return $this->tools()->needInventorization();
    }

    public function toolsNeedInventorizationNotNotified()
    {
        return $this->tools()->needInventorization()->notNotified();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function hours()
    {
        return $this->hasMany(Hour::class);
    }

    public function sum_hours()
    {
        if (request('date')=== null) {
            // this month---------------
            $currentDate=date('y-m-d');
            $start_month = date("Y-n-j", strtotime("first day of this month"));
            $end_month = date("Y-n-j", strtotime($currentDate));
            return $this->hours()
                ->select(DB::raw('worker_id, sum(working_hours) as total_working_hours'))->where(function($query) use($start_month, $end_month) {
                    return $query->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
                })->groupBy('worker_id');
        } else {
            return $this->hours()
                ->select(DB::raw('worker_id, sum(working_hours) as total_working_hours'))->filter()->groupBy('worker_id');
        }
    }

    public static function sum_workers_late_time_hours($workerIds, $dateRange)
    {
        $query = user()->company->hour()
                ->select(DB::raw('worker_id, sum(late_submission_hours) as total_late_time'));
        
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('hours.work_day', '>=' , $dateRange['start_date'])->where('hours.work_day', '<=' , $dateRange['end_date']);
        }
        
        return $query->whereIn('hours.worker_id', $workerIds)->groupBy('worker_id')->get()->toArray();
    }
    
    public static function sum_comments($workerIds,$dateRange)
    {
        $query = user()->company->hour()
                ->select(DB::raw('worker_id, SUM(no_of_words_in_comments) as total_words_in_comments'));
        
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('hours.work_day', '>=' , $dateRange['start_date'])->where('hours.work_day', '<=' , $dateRange['end_date']);
        }
        
        return $query->whereIn('hours.worker_id', $workerIds)->groupBy('worker_id')->get()->toArray();
    }
    
    public static function sum_hours_images($workerIds,$dateRange)
    {
        $query = user()->company->hour()
                ->select(DB::raw('worker_id, SUM(no_of_images) as total_images'));
        
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('hours.work_day', '>=' , $dateRange['start_date'])->where('hours.work_day', '<=' , $dateRange['end_date']);
        }
        
        return $query->whereIn('hours.worker_id', $workerIds)->groupBy('worker_id')->get()->toArray();
    }
    
    public static function workers_performace_with_images($workerIds, $workerFirstNames, $workerLastNames, $dateRange)
    {
        $sumComments = self::sum_comments($workerIds, $dateRange);
        $sumImages = self::sum_hours_images($workerIds, $dateRange);
        $sumHours = self::sum_workers_late_time_hours($workerIds, $dateRange);
        $data = [];
        foreach ($workerIds as $workerId) {
            $totalImages = $totalComments = $totalLateTime = 0;
            foreach ($sumComments as $ct) {
                if ($ct['worker_id'] == $workerId) {
                    $totalComments = $ct['total_words_in_comments'];
                }
            }
            foreach ($sumImages as $ct) {
                if ($ct['worker_id'] == $workerId) {
                    $totalImages = $ct['total_images'];
                }
            }
            foreach ($sumHours as $hr) {
                if ($hr['worker_id'] == $workerId) {
                    $totalLateTime = $hr['total_late_time'];
                }
            }
            $workerName = $workerFirstNames[$workerId].' '. $workerLastNames[$workerId];
            $data[] = [
                'worker_id'=> $workerId, 'worker_name' => $workerName, 'total_images' => $totalImages,
                'late_submission_hours' => $totalLateTime, 'total_comments' => $totalComments,
                'average_sum'=> round(($totalImages+$totalLateTime+$totalComments)/3, 2)
            ];
        }
        array_multisort( array_column($data, "average_sum"), SORT_DESC, $data );
        return $data;
    }

    public function sickness()
    {
        return $this->belongsToMany(Sickness::class);
    }

    public function holiday()
    {
        return $this->belongsToMany(Holiday::class);

    }

    // ------------------------------------------- scopes --------------------------------------------------------------

    public function scopeFilter($q)
    {
        if (request('worker_name')) {
            $names = explode(' ',request('worker_name'));
            //$q->where('first_name','LIKE', "%$name%")->orWhere('last_name','LIKE', "%$name%");
            if (count($names) == 1) {
                $q->where(function($query) use ($names){
                        $query->where('first_name','LIKE', "%$names[0]%")->orWhere('last_name','LIKE', "%$names[0]%");
                    });
            } else {
                $q->where(function($query) use ($names){
                    foreach ($names as $name) {
                        $query->orWhere('first_name','LIKE', "%$name%")->orWhere('last_name','LIKE', "%$name%");
                    }
                });
            }
        }
        if (request('skills')) {
            $s = request('skills');
            $q->where('worker_position','LIKE', "%$s%");
        }

//        if (request('worker_role')) {
//            $role = request('worker_role');
//            if($role == "Storage inventorization"){
//                $q->where('inventory_storage',1);
//            }
//            if($role == "See company tools"){
//                $q->where('see_company_tools',1);
//            }
//            if($role == "Scan to storage"){
//                $q->where('scan_to_storage',1);
//            }
//            if($role == "Add new tools"){
//                $q->where('add_tool',1);
//            }
//        }

        if (request('worker_status') !== null) {
            $q->where('status', (bool)request('worker_status'));
        } else {
            $q->where('status', 1);//Show active workers in default.
        }

        if (request('date')=='Last week') {
            $previous_week = strtotime("-1 week +1 day");
            $start_week = strtotime("last sunday midnight",$previous_week);
            $end_week = strtotime("next saturday",$start_week);
            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);
            return $q->whereHas('hours', function( $query) use ($start_week, $end_week) {
                return $query->where('hours.work_day', '>=' , $start_week)->where('hours.work_day', '<=' , $end_week);
            });
        }
        elseif (request('date')=='Previous two weeks') {
            $previous_two_week = strtotime("-2 week +1 day");
            $start_two_week = strtotime("last sunday midnight",$previous_two_week);//1

            $previous_week = strtotime("-1 week +1 day");
            $start_week = strtotime("last sunday midnight",$previous_week);
            $end_week = strtotime("next saturday",$start_week);//2

            $start_two_week = date("Y-m-d",$start_two_week);
            $end_week = date("Y-m-d",$end_week);
            return $q->whereHas('hours', function( $query) use ($start_two_week, $end_week) {
                return $query->where('work_day', '>=' , $start_two_week)->where('work_day', '<=' , $end_week);
            });
        }
        elseif (request('date')=='This week') {
            $currentDate=date('y-m-d');
            $start_week = strtotime("last sunday midnight");
            $end_week = strtotime($currentDate);
            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);
            return $q->whereHas('hours', function( $query) use ($start_week, $end_week) {
                return $query->where('work_day', '>=' , $start_week)->where('work_day', '<=' , $end_week);
            });
        }
        elseif (request('date')=='Last and this week') {
            $currentDate=date('y-m-d');
            $previous_week = strtotime("-1 weeks +1 day");
            $start_week = strtotime("last sunday midnight",$previous_week);
            $end_week = strtotime($currentDate);
            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);
            return $q->whereHas('hours', function( $query) use ($start_week, $end_week) {
                return $query->where('work_day', '>=' , $start_week)->where('work_day', '<=' , $end_week);
            });
        }
        elseif (request('date')=='Last month') {
            $start_month = date("Y-n-j", strtotime("first day of previous month"));
            $end_month = date("Y-n-j", strtotime("last day of previous month"));
            return $q->whereHas('hours', function( $query) use ($start_month, $end_month) {
                return $query->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
            });
         }
        elseif (request('date')=='This month') {
            $currentDate=date('y-m-d');
            $start_month = date("Y-n-j", strtotime("first day of this month"));
            $end_month = date("Y-n-j", strtotime($currentDate));
            return $q->whereHas('hours', function( $query) use ($start_month, $end_month) {
                return $query->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
            });
        }
        // custom--------------
        elseif (request('date') == 'Custom' && !empty(request('start_date')) && !empty(request('end_date'))) {
            $start_date = request('start_date');
            $end_date = request('end_date');
            return $q->whereHas('hours', function( $query) use ($start_date, $end_date) {
                return $query->where('work_day', '>=' , $start_date)->where('work_day', '<=' , $end_date);
            });
        }
        elseif (request('date') == 'Select date') {
            // do nothing. Show all data.
        }
        // Show this month as default.
        else {
            // this month---------------
//            $currentDate=date('y-m-d');
//            $start_month = date("Y-n-j", strtotime("first day of this month"));
//            $end_month = date("Y-n-j", strtotime($currentDate));
//            return $q->whereHas('hours', function( $query) use ($start_month, $end_month) {
//                return $query->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
//            });
        }
        // if (request('date')=='Last week') {
        //     // Last week---------------
        //     $previous_week = strtotime("-1 week +1 day");
        //     $start_week = strtotime("last sunday midnight",$previous_week);
        //     $end_week = strtotime("next saturday",$start_week);
        //     $start_week = date("Y-m-d",$start_week);
        //     $end_week = date("Y-m-d",$end_week);
        //     // return $start_week.' '.$end_week ;
        //     $q->where('start_date', '>=' , $start_week)->where('start_date', '<=' , $end_week);
        // }

        // if (request('date')=='Previous two weeks') {
        //     // Previous two weeks---------------
        //     $previous_two_week = strtotime("-2 week +1 day");
        //     $start_two_week = strtotime("last sunday midnight",$previous_two_week);//1

        //     $previous_week = strtotime("-1 week +1 day");
        //     $start_week = strtotime("last sunday midnight",$previous_week);
        //     $end_week = strtotime("next saturday",$start_week);//2

        //     $start_two_week = date("Y-m-d",$start_two_week);
        //     $end_week = date("Y-m-d",$end_week);
        //     //echo $start_two_week.' '.$end_week ;exit;
        //     $q->where('start_date', '>=' , $start_two_week)->where('start_date', '<=' , $end_week);
        // }

        // if (request('date')=='This week') {
        //     // This week---------------
        //     $currentDate=date('y-m-d');
        //     $start_week = strtotime("last sunday midnight");
        //     $end_week = strtotime($currentDate);
        //     $start_week = date("Y-m-d",$start_week);
        //     $end_week = date("Y-m-d",$end_week);
        //     // return $start_week.' '.$end_week ;
        //     $q->where('start_date', '>=' , $start_week)->where('start_date', '<=' , $end_week);
        // }

        // if (request('date')=='Last and this week') {
        //     //  Last and this weeks---------------
        //     $currentDate=date('y-m-d');
        //     $previous_week = strtotime("-1 weeks +1 day");
        //     $start_week = strtotime("last sunday midnight",$previous_week);
        //     $end_week = strtotime($currentDate);
        //     $start_week = date("Y-m-d",$start_week);
        //     $end_week = date("Y-m-d",$end_week);
        //     // return $start_week.' '.$end_week ;
        //     $q->where('start_date', '>=' , $start_week)->where('start_date', '<=' , $end_week);
        // }

        // if (request('date')=='Last month') {
        //     // Last month---------------
        //     $start_month = date("Y-n-j", strtotime("first day of previous month"));
        //     $end_month = date("Y-n-j", strtotime("last day of previous month"));
        //     // return $start_month.' '.$end_month ;
        //     $q->where('start_date', '>=' , $start_month)->where('start_date', '<=' , $end_month);
        //  }

        // if (request('date')=='This month') {
        //     // this month---------------
        //     $currentDate=date('y-m-d');
        //     $start_month = date("Y-n-j", strtotime("first day of this month"));
        //     $end_month = date("Y-n-j", strtotime($currentDate));
        //     // return $start_month.' '.$end_month ;
        //     $q->where('start_date', '>=' , $start_month)->where('start_date', '<=' , $end_month);
        // }

        // // custom--------------
        // if (request('date') == 'Custom' && !empty(request('start_date')) && !empty(request('end_date'))) {
        //     $start_date = request('start_date');
        //     $end_date = request('end_date');
        //     // return $start_month.' '.$end_month ;
        //     $q->where('start_date', '>=' , $start_date)->where('start_date', '<=' , $end_date);
        // }
    }
    /* Not used */
    public static function workerStats($workers, $date, $start_date, $end_date)
    {
        $stats = [];
        $total_working_hours = 0;
        //dd($date, $start_date, $end_date);
        foreach ($workers as $worker) {
            $q = user()->company->workers()->where('id', $worker->id);
            $tools = Tool::where('possessor_type', 'App\Models\Worker')->where('possessor_id', $worker->id);
            //$not_balanced = $tools->where('next_inventorization_at', '<=', today())->count();
            //$balanced = $tools->where('next_inventorization_at', '>', today())->count();

            if ($date=='Last week') {
                $previous_week = strtotime("-1 week +1 day");
                $start_week = strtotime("last sunday midnight",$previous_week);
                $end_week = strtotime("next saturday",$start_week);
                $start_week = date("Y-m-d",$start_week);
                $end_week = date("Y-m-d",$end_week);
                return $q->whereHas('hours', function( $query) use ($start_week, $end_week) {
                    return $query->where('hours.work_day', '>=' , $start_week)->where('hours.work_day', '<=' , $end_week);
                });
            }
            elseif ($date=='Previous two weeks') {
                $previous_two_week = strtotime("-2 week +1 day");
                $start_two_week = strtotime("last sunday midnight",$previous_two_week);//1

                $previous_week = strtotime("-1 week +1 day");
                $start_week = strtotime("last sunday midnight",$previous_week);
                $end_week = strtotime("next saturday",$start_week);//2

                $start_two_week = date("Y-m-d",$start_two_week);
                $end_week = date("Y-m-d",$end_week);
                return $q->whereHas('hours', function( $query) use ($start_two_week, $end_week) {
                    return $query->where('work_day', '>=' , $start_two_week)->where('work_day', '<=' , $end_week);
                });
            }
            elseif ($date=='This week') {
                $currentDate=date('y-m-d');
                $start_week = strtotime("last sunday midnight");
                $end_week = strtotime($currentDate);
                $start_week = date("Y-m-d",$start_week);
                $end_week = date("Y-m-d",$end_week);
                return $q->whereHas('hours', function( $query) use ($start_week, $end_week) {
                    return $query->where('work_day', '>=' , $start_week)->where('work_day', '<=' , $end_week);
                });
            }
            elseif ($date=='Last and this week') {
                $currentDate=date('y-m-d');
                $previous_week = strtotime("-1 weeks +1 day");
                $start_week = strtotime("last sunday midnight",$previous_week);
                $end_week = strtotime($currentDate);
                $start_week = date("Y-m-d",$start_week);
                $end_week = date("Y-m-d",$end_week);
                return $q->whereHas('hours', function( $query) use ($start_week, $end_week) {
                    return $query->where('work_day', '>=' , $start_week)->where('work_day', '<=' , $end_week);
                });
            }
            elseif ($date=='Last month') {
                $start_month = date("Y-n-j", strtotime("first day of previous month"));
                $end_month = date("Y-n-j", strtotime("last day of previous month"));
                return $q->whereHas('hours', function( $query) use ($start_month, $end_month) {
                    return $query->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
                });
            }
            elseif ($date=='This month') {
                $currentDate=date('y-m-d');
                $start_month = date("Y-n-j", strtotime("first day of this month"));
                $end_month = date("Y-n-j", strtotime($currentDate));
                $total_working_hours = $q->whereHas('hours', function( $query) use ($start_month, $end_month) {
                    return $query->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month)->sum('working_hours');;
                });
            }
            // custom--------------
            elseif ($date == 'Custom' && !empty($start_date) && !empty($end_date)) {
                return $q->whereHas('hours', function( $query) use ($start_date, $end_date) {
                    return $query->where('work_day', '>=' , $start_date)->where('work_day', '<=' , $end_date);
                });
            }elseif ($date == 'Select date') {
                // do nothing. Show all data.
            }
            // Show this month as default.
            else {
                // this month---------------
                $currentDate=date('y-m-d');
                $start_month = date("Y-n-j", strtotime("first day of this month"));
                $end_month = date("Y-n-j", strtotime($currentDate));
                $q->whereHas('hours', function( $query) use ($start_month, $end_month) {
                    return $query->where('work_day', '>=' , $start_month)->where('work_day', '<=' , $end_month);
                });
            }

            $data = [/*'tools_count' => $tools->count(), 'not_balanced' => $not_balanced, 'balanced' => $balanced ,*/ 'total_working_hours' => $total_working_hours];
            $stats[$worker->id] = $data;
        }
        dd($stats);
        return $stats;
    }

    public function scopeOrderDefault($q)
    {
        $q->orderBy('first_name');
    }


    // --------------------------------------- methods -----------------------------------------------------------------

    public function findTool(string $qr_code): Tool
    {
        $company_tool = $this->company->findTool($qr_code);
        // not an owner
        if (!$company_tool->possessor) {
            throw ValidationException::withMessages(['This tool is not assigned to anyone']);
        }
        // already owner
        if (!$company_tool->possessor->is($this)) {
            throw ValidationException::withMessages(['This tool is assigned to "' . $company_tool->possessor->possessorName() . '"']);
        }

        return $this->tools()->find($company_tool->id);
    }

    public static function setWorkerLoginCookie($worker_login_id) {
        $seconds = time()+31536000; //60*60*24*365=31536000 (~1 year).
        setcookie('login', $worker_login_id, $seconds);
    }
    
    public static function getWorkerLoginCookie(){
        return $_COOKIE['login'];
    }
    
    public static function login(int $worker_id)
    {
        session()->put('worker_id', $worker_id);
    }

    public static function isLoggedIn(): bool
    {
        return session()->has('worker_id');
    }

    public static function loggedIn(): self
    {
        $id = session()->get('worker_id');
        $worker = Worker::find($id);
        //setcookie('login', 'test', -36000); // The purpose is to delete old cookie before setting a new one.
        if (!isset($_COOKIE["login"])) {
            self::setWorkerLoginCookie($worker->login);//Set worker hash login in cookie.
        }

        return $worker;
    }
    
    public static function addLoginActivity($worker_id)
    {
        self::where('id', $worker_id)->update(['last_login_at' => now(), 'login_counter'=> DB::raw('login_counter+1')]);
    }

    public function workerLink()
    {
        return route('worker.login', $this->login);
    }

    public function possessorName(): string
    {
        return 'Worker: ' . $this->fullName();
    }

    public function fullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function phone(): string|null
    {
        if (!$this->phone_country || !$this->phone_number) {
            return null;
        }

        return '+' . $this->phone_country . $this->phone_number;
    }

    public function requestInventorization(string $sms_message = null)
    {
        Tool::inventoryWorker($this, $sms_message);
    }
    //-----------profile picture ---------------

    public function profilePicture($image_path)
    {
        $files = $this->images;
            //\Image::make($image_path)->fit(700, 700)->orientate()->stream(null, 75);
            \Image::make($image_path)->fit(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(null, 95);
            $files = $image_path->store($this->company->id . '/workerPictures');
        $this->images = $files;
        $this->save();
    }
    public function updatedProfilePicture($image_path)
    {
        \Image::make($image_path)->fit(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(null, 95);
        $files = $image_path->store($this->company->id . '/workerUpdatedPictures');

        $this->images = $files;
        $this->save();
    }

    public function deleteImage(string $image_name)
    {
        \Illuminate\Support\Facades\Storage::delete($image_name);
        $this->images = null;
        $this->save();
    }
    
    public static function getCompanyWorkersMonthlyHours ($dateRange)
    {
        $query = DB::table('hours')->selectRaw('hours.worker_id, SUM(hours.working_hours) as total_hours')
            ->where('hours.company_id', user()->company_id);
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('hours.work_day', '>=' , $dateRange['start_date'])->where('hours.work_day', '<=' , $dateRange['end_date']);
        }
            
        $hours = $query->groupBy('hours.worker_id')->get()->toArray();
        return $hours;
    }
    
    public static function companyWorkersMonhlyExpenses ($hours)
    {
        $workerIds = [];
        foreach ($hours as $hour) {
            $workerIds[] = $hour->worker_id;
        }
        $workersSpecs = DB::table('workers')->whereIn('id', $workerIds)->select('id','worker_salary','worker_cost')->get()->toArray();
        $salaries = [];
        foreach ($hours as $hour) {
            foreach ($workersSpecs as $spec) {
                if ($hour->worker_id == $spec->id) {
                    $salaries[] = ['worker_id' => $hour->worker_id, 'salary' => $hour->total_hours*$spec->worker_salary, 'cost' => $hour->total_hours*$spec->worker_cost];
                }
            }
        }
        return $salaries;
    }
    
    public static function getTotalExpenses ($dateRange)
    {
        $companyWorkersHours = self::getCompanyWorkersMonthlyHours($dateRange);
        $companyWorkersExpenses = self::companyWorkersMonhlyExpenses($companyWorkersHours);
        
        $data = ['workers_salary_expense' => 0, 'workers_overhead_expense' => 0, 'workers_cost_expense' => 0];
        $data['tool_expenses'] = Tool::toolsExpenses($dateRange);
        foreach ($companyWorkersExpenses as $exp) {
            $data['workers_salary_expense'] += $exp['salary'];
            $data['workers_cost_expense'] += $exp['cost'];
        }
        $data['workers_overhead_expense'] = $data['workers_cost_expense'] - $data['workers_salary_expense'];
        $data['total'] = $data['workers_salary_expense'] + $data['workers_overhead_expense'] + $data['tool_expenses'];
        return $data;
    }
    
    public static function nonActiveWorkers ($companyId, $dateRange)
    {
        /* No activity in last 6 months. */
        $query = self::where('company_id', $companyId)->select('id', 'first_name', 'last_name', 'last_login_at', 'login_counter');
//        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
//            $query->where('last_login_at', '>=' , $dateRange['end_date']);
//        }
        $query->where("last_login_at","<", Carbon::now()->subMonths(1));
        $workers = $query->groupBy('id')->orderBy('last_login_at', 'ASC')->get()->toArray();
        //dd($workers);
        return $workers;
    }
    
    public static function activeWorkers ($companyId)
    {
        $dateRange = getDateRangeFromDateOption();
        
        $query = DB::table('workers')->where('workers.company_id', $companyId)->join('hours', 'workers.id', '=', 'hours.worker_id')/*->where("hours.work_day",">", Carbon::now()->subMonths(12))*/
                ->select('workers.id','workers.first_name','workers.last_name');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('hours.work_day', '>=' , $dateRange['start_date'])->where('hours.work_day', '<=' , $dateRange['end_date']);
        }
        return $workers = $query->distinct()->get()->toArray();
    }
}
