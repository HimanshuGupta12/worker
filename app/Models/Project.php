<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public static $rules = [
        'name' => 'required|string|max:255',
        'address' => 'nullable|string|max:1023',
        'city' => 'nullable|string|max:255',
        'country' => 'nullable',
        'postcode' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'nullable|date',
        'shift_start' => 'nullable',
        'shift_end' => 'nullable',
        'manager_id' => 'nullable|integer',
        'client_id' => 'nullable|integer',
        'break_time' => 'nullable|integer',
        'payment_type' => 'nullable|string',
        'hourly_rate' => 'nullable|numeric',
        'fixed_rate' => 'nullable|numeric',
        'total_hours' => 'nullable|numeric',
        'allow_comments' => 'nullable|boolean',
        'allow_photos' => 'nullable|boolean',
        'add_client' => 'nullable|boolean',
        'add_economical_details' => 'nullable|boolean',

    ];
    public static $rules_with_shift_hours = [
        'name' => 'required|string|max:255',
        'address' => 'nullable|string|max:1023',
        'city' => 'nullable|string|max:255',
        'postcode' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'nullable|date',
        'shift_start' => 'date_format:H:i',
        'shift_end' => 'date_format:H:i|after:shift_start',
        'manager_id' => 'nullable|integer',
        'client_id' => 'nullable|integer',
        'break_time' => 'nullable|integer',
        'payment_type' => 'nullable|string',
        'hourly_rate' => 'nullable|numeric',
        'fixed_rate' => 'nullable|numeric',
        'total_hours' => 'nullable|numeric',
        'allow_comments' => 'nullable|boolean',
        'allow_photos' => 'nullable|boolean',
        'add_client' => 'nullable|boolean',
        'add_economical_details' => 'nullable|boolean',

    ];
    
    public static $statusRules = [
        'status' => 'required|in:active,completed',
    ];

    public function workers()
    {
        return $this->belongsToMany(Worker::class);
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function manager()
    {
        return $this->belongsTo(Worker::class, 'manager_id');
    }    
    public function hours()
    {
        return $this->belongsToMany(Hour::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function scopeOrderName($q)
    {
        $q->orderBy('name');
    }
    
    public function nameAndNumber(): string
    {
        return $this->name . ' (#' . $this->company_project_id . ')';
    }
    
    public function scopeFilter($q)
    {
        if (request('status') !== null) {
            $q->where('status',request('status'));
        } else {
            //By-default show only active projects.
            $q->where('status', 'active');
        }
        if (request('q') && !empty(request('q'))) {
            $s = request('q');
            if (is_numeric($s)) {
                $q->where('company_project_id', $s);
            } else {
                $q->where(function ($q) use ($s) {
                    $q->orWhere('name', 'LIKE', "%$s%");//->orWhere('model', 'LIKE', "%$s%");
                });
            }
        }
    }
    
    public static function calculateWorkDuration ($start_time, $end_time, $break_time = 0)
    {
        $startArr = explode(":", $start_time);
        $finishArr = explode(":", $end_time);
        $startMins = ($startArr[0]*60) + $startArr[1];
        $finishMins = ($finishArr[0]*60) + $finishArr[1];
        $diffMins = $finishMins - $startMins - $break_time;

        $worked_hours = floor($diffMins/60); // hours
        $worked_mins = $diffMins - ($worked_hours*60); // mins

        return sprintf("%02d",$worked_hours) .':'. sprintf("%02d",$worked_mins);
    }
    
    public static function getBusyProjects ($dateRange)
    {
        $query = DB::table('hours')->selectRaw('project_id, SUM(working_hours) as total_hours')
                ->where('company_id', user()->company_id)->whereNull('deleted_at');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('work_day', '>=' , $dateRange['start_date'])->where('work_day', '<=' , $dateRange['end_date']);
        }
        $projectHours = $query->groupBy('project_id')->orderBy('total_hours', 'DESC')->get()->toArray();
        $projectIds = [];
        foreach ($projectHours as $project) {
            $projectIds[] = $project->project_id;
        }
        $projectNames = self::whereIn('id', $projectIds)->get();
        $projectWorkers = DB::table('hours')->leftJoin('workers', 'hours.worker_id', '=', 'workers.id')
                ->selectRaw('project_id, count(distinct(hours.worker_id)) as total_workers')
            ->whereIn('hours.project_id', $projectIds)->groupBy('project_id')->get()->toArray();
        $data = [];
        foreach ($projectNames as $proj) {
            $totalWorkers = 0;
            $totalHours = 0;
            foreach ($projectHours as $project) {
                if ($proj->id == $project->project_id) {
                    $totalHours = $project->total_hours;
                    break;
                }
            }
            foreach ($projectWorkers as $project) {
                if ($proj->id == $project->project_id) {
                    $totalWorkers = $project->total_workers;
                    break;
                }
            }
            $data[] = ['id' => $proj->id, 'name' => $proj->name, 'totalWorkers' => $totalWorkers, 'totalHours' => $totalHours];
        }
        array_multisort( array_column($data, "totalHours"), SORT_DESC, $data );
        return $data;
    }
    
    public static function projectsListByUsedVsGivenHours ($dateRange)
    {
        $query = DB::table('hours')->selectRaw('project_id, SUM(working_hours) as used_hours')
                ->where('company_id', user()->company_id)->whereNull('deleted_at');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('work_day', '>=' , $dateRange['start_date'])->where('work_day', '<=' , $dateRange['end_date']);
        }
        $projectHours = $query->groupBy('project_id')->orderBy('used_hours', 'DESC')->get()->toArray();
        
        $projectIds = [];
        foreach ($projectHours as $project) {
            $projectIds[] = $project->project_id;
        }
        $projectNames = self::whereIn('id', $projectIds)->select('id', 'name', 'company_project_id', 'total_hours')->get();
        $data = [];
        foreach ($projectNames as $proj) {
            $givenHours = 0;
            $usedHours = 0;
            $percent = 0;
            foreach ($projectHours as $project) {
                if ($proj->id == $project->project_id) {
                    $givenHours = $proj->total_hours;
                    $usedHours = $project->used_hours;
                    $percent = ($givenHours > 0) ? round(($usedHours/$givenHours)*100, 2) : 0;
                    break;
                }
            }
            
            $data[] = ['id' => $proj->id, 'company_project_id' => $proj->company_project_id, 'name' => $proj->nameAndNumber(), 'used_hours' => $usedHours, 'given_hours' => $givenHours, 'used_percent'=> $percent];
        }
        array_multisort( array_column($data, "used_percent"), SORT_DESC, $data );
        return $data;
    }
}
