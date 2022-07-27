<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class Tool extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'inventoried_at' => 'datetime',
        'next_inventorization_at' => 'date',
        'purchased_at' => 'datetime',
        'images' => 'array',
        'tool_code' => 'string'
    ];

    // -------------------------------------------- scopes -------------------------------------------------------------

    public function scopeFilter($q)
    {
        if (request('q')) {
            $s = request('q');
            if (is_numeric($s)) {
                $q->where('company_tool_id', $s);
            } else {
                $q->where(function ($q) use ($s) {
                    $q->orWhere('name', 'LIKE', "%$s%")->orWhere('model', 'LIKE', "%$s%");
                });
            }
        }
        if (request('code')) {
            $tool_id = Qr::decodeToolId(request('code'));
            $q->where('id', $tool_id);
        }
        if (request('worker_id')) {
            $q->where('possessor_id', request('worker_id'))->where('possessor_type', 'App\Models\Worker');
        }
        if (request('storage_id')) {
            $q->where('possessor_id', request('storage_id'))->where('possessor_type', 'App\Models\Storage');
        }
        if (request('category_id')) {
            $q->where('tool_category_id', request('category_id'));
        }
        if (request('need_inventorization') === '1') {
            //$q->needInventorization();
            $q->whereNotNull("next_inventorization_at")->where("next_inventorization_at", "<=", today());
            
        } elseif (request('need_inventorization') === '0') {
            //$q->needInventorization(false); // Default option is balanced means don't need inventorization.
            $q->whereNotNull("inventoried_at")->where(function ($query) {
                $query->where("next_inventorization_at", ">", today())
                    ->orWhereNull('next_inventorization_at');
            });
        }
        if(request('status_id') != 'all') {
            if (request('status_id')) {
                $q->where('status_id', request('status_id'));
            } else {
                if (Auth::check()) {
                    $q->where('status_id', 1);//operational is default for company manage but not for worker. We show all for worker
                }
            }
        }
    }
    
    public function scopeNeedInventorization($q, $needs = true)
    {
        if ($needs) {
            $q->where('next_inventorization_at', '<=', today());
        } else {
            $q->where('next_inventorization_at', '>', today());
        }

    }
    
    public static function daysUntillNextBalancing($dateRange)
    {
        $query = self::whereNotNull('inventoried_at')->whereNotNull('next_inventorization_at')->where('company_id', user()->company_id)
                ->select('next_inventorization_at');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('next_inventorization_at', '>=' , $dateRange['start_date'])->where('next_inventorization_at', '<=' , $dateRange['end_date']);
        }
        $date = $query->orderBy('next_inventorization_at', 'ASC')->limit(1)->get()->toArray();
        if (isset($date[0]['next_inventorization_at'])) {
            $now = time(); // or your date as well
            $coming_date = strtotime($date[0]['next_inventorization_at']);
            $datediff = $coming_date - $now;

            $days = floor($datediff / (60 * 60 * 24));
            return ['date' => date('d-m-Y',strtotime($date[0]['next_inventorization_at'])) , 'days' => $days];
        } else {
          return ['date' => 'Nil', 'days'=>'Nil' ];
        }
    }

    public function scopeNotNotified($q)
    {
        $q->where('notified', false);
    }

    public function scopeWherePossessorIsWorker($q)
    {
        $q->where('possessor_type', 'App\Models\Worker');
    }

    public function scopeInService($q)
    {
        $status = ToolStatus::where('name', 'in service')->firstOrFail();
        $q->where('status_id', $status->id);
    }

    // -------------------------------------------- relationships ------------------------------------------------------

    public function possessor()
    {
        return $this->morphTo();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function histories()
    {
        return $this->hasMany(ToolHistory::class);
    }

    public function category()
    {
        return $this->belongsTo(ToolCategory::class, 'tool_category_id');
    }

    public function inventorization()
    {
        return $this->hasOne(Inventorization::class);
    }

    public function status()
    {
        return $this->belongsTo(ToolStatus::class, 'status_id');
    }

    // --------------------------------------- methods -----------------------------------------------------------------

    public static function rules(Company $company): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|between:0,1000000',
            'tool_category_id' => 'required|int',
            'storage_id' => 'nullable|int',
            'purchased_at' => 'nullable|date',
            'images' => 'array|max:5',
            'images.*' => 'image|mimes:jpg,png|max:10000|dimensions:max_width=5000,max_height=5000',
        ];
        if (!$company->storages()->exists()) {
            $rules['storage_id'] = 'nullable';
        }
        if (!$company->toolCategories()->exists()) {
            $rules['tool_category_id'] = 'nullable';
        }

        return $rules;
    }

    public function publicId(): string
    {
        return Qr::encodeId($this->id);
    }

    public function canTransfer(Storage|Worker $new_possessor): bool
    {
        // already belongs to worker?
        if ($this->possessor()->is($new_possessor)) {
            throw ValidationException::withMessages(["This tool is already assigned to this possessor"]);
        }

        return true;
    }

    public function transfer(Storage|Worker $new_possessor)
    {
        $this->canTransfer($new_possessor);

        DB::transaction(function () use ($new_possessor) {
            $this->possessor_id = $new_possessor->id;
            $this->possessor_type = $new_possessor::class;
            $this->next_inventorization_at = $this->nextInventorizationAt();
            $this->notified = false;
            $this->save();
        });
    }

    public function inventory()
    {
        if ($this->needsInventorization() !== true) {
            throw ValidationException::withMessages(['"' . $this->name . '"' . " doesn't need inventorization"]);
        }

        $this->inventoried_at = now();
        $this->next_inventorization_at = $this->nextInventorizationAt();
        $this->notified = false;
        $this->save();
    }

    public function qrLink(): string
    {
        if($this->tool_code) {
            $label = explode('-', $this->tool_code, 2)[1];
            $qrUrl = Qr::url($this->tool_code, $label);
        } else{
            $label = Str::limit($this->company_tool_id . ' ' . $this->name, 12, '');
            $qrUrl = Qr::url($this->id, $label);
        }
        return $qrUrl;
    }

//    public function isInventoriable(): bool
//    {
//        return $this->next_inventorization_at !== null;
//    }

//    public function needsInventorization(): bool
//    {
//        return ($this->next_inventorization_at && $this->next_inventorization_at->isPast());
//    }

    // true - needs inventorization
    // false, null - doesn't need
    // false - also means "balanced" (was balanced and will need balancing in the future)
    public function needsInventorization(): bool|null
    {
        if ($this->next_inventorization_at === null) {
            return null;
        } elseif ($this->next_inventorization_at->isPast()) {
            return true;
        } elseif ($this->inventoried_at) {
            return false;
        } else {
            return null;
        }
    }
    
    public static function getUnbalancedCount($tools, $type)
    {
        $unbalanced = 0;
        if ($type == 'storage') {    
            foreach ($tools as $tool) {
                if ($tool->possessor::class === \App\Models\Storage::class) {
                    if ($tool->next_inventorization_at !== null && $tool->next_inventorization_at->isPast()) {
                        $unbalanced++;
                    }
                }
            }
            return $unbalanced;
        }
        if ($type == 'worker') {
            foreach ($tools as $tool) {
                //\Illuminate\Support\Facades\Log::info('Tool id = '.$tool->id);
                if ($tool->possessor::class === \App\Models\Worker::class) {
                    if ($tool->next_inventorization_at !== null && $tool->next_inventorization_at->isPast()) {
                        $unbalanced++;
                    }
                }
            }
            return $unbalanced;
        }
    }
    
    public static function getUnbalancedCountOfAllTools($tools)
    {
        $unbalanced = 0;
        foreach ($tools as $tool) {
            if ($tool->next_inventorization_at !== null && $tool->next_inventorization_at->isPast()) {
                $unbalanced++;
            }
        }
        return $unbalanced;
    }
    
    public static function topToolsInService ($dateRange)
    {
        $query = self::where('status_id', 4)->where('company_id', user()->company_id)->orderBy('status_changed_at', 'ASC');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('status_changed_at', '>=' , $dateRange['start_date'])->where('status_changed_at', '<=' , $dateRange['end_date']);
        }
        $tools = $query->get();
        $data = [];
        foreach ($tools as $tool) {
            $date = date('d-m-Y', strtotime($tool->status_changed_at));
            $now = time(); // or your date as well
            $old_date = strtotime($tool->status_changed_at);
            $datediff = $now - $old_date;

            $days = round($datediff / (60 * 60 * 24));
            $possessor = !empty($tool->possessor) ? $tool->possessor->possessorName() : '';
            $data[] = ['company_tool_id' => $tool->company_tool_id, 'name' => $tool->name, 'date' => $date, 'days' => $days, 'possessor' => $possessor, 'status_id' => 4];
        }
        return $data;
    }
    
    public static function topToolsWithWorkers ($dateRange)
    {
        $query = DB::table('tools')->selectRaw('possessor_id as worker_id, COUNT(possessor_id) as tools_count')
                ->where('company_id', user()->company_id)->where('possessor_type', 'App\Models\Worker');
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('created_at', '>=' , $dateRange['start_date'])->where('created_at', '<=' , $dateRange['end_date']);
        }
        $topWorkers = $query->groupBy('possessor_id')->orderBy('tools_count', 'DESC')->get()->toArray();
        
        $workerIds = [];
        foreach ($topWorkers as $worker) {
            $workerIds[] = $worker->worker_id;
        }
        $data = [];
        foreach ($topWorkers as $key => $work) {
            $toolsPrice = self::where('possessor_type', 'App\Models\Worker')->where('possessor_id', $work->worker_id)->sum('price');
            $workerTools = self::where('possessor_type', 'App\Models\Worker')->where('possessor_id', $work->worker_id)->get();
            $unbalancedCount = self::getUnbalancedCountOfAllTools($workerTools);
            
            $worker = Worker::where('id', $work->worker_id)->select('first_name', 'last_name')->get()->toArray();
            $data[] = ['worker_id' => $work->worker_id, 'worker_name' => $worker[0]['first_name'] .' '. $worker[0]['last_name'], 'total_tools'=> $work->tools_count, 'unbalanced_tools'=> $unbalancedCount, 'tools_price'=> $toolsPrice];
        }
        return $data;
    }
    
    public static function getNewToolsCountAndPrice($dateRange)
    {
        $query = self::where('company_id', user()->company_id)->where('created_at', '>', now()->subDays(30)->endOfDay());
//        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
//            $query->where('created_at', '>=' , $dateRange['start_date'])->where('created_at', '<=' , $dateRange['end_date']);
//        }
        $totalTools = $query->count('id');
        
        $totalToolsPrice = $query->sum('price');

        return ['total_tools' => $totalTools, 'total_tools_price' => $totalToolsPrice];
    }

    // mostly for frontend
    public function showBalanced(): bool
    {
        return $this->inventoried_at !== null && ($this->next_inventorization_at === null || $this->next_inventorization_at->isFuture());
    }

    // mostly for frontend
    public function showUnbalanced(): bool
    {
        return $this->next_inventorization_at !== null && $this->next_inventorization_at->isPast();
    }

    public function addImages(array $image_paths)
    {
        $max_images = 5;
        if ((count((array)$this->images) + count($image_paths)) > $max_images) {
            throw ValidationException::withMessages(['Tool can have up to 5 images']);
        }

        $files = $this->images;
        foreach ($image_paths as $image) {
            //\Image::make($image)->fit(700, 700)->orientate()->save(null, 75);
            \Image::make($image)->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(null, 95);
            $files[] = $image->store($this->company->id . '/tools');
        }
        $this->images = $files;
        $this->save();
    }

    public function duplicateImages()
    {
        $images = (array)$this->images;
        if (count($images) === 0) {
            return;
        }
        $new_images = [];
        foreach ($images as $image) {
            $extension = fileExtension($image);
            $new_path = $this->company->id . '/tools/' . Str::random(40) . '.' . $extension;
            \Illuminate\Support\Facades\Storage::copy($image, $new_path);
            $new_images[] = $new_path;
        }
        $this->images = $new_images;
        $this->save();
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

    private function nextInventorizationAt(): Carbon|null
    {
        if (!$this->company->inventorization) {
            return null;
        }
        if ($this->possessor_type !== 'App\Models\Worker') {
            return null;
        }

        return $this->company->inventorization->nextInventorizationDate();
    }

    public static function inventoryStorage(Storage $storage)
    {
        $storage->tools()->update([
            'next_inventorization_at' => today(),
            'notified' => false,
        ]);
    }

    public static function inventoryWorker(Worker $worker, string $sms_message = null)
    {
        $worker->tools()->update([
            'next_inventorization_at' => now()->startOfDay(),
            'notified' => false,
        ]);
        if ($worker->phone()) {
            if ($sms_message === null) {
                $sms_message = $worker->fullName() . ' - you have new request to inventory tools: ' . $worker->workerLink();
            }
            sms($worker->phone(), $sms_message . ' ' . $worker->workerLink());
        }
    }

    public static function disableInventorization(Company $company)
    {
        $company->tools()->wherePossessorIsWorker()->update([
            'next_inventorization_at' => null,
        ]);
    }

    public function changeStatus(ToolStatus $status, string|null $description, UploadedFile $status_photo = null)
    {
        if ($this->status_id === $status->id) {
            throw ValidationException::withMessages(["Tool already has this status"]);
        }

        if ($status->needs_description && !$description) {
            throw ValidationException::withMessages(['Description is required']);
        }

        $this->status_id = $status->id;
        $this->status_description = $description;
        $this->updateStatusPhoto($status_photo);

        $this->eventStatusChanged(); // saves
    }

    public function canWorkerReport(): bool
    {
        return $this->status_id === 1;
    }

    public function workerReportProblem(string $status_name, string|null $status_description, UploadedFile $status_photo = null)
    {
        in_array($status_name, ['broken', 'lost', 'in service']) or throw new \Exception('bad type ' . $status_name);
        $this->canWorkerReport() or throw new \Exception('bad state ' . $this->status);

        $this->updateStatusPhoto($status_photo);
        $this->status_id = ToolStatus::where('name', $status_name)->first()->id;
        $this->status_description = $status_description;

        $this->eventStatusChanged(); // saves

        $e = email()
            ->to($this->company->user->email)
            ->subject('Worker reported a problem with the tool')
            ->line('Worker reported a problem with the tool')
            ->line($this->possessor->possessorName())
            ->line('Tool: ' . $this->name)
            ->line('Tool ID: ' . $this->company_tool_id)
            ->line('Status: ' . $status_name)
            ->line('Description: ' . $this->status_description);
        if ($this->status_photo) {
            $e->image(\Illuminate\Support\Facades\Storage::url($this->status_photo));
        }
        $e->button(route('tools.index', ['q' => $this->company_tool_id]), 'View')
            ->queue();
    }

    private function eventStatusChanged()
    {
        $status_name = $this->status->name;

        $this->status_changed_at = today();

        if ($status_name === 'operational') {
            $this->next_inventorization_at = $this->nextInventorizationAt();
        } else {
            $this->next_inventorization_at = null;
        }
        if ($status_name === 'decommissioned') {
            $this->possessor_id = null;
            $this->possessor_type = null;
        }
        // @todo photo deletion

        $this->save();
    }

    public static function remindToInventory()
    {
        Worker::
            has('toolsNeedInventorizationNotNotified')
            ->chunk(1000, function ($workers) {
                $workers->load('company.inventorization');
                foreach ($workers as $worker) {
                    $sms_message = $worker->company->inventorization->sms_message;
                    sms($worker->phone(), $sms_message . ' ' . $worker->workerLink());
                    $worker->toolsNeedInventorizationNotNotified()->update([
                        'notified' => true,
                    ]);
                }
            }
        );
    }

    public static function remindEvery3WeeksAboutToolsInService()
    {
        User::where('last_in_service_reminder', '<', today()->subDays(21))->whereHas('company.tools', function ($q) {
             $q->inService();
             $q->where('status_changed_at', '<', today()->subDays(21));
        })->each(function ($user) {
            $user->last_in_service_reminder = today();
            $user->save();
            $user->company->tools()->where('status_changed_at', '<', today()->subDays(21))->update(['status_change_at' => today()]);

            email()
                ->to($user)
                ->subject('You have tools in service for over 3 weeks')
                ->line('Reminder, you have tools in service for over 3 weeks')
                ->queue();
        });
    }

    private function updateStatusPhoto(UploadedFile $uploaded_photo = null)
    {
        if ($this->status_photo) {
            \Illuminate\Support\Facades\Storage::delete($this->status_photo);
            $this->status_photo = '';
        }
        if ($uploaded_photo === null) {
            return;
        }
        \Image::make($uploaded_photo)->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(null, 95);
        $path = $uploaded_photo->store($this->company->id . '/status-photos');
        $this->status_photo = $path;
    }
    
    public static function saveHomeLink($url)
    {
        session()->put('tool_index_url', $url);
    }
    
    public static function checkHomeLink(): bool
    {
        return session()->has('tool_index_url');
    }
    
    public static function getHomeLink(): string
    {
        if(session()->has('tool_index_url')) {
            return session()->get('tool_index_url');
        }
        return false;
    }
    
    public static function forgetHomeLink()
    {
        if(!session()->has('tool_index_url')) {
            return session()->forget('tool_index_url');
        }
        return true;
    }
    
    public static function toolsExpenses ($dateRange)
    {
        $query = user()->company->tools();
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('created_at', '>=' , $dateRange['start_date'])->where('created_at', '<=' , $dateRange['end_date']);
        }
        return $toolExpenses = $query->sum('price');
    }
}
