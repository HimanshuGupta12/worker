<?php

namespace App\Http\Controllers;
use App\Models\{Hour,Project,Worker,Tool,Sickness, Holiday};
use DateTime;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyDashboardController extends Controller
{
    public function index()
    {
        $user = user();
        $date = (isset($_GET['date']) && $_GET['date'] != 'Select date') ? $_GET['date'] : null;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
        
        $dateRange = getDateRangeFromDateOption();
        // Hours
        $hours = Hour::where('company_id', $user->company_id)->filter();
        $totalHours = $hours->sum('working_hours');
        $totalWorkersWhoSubmittedHours = $hours->distinct('worker_id')->count('worker_id');
        
        $invoicedHours = Hour::where('company_id', $user->company_id)->filter()->where('stamp_invoice', 1)->sum('working_hours');
        $nonInvoicedHours = Hour::where('company_id', $user->company_id)->filter()->where('stamp_invoice', 0)->sum('working_hours');
        
        // Hourly/Fixed rate hours 
        $hour_q1 = DB::table('hours')
            ->leftJoin('projects', 'hours.project_id', '=', 'projects.id')
            ->where('hours.company_id', $user->company_id)->whereNull('hours.deleted_at')
            ->where('projects.payment_type', 'hourly');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $hour_q1->where('hours.work_day', '>=' , $dateRange['start_date'])->where('hours.work_day', '<=' , $dateRange['end_date']);
        }
        $hourlyProjectHours = $hour_q1->sum('hours.working_hours');
        
        $hour_q2 = DB::table('hours')
            ->leftJoin('projects', 'hours.project_id', '=', 'projects.id')
            ->where('hours.company_id', $user->company_id)->whereNull('hours.deleted_at')
            ->where('projects.payment_type', 'fixed');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $hour_q2->where('hours.work_day', '>=' , $dateRange['start_date'])->where('hours.work_day', '<=' , $dateRange['end_date']);
        }
        $fixedProjectHours = $hour_q2->sum('hours.working_hours');
        
        $hour_q3 = DB::table('hours')
            ->leftJoin('projects', 'hours.project_id', '=', 'projects.id')
            ->where('hours.company_id', $user->company_id)->whereNull('hours.deleted_at')
            ->where('projects.payment_type', 'mixed');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $hour_q3->where('hours.work_day', '>=' , $dateRange['start_date'])->where('hours.work_day', '<=' , $dateRange['end_date']);
        }
        $mixedProjectHours = $hour_q3->sum('hours.working_hours');
        
        $topProjectsWithUsedVsGivenHours = Project::projectsListByUsedVsGivenHours($dateRange);
        
        //Projects
        $query_project_ids = Hour::where('company_id', $user->company_id);
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query_project_ids->where('work_day', '>=' , $dateRange['start_date'])->where('work_day', '<=' , $dateRange['end_date']);
        }
        $hourProjectIds = $query_project_ids->groupBy('project_id')->pluck('project_id')->toArray();
        /*Active projects are with status active plus those which have submitted hours in selected range*/
        $activeProjects = Project::where('company_id', $user->company_id)->where('status', '=', 'active')->whereIn('id', $hourProjectIds)->count();
        
        /*Completed projects are with status completed plus those which have submitted hours in selected range*/
        $completedProjects = Project::where('company_id', $user->company_id)->where('status', '=', 'completed')->whereIn('id', $hourProjectIds)->count();
        
        $busyProjects = Project::getBusyProjects($dateRange);
        
        //Workers
        $worker_q1 = $user->company->workers()->where('status', '=', 1);
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $worker_q1->where('created_at', '>=' , $dateRange['start_date'])->where('created_at', '<=' , $dateRange['end_date']);
        }
        $workers = $worker_q1->count();
        
        $worker_q2 = $user->company->workers();
        $workerIds = $worker_q2->pluck('id');
        
        $worker_q3 = $user->company->workers();
        $workerFirstNames = $worker_q3->pluck('first_name', 'id');
        
        $worker_q4 = $user->company->workers();
        $workerLastNames = $worker_q4->pluck('last_name', 'id');
        
        $workersPerformanceList = Worker::workers_performace_with_images($workerIds, $workerFirstNames, $workerLastNames, $dateRange);
        $worker_q5 = $user->company->holiday();
        //if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
        //    $worker_q5->where('holidays.date_from', '>=' , $dateRange['start_date'])->where('holidays.date_from', '<=' , $dateRange['end_date']);
        //} else {
        $worker_q5->where('holidays.date_from', '>=' , date('Y-m-d'));
        //}
        $holidays = $worker_q5->with(['worker'])->get()->toArray();
        
        
        $comingHolidays = Holiday::addUntillDaysInHolidays($holidays);
        
        $sickenessStats = Sickness::getLeastSicknessStatsOfWorkers($user->company_id, $workerIds, $workerFirstNames, $workerLastNames, $dateRange);
        $nonActiveWorkers = Worker::nonActiveWorkers($user->company_id, $dateRange);
        
        // Tools
        $tools_q1 = $user->company->tools();
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $tools_q1->where('created_at', '>=' , $dateRange['start_date'])->where('created_at', '<=' , $dateRange['end_date']);
        }
        $totalTools = $tools_q1->count();
                
        $tools_q2 = $user->company->tools()->where('possessor_type', 'App\Models\Worker');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $tools_q2->where('created_at', '>=' , $dateRange['start_date'])->where('created_at', '<=' , $dateRange['end_date']);
        }
        $toolsWithWorkers = $tools_q2->count('id');
        
        $tools_q3 = $user->company->tools()->where('possessor_type', 'App\Models\Storage');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $tools_q3->where('created_at', '>=' , $dateRange['start_date'])->where('created_at', '<=' , $dateRange['end_date']);
        }
        $toolsInStorages = $tools_q3->count('id');
        
        $tools_q4 = $user->company->tools();
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $tools_q4->where('created_at', '>=' , $dateRange['start_date'])->where('created_at', '<=' , $dateRange['end_date']);
        }
        $companyTools = $tools_q4->get();
        
        $topToolsInService = Tool::topToolsInService($dateRange);
        $topToolsWithWorkers = Tool::topToolsWithWorkers($dateRange);
        $unbalancedTools = Tool::getUnbalancedCountOfAllTools($companyTools);
        $newTools = Tool::getNewToolsCountAndPrice($dateRange);
        $daysUntillNextBalancing = Tool::daysUntillNextBalancing($dateRange);
        
        //Expenses
        $economicalData = Worker::getTotalExpenses($dateRange);
        
        return view('company.dashboard',compact('totalHours', 'invoicedHours', 'nonInvoicedHours', 'hourlyProjectHours', 'fixedProjectHours', 'mixedProjectHours', 'totalWorkersWhoSubmittedHours', 'topProjectsWithUsedVsGivenHours',
               'activeProjects', 'completedProjects', 'busyProjects', 'totalTools', 'toolsWithWorkers', 'toolsInStorages', 'topToolsInService', 'topToolsWithWorkers',
                'unbalancedTools', 'newTools', 'daysUntillNextBalancing', 'workersPerformanceList', 'comingHolidays', 'sickenessStats', 'nonActiveWorkers',
                'economicalData', 'workers', 'date', 'start_date', 'end_date'));
    }

}
