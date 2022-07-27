<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Models\Hour;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = user()->company->projects()->filter()->orderBy('created_at','DESC')->paginate(25);
        $filteredProjectsCount = user()->company->projects()->filter()->count();
        $company_id = user()->company_id;
        $projectUsedHoursCollection = DB::select("SELECT project_id, SUM(working_hours) as used_hours FROM hours where company_id = $company_id && deleted_at is null Group By project_id");
        $projectUsedHours = Arr::pluck($projectUsedHoursCollection, 'used_hours', 'project_id');

        $projectInvoicedHoursCollection = DB::select("SELECT project_id, SUM(working_hours) as invoiced_hours FROM hours where stamp_invoice = 1 and company_id = $company_id and deleted_at is null Group By project_id");
        $projectInvoicedHours = Arr::pluck($projectInvoicedHoursCollection, 'invoiced_hours', 'project_id');

        $projectStatus = isset($_GET['status']) ? $_GET['status'] : 'active';
        $filterText = ucfirst($projectStatus);
        $q = isset($_GET['q']) ? $_GET['q'] : null;

        $hoursSumQuery = DB::table('hours')->leftJoin('projects', 'projects.id', '=', 'hours.project_id')
            ->where('projects.status', $projectStatus)->where('hours.company_id', $company_id);

        if (request('q') && !empty(request('q'))) {
            $s = request('q');
            if (is_numeric($s)) {
                $hoursSumQuery->where('projects.id', $s);
            } else {
                $hoursSumQuery->where(function ($query) use ($s) {
                    $query->orWhere('projects.name', 'LIKE', "%$s%");//->orWhere('model', 'LIKE', "%$s%");
                });
            }
        }
        $filteredProjectHours = $hoursSumQuery->sum('working_hours');

        return view('projects.index', compact('projects', 'filteredProjectsCount', 'filteredProjectHours', 'projectUsedHours', 'projectInvoicedHours',
                'projectStatus', 'filterText', 'q'));
    }

    public function create()
    {
        $page = 'create';
        $url = route('projects.store');
        $project = new Project();
        $client = new Client();
        $workers = user()->company->workers()->orderDefault()->get();
        $clients = user()->company->clients()->orderDefault()->get();
        $day_duration = $worked_duration = null;
        return view('projects.create_or_edit', compact('project', 'workers', 'clients', 'page', 'url', 'client', 'day_duration', 'worked_duration'));
    }

    public function store()
    {
        if (!empty(request('shift_start')) || !empty(request('shift_end'))) {
            $v = request()->validate(Project::$rules_with_shift_hours);
        } else {
            $v = request()->validate(Project::$rules);
        }
        $quick_add = false;
        if(request('show_quick_add_project') !== null){
            $quick_add = true;
        }
        $worker_ids = user()->company->workers()->whereIn('id', (array)request('worker_ids'))->pluck('id');
        if($quick_add){
            $worker_ids = Worker::where('company_id', user()->company->id)->pluck('id');
        }
        $last_company_project_id = Project::where('company_id' , user()->company->id )->max('company_project_id');
        $company_project_id = $last_company_project_id + 1;
        $project = Project::create($v + [ 'company_project_id' => $company_project_id, 'company_id' => user()->company->id, 'quick_add' => $quick_add]);
        $project->workers()->sync($worker_ids);
        return redirect()->route('projects.index')->with('success', 'saved');
    }

    public function edit($project_id)
    {
        $page = 'edit';
        $url = route('projects.update', $project_id);
        $project = user()->company->projects()->find($project_id);
        $workers = user()->company->workers()->orderDefault()->get();
        $clients = user()->company->clients()->orderDefault()->get();
        $client = isset($project->client_id) ? Client::find($project->client_id) : new Client();

        $day_duration = $worked_duration = null;
        if (!empty($project->shift_start) && !empty($project->shift_end)) {
            $day_duration = Project::calculateWorkDuration ($project->shift_start, $project->shift_end, $project->break_time);
            $worked_duration = Project::calculateWorkDuration ($project->shift_start, $project->shift_end);
        }
        return view('projects.create_or_edit', compact('project', 'workers', 'clients', 'client', 'page', 'url', 'day_duration', 'worked_duration'));
    }

    public function update($project_id)
    {
        if (!empty(request('shift_start')) || !empty(request('shift_end'))) {
            $v = request()->validate(Project::$rules_with_shift_hours);
        } else {
            $v = request()->validate(Project::$rules);
        }
        $v['allow_comments'] = (null !== request('allow_comments')) ? 1 : 0;// If checkbox is unchecked its not posted with request.
        $v['allow_photos'] = (null !== request('allow_photos')) ? 1 : 0;
        $v['quick_add'] = false;
        $project = user()->company->projects()->find($project_id);
        $project->update($v);
        $worker_ids = user()->company->workers()->whereIn('id', (array)request('worker_ids'))->pluck('id');
        $project->workers()->sync($worker_ids);
        return redirect()->route('projects.index')->with('success', 'saved');
    }

    public function update_status ($project_id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), [
            'status' => 'required|in:active,completed',
        ]);
        if ($validator->fails()) {
            //return redirect()->route('projects.edit')->withErrors($validator)->withInput();
            return ['success' => false, "message" => "The project status is invalid."];
        } else {
            $v = request()->validate(Project::$statusRules);
            $project = user()->company->projects()->find($project_id);
            $project->update($v);
            //return redirect()->route('projects.edit', $project_id)->with('success', 'Project completed!');
            return ['success' => true, "message" => "The project marked as ".$v['status']."."];
        }
    }

    public function duplicate($project_id)
    {
        $page = 'duplicate';
        $project_old = user()->company->projects()->find($project_id);
        $project = $project_old->replicate();
        $project->workers = $project_old->workers;
        $client = new Client();
        $workers = user()->company->workers()->orderDefault()->get();
        $clients = user()->company->clients()->orderDefault()->get();
        $url = route('projects.duplicate-post', $project_id);

        $day_duration = $worked_duration = null;
        if (!empty($project->shift_start) && !empty($project->shift_end)) {
            $day_duration = Project::calculateWorkDuration ($project->shift_start, $project->shift_end, $project->break_time);
            $worked_duration = Project::calculateWorkDuration ($project->shift_start, $project->shift_end);
        }
        return view('projects.create_or_edit', compact('project', 'workers', 'clients', 'url', 'page', 'client', 'day_duration', 'worked_duration'));
    }

    public function duplicatePost($project_id)
    {
        $project = user()->company->findProject($project_id);
        if (!empty(request('shift_start')) || !empty(request('shift_end'))) {
            $v = request()->validate(Project::$rules_with_shift_hours);
        } else {
            $v = request()->validate(Project::$rules);
        }
        //getLock(user()->company->id); // to prevent company tool id duplication
        $new_project = new Project();
        $new_project->company_id = user()->company->id;
        $new_project->company_project_id = $this->getCompanyProjectId( user()->company_id );
        $new_project->name = $v['name'];
        if (isset($v['address'])) {
            $new_project->address = $v['address'];
        }
        if (isset($v['city'])) {
            $new_project->city = $v['city'];
        }
        if (isset($v['postcode'])) {
            $new_project->postcode = $v['postcode'];
        }
        if (isset($v['description'])) {
            $new_project->description = $v['description'];
        }
        if (isset($v['start_date'])) {
            $new_project->start_date = $v['start_date'];
        }
        if (isset($v['shift_start'])) {
            $new_project->shift_start = $v['shift_start'];
        }
        if (isset($v['shift_end'])) {
            $new_project->shift_end = $v['shift_end'];
        }
        if (isset($v['manager_id'])) {
            $new_project->manager_id = $v['manager_id'];
        }
        if (isset($v['client_id'])) {
            $new_project->client_id = $v['client_id'];
        }
        if (isset($v['break_time'])) {
            $new_project->break_time = $v['break_time'];
        }
        if (isset($v['payment_type'])) {
            $new_project->payment_type = $v['payment_type'];
        }
        if (isset($v['hourly_rate'])) {
            $new_project->hourly_rate = $v['hourly_rate'];
        }
        if (isset($v['fixed_rate'])) {
            $new_project->fixed_rate = $v['fixed_rate'];
        }
        if (isset($v['total_hours'])) {
            $new_project->total_hours = $v['total_hours'];
        }
        if (isset($v['allow_comments'])) {
            $new_project->allow_comments = $v['allow_comments'];
        }
        if (isset($v['allow_photos'])) {
            $new_project->allow_photos = $v['allow_photos'];
        }
        if (isset($v['add_client'])) {
            $new_project->add_client = $v['add_client'];
        }
        if (isset($v['add_economical_details'])) {
            $new_project->add_economical_details = $v['add_economical_details'];
        }
        $new_project->save();
        $worker_ids = $project->workers()->pluck('workers.id');
        $new_project->workers()->sync($worker_ids);
        //releaseLock(user()->company->id);

        return redirect()->route('projects.index')->with('success', 'dup saved');
    }

    public function destroyWithData($project_id)
    {
        $project = user()->company->findProject($project_id);

        $hours = Hour::where('project_id', $project_id)->select('id', 'images')->get()->toArray();
        if (!empty($hours)) {
            foreach($hours as $hr) {
                if (!empty($hr['images'])) {
                    foreach($hr['images'] as $image) {
                        \Illuminate\Support\Facades\Storage::delete($image);
                    }
                }
            }
        }
        $project->forceDelete();
        DB::table('projects_deletion_history')->insert(['description' => 'Project Id '. $project_id.' | Permanently deleted.', 'created_at'=> date('Y-m-d H:i:s'), 'updated_at'=> date('Y-m-d H:i:s')]);

        return ['success' => true, 'message' => 'Project including relevant data has been deleted!', 'url' => route('projects.index')];
    }

    public function projectReport ($project_id)
    {
        $data = ['hours' => null, 'images' => null, 'delete_url' => route('projects.destroy-with-data', $project_id), 'id'=> $project_id];
        $hours = Hour::where('project_id', $project_id)->select('id', 'images', 'working_hours')->get()->toArray();

        if (!empty($hours)) {
            foreach($hours as $hr) {
                $data['hours'] += $hr['working_hours'];
                if (!empty($hr['images'])) {
                    $data['images'] += count($hr['images']);
                }
            }
        }
        return $data;
    }
    
    public function getList ($companyId, $status)
    {
        $dateRange = getDateRangeFromDateOption();
        $query = Hour::where('company_id', user()->company_id);
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('work_day', '>=' , $dateRange['start_date'])->where('work_day', '<=' , $dateRange['end_date']);
        }
        $hourProjectIds = $query->groupBy('project_id')->pluck('project_id')->toArray();
        $projects = Project::where('company_id', $companyId)->where('status', $status)->whereIn('id', $hourProjectIds)->select('company_project_id', 'name')->get()->toArray();
        return view('projects.list', compact('projects'));
    }

    private function getCompanyProjectId ($companyId)
    {
        $id = Project::where('company_id', $companyId)->max('company_project_id');

        return $id+1;
    }
}
