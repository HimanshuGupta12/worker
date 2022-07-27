<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sickness extends Model
{
    use HasFactory;

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
    
    public static $sicknessStatus = [
        0 => null,
        1 => 'Approved',
        2 => 'Not Approved',
        3 => 'Pending',
        4 => 'Delete request',
    ];
    
    public static function getLeastSicknessStatsOfWorkers($companyId, $workerIds, $workerFirstNames , $workerLastNames, $dateRange)
    {
        $query = self::where('company_id', $companyId)->selectRaw('worker_id, SUM(leave_duration) as total_leave_days');
        
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('date_from', '>=' , $dateRange['start_date'])->where('date_from', '<=' , $dateRange['end_date']);
        }
        $sickWorkers = $query->groupBy('worker_id')->orderBy('total_leave_days', 'DESC')->get()->toArray();
        
        $query = DB::table('hours')->where('company_id', $companyId)->selectRaw('worker_id, COUNT(distinct(work_day)) as total_working_days');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('work_day', '>=' , $dateRange['start_date'])->where('work_day', '<=' , $dateRange['end_date']);
        }
        $workerDays = $query->groupBy('worker_id')->orderBy('total_working_days', 'DESC')->get()->toArray();

        $data = [];
        foreach ($workerIds as $workerId) {
            $workerName = $workerFirstNames[$workerId].' '. $workerLastNames[$workerId];
            $stats = ['id'=>$workerId, 'name'=>$workerName, 'leaves'=> 0, 'working_days'=> 0, 'leave_ratio'=> 0,];
            foreach ($sickWorkers as $sk) {
                if ($sk['worker_id'] == $workerId) {
                    $stats['leaves'] = $sk['total_leave_days'];
                    break;
                }
            }
            foreach ($workerDays as $wd) {
                if ($wd->worker_id == $workerId) {
                    $stats['working_days'] = $wd->total_working_days;
                    break;
                }
            }
            $stats['leave_ratio'] = ($stats['working_days'] != 0) ? round( ($stats['leaves']/$stats['working_days'])*100, 2) : 'N/A';
            $data[] = $stats;
        }
        array_multisort( array_column($data, "working_days"), SORT_DESC, $data );
        return $data;
    }
    
}
