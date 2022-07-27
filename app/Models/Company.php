<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use App\Models\Module;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'settings' => 'json'
    ];

    // -------------------------------------------- relationships ------------------------------------------------------

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function toolCategories()
    {
        return $this->hasMany(ToolCategory::class);
    }

    public function tools()
    {
        return $this->hasMany(Tool::class);
    }

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }

    public function customWorkerPosition()
    {
        return $this->hasMany(WorkerPosition::class);
    }

    public function storages()
    {
        return $this->hasMany(Storage::class);
    }

    public function hour()
    {
        return $this->hasMany(Hour::class);
    }

    public function inventorization()
    {
        return $this->hasOne(Inventorization::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class)->with(['client:id,name', 'manager:id,first_name,last_name']);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    public function sick_workers()
    {
        return $this->hasManyThrough(Sickness::class, Worker::class)->with('worker');
    }

    // sickness/holiday---------
    public function sickness()
    {
        return $this->hasManyThrough(Sickness::class, Worker::class)->with('worker');
    }

    public function holiday()
    {
        return $this->hasManyThrough(Holiday::class, Worker::class)->with('worker');
    }

    // ------------------------

    public function company_access_control()
    {
        return $this->hasOne(CompanyAccessControl::class);
    }

    // ------------------------------------------------- methods -------------------------------------------------------

    public function findTool(string $qr_code)
    {
        $id = Qr::decodeToolId($qr_code);
        $unknown_tool = Tool::find($id);
        //$unknown_tool = Tool::where('tool_code', $qr_code)->first();
        // no ID
        if (!$unknown_tool) {
            throw ValidationException::withMessages(["QR doesn't exists"]);
        }
        // another company
        if ($unknown_tool->company_id !== $this->id) {
            throw ValidationException::withMessages(['This tool belongs to the "' . $unknown_tool->company->name . '" company']);
        }

        return $this->tools()->find($id);
        //return $this->tools()->find($unknown_tool->id);
    }

    public function findProject($project_id)
    {
        $unknown_project = Project::find($project_id);
        // no ID
        if (!$unknown_project) {
            throw ValidationException::withMessages(["Project doesn't exists"]);
        }
        // another company
        if ($unknown_project->company_id !== $this->id) {
            throw ValidationException::withMessages(['This project belongs to the "' . $unknown_tool->company->name . '" company']);
        }

        return $this->projects()->find($project_id);
    }

    public static function createCompany(string $name)
    {
        return self::create([
            'name' => $name,
        ]);
    }

    // -----------profile picture ---------------

    public function updateLogo($image_path)
    {
        $files = $this->images;
        $company_id = user()->company_id;
            \Image::make($image_path)->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(null, 95);
            $files = $image_path->store($company_id.'/logo');
        $this->logo = $files;
        $this->save();
    }
    public function deleteLogo(string $image_name)
    {
        \Illuminate\Support\Facades\Storage::delete($image_name);
        $this->logo = null;
        $this->save();
    }

    // ------------------------------------------- scopes --------------------------------------------------------------

    public function scopeFilter($q)
    {
        if (request('company_name')) {
            $name = request('company_name');
            //$q->where('first_name','LIKE', "%$name%")->orWhere('last_name','LIKE', "%$name%");
            $q->where('name','LIKE', "%$name%")->orWhere('name','LIKE', "%$name%");
        }

        if (request('date')=='Last week') {
            // Last week---------------
            $previous_week = strtotime("-1 week +1 day");
            $start_week = strtotime("last sunday midnight",$previous_week);
            $end_week = strtotime("next saturday",$start_week);
            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);
            return $q->where('created_at', '>=' , $start_week)->where('created_at', '<=' , $end_week);
        }

        if (request('date')=='Previous two weeks') {
            // Previous two weeks---------------
            $previous_two_week = strtotime("-2 week +1 day");
            $start_two_week = strtotime("last sunday midnight",$previous_two_week);//1

            $previous_week = strtotime("-1 week +1 day");
            $start_week = strtotime("last sunday midnight",$previous_week);
            $end_week = strtotime("next saturday",$start_week);//2

            $start_two_week = date("Y-m-d",$start_two_week);
            $end_week = date("Y-m-d",$end_week);
            //echo $start_two_week.' '.$end_week ;exit;
            return $q->where('created_at', '>=' , $start_two_week)->where('created_at', '<=' , $end_week);
        }

        if (request('date')=='This week') {
            // This week---------------
            $currentDate=date('y-m-d');
            $start_week = strtotime("last sunday midnight");
            $end_week = strtotime($currentDate);
            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);
            // return $start_week.' '.$end_week ;
            return $q->where('created_at', '>=' , $start_week)->where('created_at', '<=' , $end_week);
        }

        if (request('date')=='Last and this week') {
            //  Last and this weeks---------------
            $currentDate=date('y-m-d');
            $previous_week = strtotime("-1 weeks +1 day");
            $start_week = strtotime("last sunday midnight",$previous_week);
            $end_week = strtotime($currentDate);
            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);
            // return $start_week.' '.$end_week ;
            return $q->where('created_at', '>=' , $start_week)->where('created_at', '<=' , $end_week);
        }

        if (request('date')=='Last month') {
            // Last month---------------
            $start_month = date("Y-n-j", strtotime("first day of previous month"));
            $end_month = date("Y-n-j", strtotime("last day of previous month"));
            // return $start_month.' '.$end_month ;
            return $q->where('created_at', '>=' , $start_month)->where('created_at', '<=' , $end_month);
         }

        if (request('date')=='This month') {
            // this month---------------
            $currentDate=date('y-m-d');
            $start_month = date("Y-n-j", strtotime("first day of this month"));
            $end_month = date("Y-n-j", strtotime($currentDate));
            // return $start_month.' '.$end_month ;
            return $q->where('created_at', '>=' , $start_month)->where('created_at', '<=' , $end_month);
        }

        // custom--------------
        if (request('date') == 'Custom' && !empty(request('start_date')) && !empty(request('end_date'))) {
            $start_date = request('start_date');
            $end_date = request('end_date');
            // return $start_month.' '.$end_month ;
            return $q->where('created_at', '>=' , $start_date)->where('created_at', '<=' , $end_date);
        }

    }

    public function scopeOrderDefault($q)
    {
        $q->orderBy('id', 'DESC');
    }

    // ---------------- Access Control ----------------
    /*
     * Check company access control
     */
    public function checkAccess($type, $route_name = '') {
        // Check if access control exists for the company, if not keep full access by default
        $checkExists = $this->company_access_control()->exists();
        if(!$checkExists) {
            return true;
        }
        // -------------

        // Check if route needs to be checked, if not allow access by default
        if(!empty($route_name)) {
            $checkExists = Module::whereJsonContains('route_names', $route_name)->exists();
            if(!$checkExists) {
                return true;
            }
        }
        // -------------

        $modules = [];
        $accessible_modules = $this->company_access_control()->pluck($type);
        if((!empty($accessible_modules[0]))) {
            $modules = json_decode($accessible_modules[0]);
        }

        if(!empty($route_name)) {
            $is_accessible = Module::whereIn('name', $modules)->whereJsonContains('route_names', $route_name)->exists();
            return $is_accessible;
        }

        return $modules;
    }

}
