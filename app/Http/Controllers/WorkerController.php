<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Twilio\Rest\Client;
use App\Models\Hour;
use App\Models\Tool;
use App\Models\ToolHistory;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{
    private static function rules($request): array
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_country' => 'required|integer',
            'phone_number' => 'required|integer',
            'change_tool_status' => 'boolean',
            'scan_to_storage' => 'boolean',
            'inventory_storage' => 'boolean',
            'see_company_tools' => 'boolean',
            'add_tool' => 'boolean',
            'see_hours' => 'boolean',
            'edit_hours' => 'boolean',
            'images' => 'nullable',
            'worker_position' => 'required',
            'language_settings' => 'nullable',
            'economical_data' => 'boolean',
            'status' => 'boolean',
            'loose_access' => 'boolean',
            'hide_worker' => 'boolean',
            'project_ids' => 'nullable',
        ];
        if (isset($request['economical_data'])) {
            if (empty($request['worker_cost']) && empty($request['worker_salary'])) {
                $rules['worker_cost'] = 'required_if:economical_data,==,1';
                $rules['worker_salary'] = 'required_if:economical_data,==,1';
            } elseif (empty($request['worker_cost']) && $request['worker_salary']) {
                $rules['worker_cost'] = 'nullable';
                $rules['worker_salary'] = 'required_if:economical_data,==,1';
            }elseif ($request['worker_cost'] && empty($request['worker_salary'])) {
                $rules['worker_cost'] = 'required_if:economical_data,==,1';
                $rules['worker_salary'] = 'nullable';
            } else {
                $rules['worker_cost'] = 'required_if:economical_data,==,1';
                $rules['worker_salary'] = 'required_if:economical_data,==,1';
            }
        }
        return $rules;
    }

    public function index()
    {
        $worker_name = isset($_GET['worker_name']) ? $_GET['worker_name'] : null;
        $skills = isset($_GET['skills']) ? $_GET['skills'] : null;
        $worker_status = isset($_GET['worker_status']) ? $_GET['worker_status'] : null;
        //$worker_role = isset($_GET['worker_role']) ? $_GET['worker_role'] : null;
        $date = (isset($_GET['date'])) ? $_GET['date'] : null;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
        
        $activeWorkers = user()->company->workers()->where('status', 1)->get();
        $workers = user()->company->workers()->with([
            'sum_hours'
        ])->withCount(['tools', 'toolsNeedInventorization'])->filter()->orderDefault()->paginate(25);
        return view('workers.index', compact('workers', 'worker_name', 'skills', 'worker_status', 'date', 'start_date', 'end_date', 'activeWorkers'));
    }

    public function create()
    {
        // Check Trial Limit
        // $checkAccess = user()->checkWorkerAccess();
        // if(!$checkAccess) { return redirect(route('subscription.show'))->with('danger', 'Please subscribe to a plan to manage workers.'); }
        // Check Trial Limit

        $worker = new Worker();
        $projects = user()->company->projects()->where('status', 'active')->orderBy('created_at','DESC')->get();
        $url = route('workers.store');
        $workerPositions = [];

        return view('workers.create_or_edit', compact('worker', 'url', 'projects', 'workerPositions'));
    }

    public function store()
    {
        // Check Access
        // $checkAccess = user()->checkWorkerAccess();
        // if(!$checkAccess) { return redirect(route('subscription.show'))->with('danger', 'Please subscribe to a plan to manage workers.'); }
        // Check Access
        
        $rules = self::rules(request()->all());
        $referer = request()->headers->get('referer');
        if($referer == route('workers.create')){
            $v = request()->validate($rules);
        }else{
            $v = Validator::make(request()->all(), $rules);
            if($v->fails()){
                return back()->withErrors($v->getMessageBag(), 'workerQuickModalError');
            }
            $v = request()->all();
        }



        $project_ids = user()->company->projects()->whereIn('id', (array)request('project_ids'))->pluck('id');

        $arrayTostring = implode(',', request()->input('worker_position') ?? [] );
        $v['worker_position'] = $arrayTostring;

        self::validatePhone($v['phone_country'], $v['phone_number']);

        $data = ['company_id' => user()->company->id] + $v;
        $data['login'] = Str::random(20);
        if( request('show_quick_add_workers') !== null ){
            $data['quick_add'] = true;
            $project_ids = user()->company->projects()->pluck('id');
        }


        unset($data['project_ids']);
        unset($data['show_quick_add']);
        unset($data['customWorkerPosition']);
        unset($data['show_quick_add_workers']);

        $worker = Worker::create($data);
        if(request('images')){
            $worker->profilePicture($v['images'] ?? []);
        }
        $worker->projects()->sync($project_ids);
        $success = 'saved';

        if ($worker->phone()) {
            getSmsText($worker);
        }
        // if ($worker->phone()) {
        //     $text = 'Hello ' . $worker->first_name . ',

        // Welcome to WorkerNU tools management system.
        // You can access your profile by clicking on this link:

        // ' . $worker->workerLink();
        //             sms($worker->phone(), $text);
        //             $success = 'SMS with a login link was sent to the worker';
        //         }

        // Report usage
        user()->reportUsage();
        // Report usage

        return redirect()->route('workers.index')->with('success', $success);
    }

    public function edit($worker_id)
    {
        // Check Access
        // $checkAccess = user()->checkWorkerAccess();
        // if(!$checkAccess) { return redirect(route('subscription.show'))->with('danger', 'Please subscribe to a plan to manage workers.'); }
        // Check Access

        $worker = user()->company->workers()->findOrFail($worker_id);
        $projects = user()->company->projects()->where('status', 'active')->orderBy('created_at','DESC')->get();
        $url = route('workers.update', $worker->id);

        $workerPositions = explode(',', $worker->worker_position);

        return view('workers.create_or_edit', compact('worker', 'url', 'projects', 'workerPositions'));
    }

    public function update($worker_id)
    {
        // Check Access
        // $checkAccess = user()->checkWorkerAccess();
        // if(!$checkAccess) { return redirect(route('subscription.show'))->with('danger', 'Please subscribe to a plan to manage workers.'); }
        // Check Access

        $rules = self::rules(request()->all());
        $worker = user()->company->workers()->findOrFail($worker_id);
        $worker_old_images = $worker->images;
        $v = request()->validate($rules);

        self::validatePhone($v['phone_country'], $v['phone_number']);

        $v['change_tool_status'] = $v['change_tool_status'] ?? false;
        $v['scan_to_storage'] = $v['scan_to_storage'] ?? false;
        $v['inventory_storage'] = $v['inventory_storage'] ?? false;
        $v['see_company_tools'] = $v['see_company_tools'] ?? false;
        $v['add_tool'] = $v['add_tool'] ?? false;
        $v['see_hours'] = $v['see_hours'] ?? false;
        $v['edit_hours'] = $v['edit_hours'] ?? false;
        $v['economical_data'] = $v['economical_data'] ?? false;
        $v['status'] = $v['status'] ?? false;
        $v['loose_access'] = $v['loose_access'] ?? false;
        $v['hide_worker'] = $v['hide_worker'] ?? false;
        $v['quick_add'] = false;

        $arrayTostring = implode(',', request()->input('worker_position') ?? []);
        $v['worker_position'] = $arrayTostring;

        unset($v['customWorkerPosition']);
        unset($v['project_ids']);
        $worker->update($v);
        if($worker_old_images && isset($v['images'])){
            $worker->deleteImage($worker_old_images);
        }
        if (isset($v['images'])) {
            $worker->updatedProfilePicture($v['images']);
        }
        $project_ids = user()->company->projects()->whereIn('id', (array)request('project_ids'))->pluck('id');
        $worker->projects()->sync($project_ids);
        
        // Report usage
        user()->reportUsage();
        // Report usage

        return redirect()->route('workers.index')->with('success', 'saved');
    }

    public function destroy($worker_id)
    {
        // Check Access
        // $checkAccess = user()->checkWorkerAccess();
        // if(!$checkAccess) { return redirect(route('subscription.show'))->with('danger', 'Please subscribe to a plan to manage workers.'); }
        // Check Access
        
        $worker = user()->company->workers()->findOrFail($worker_id);
        $worker->forceDelete();
        DB::table('worker_deletion_history')->insert(['description' => 'Worker Id '. $worker_id.' : Company Id '. user()->company_id . ' | Permanently deleted.', 'created_at'=> date('Y-m-d H:i:s'), 'updated_at'=> date('Y-m-d H:i:s')]);
        
        // Report usage
        user()->reportUsage();
        // Report usage
        
        return ['success' => true, 'message' => 'Worker has been deleted!', 'url' => route('workers.index')];
    }

    public function destroyWithData($worker_id)
    {
        // Check Access
        // $checkAccess = user()->checkWorkerAccess();
        // if(!$checkAccess) { return redirect(route('subscription.show'))->with('danger', 'Please subscribe to a plan to manage workers.'); }
        // Check Access
        
        $user = user();
        $worker = $user->company->workers()->findOrFail($worker_id);
        $hours = Hour::where('worker_id', $worker_id)->select('id', 'images')->get()->toArray();
        if (!empty($hours)) {
            foreach($hours as $hr) {
                if (!empty($hr['images'])) {
                    foreach($hr['images'] as $image) {
                        \Illuminate\Support\Facades\Storage::delete($image);
                    }
                }
            }
        }
        //Move worker tools back to storage.
        if (request('storage_id')) {
            $storage_id = request('storage_id');
            $storage = $user->company->storages()->findOrFail($storage_id);
            $tools = $user->company->tools()->where('possessor_type', 'App\Models\Worker')->where('possessor_id', $worker_id)->get();
            foreach ($tools as $tool) {
                $tool->transfer($storage);
                ToolHistory::log($tool, $user->name . ' transferred tool to storage "' . $storage->name . '"');
            }
        }
        $worker->forceDelete();
        DB::table('worker_deletion_history')->insert(['description' => 'Worker Id '. $worker_id.' : Company Id '. $user->company_id . ' | Permanently deleted.', 'created_at'=> date('Y-m-d H:i:s'), 'updated_at'=> date('Y-m-d H:i:s')]);
        
        // Report usage
        user()->reportUsage();
        // Report usage
        
        return ['success' => true, 'message' => 'Worker data has been deleted!', 'url' => route('workers.index')];
    }

    private static function validatePhone($country, $phone)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $twilio = new Client($sid, $token);

        $phone = '+' . $country . $phone;
        try {
            $phone_number = $twilio->lookups->v1->phoneNumbers($phone)->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            throw ValidationException::withMessages(['Invalid phone number format']);
        }
    }

    public function workerReport ($worker_id)
    {
        $data = [
            'hours' => null,
            'images' => null,
            'worker_tools' => user()->company->tools()->where('possessor_type', 'App\Models\Worker')->where('possessor_id', $worker_id)->pluck('id', 'name')->toArray(),
            'company_storages'=> user()->company->storages()->select('name', 'id')->get()->toArray(),
            'delete_url' => route('workers.destroy-with-data', $worker_id),
            'id'=>$worker_id
        ];
        $hours = Hour::where('worker_id', $worker_id)->select('id', 'images', 'working_hours')->get()->toArray();

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

    public function getList ($companyId)
    {
        $workers = Worker::activeWorkers($companyId);
        return view('workers.list', compact('workers'));
    }
    
    public function workerTools ($worker_id)
    {

    }

}
