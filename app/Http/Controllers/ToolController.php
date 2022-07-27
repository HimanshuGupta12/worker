<?php

namespace App\Http\Controllers;

use App\Models\Qr;
use App\Models\Tool;
use App\Models\ToolHistory;
use App\Models\ToolStatus;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class ToolController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = user();
        } elseif (\App\Models\Worker::isLoggedIn()) {
            $user = worker();
        } else {
            return redirect('/login');
        }
        $tools = $user->company->tools()->filter()->orderByDesc('created_at')->paginate(100);
        
        $tools->load('possessor', 'category', 'status');
        $workers = $user->company->workers()->orderDefault()->get();
        $storages = $user->company->storages()->orderDefault()->get();
        $categories = $user->company->toolCategories()->orderDefault()->get();
        $statuses = ToolStatus::get();
        
        $q = isset($_GET['q']) ? $_GET['q'] : null;
        $toolWorkerId = isset($_GET['worker_id']) ? $_GET['worker_id'] : null;
        $toolStorageId = isset($_GET['storage_id']) ? $_GET['storage_id'] : null;
        $toolCategoryId = isset($_GET['category_id']) ? $_GET['category_id'] : null;
        $toolStatusId = isset($_GET['status_id']) ? $_GET['status_id'] : 1;
        $toolNeedInventorization = isset($_GET['need_inventorization']) ? $_GET['need_inventorization'] : null; // default option is "0" - balanced means needs no inventorization.

        /* Sort filters don't apply the top calculated values.*/
        $totalTools = $user->company->tools()->filter()->count();
        $value = $user->company->tools()->filter()/*->whereNotIn('status_id', [3])*/->sum('price');
        $lostToolsvalue = $user->company->tools()->where('status_id', 3)->sum('price');
        $totalLostTools = $user->company->tools()->where('status_id', 3)->count('id');
        $totalInServiceTools = $user->company->tools()->where('status_id', 4)->count('id');
        
        $storageToolsCollection = $user->company->tools()->where('possessor_type', 'App\Models\Storage');
        $workerToolsCollection = $user->company->tools()->where('possessor_type', 'App\Models\Worker');
        $toolsInStorage = $storageToolsCollection->count();
        $toolsAtWorker = $workerToolsCollection->count();
        $storageToolsUnbalanced = Tool::getUnbalancedCount($storageToolsCollection->get(), 'storage');
        $workerToolsUnbalanced = Tool::getUnbalancedCount($workerToolsCollection->get(), 'worker');
        /*End total calculations without filters*/

        Tool::saveHomeLink(URL::full());
        
        return view('tools.index', compact('tools', 'workers', 'storages', 'categories', 'statuses', 'value', 'toolsInStorage', 'toolsAtWorker',
                'storageToolsUnbalanced', 'workerToolsUnbalanced', 'lostToolsvalue', 'totalLostTools', 'totalInServiceTools', 'totalTools', 'q',
                'toolWorkerId', 'toolStorageId', 'toolCategoryId', 'toolStatusId', 'toolNeedInventorization'));
    }

    public function create()
    {
        if (Auth::check()) {
            $user = user();
        } elseif (\App\Models\Worker::isLoggedIn()) {
            $user = worker();
        } else {
            return redirect('/login');
        }
        $tool = new Tool();
        $tool->purchased_at = date('Y-m-d');// set default as today.
        $workers = $user->company->workers()->orderDefault()->get();
        $categories = $user->company->toolCategories()->orderDefault()->get();
        $storages = $user->company->storages()->orderDefault()->get();
        $url = route('tools.store');
        $page = 'create';
        $code = '';
        $company_tools = $user->company->tools()->get();
        if (request('code')) {
            $code = request('code');
            $ifExists = Tool::where('tool_code', request('code'))->first();
            if ($ifExists) {
                if ($user->company->id != $ifExists->company_id) {
                    $companyName = Company::find($ifExists->company_id)->name;
                    return redirect()->route('tools.index')->with('danger', __('This QR is already used by company').' "'. $companyName.'"');
                }
                // Show message if this tool not assigned to any company.
                return redirect()->route('tools.index')->with('danger', __('Duplicate tool code'));
            }
            $company_id = $user->company->id;
            $codeChunks = explode('-', $code);
            if (count($codeChunks) != 2 || gettype($codeChunks[0]) != 'string' || !is_numeric($codeChunks[1]) || strlen(trim($codeChunks[0])) != 12) {
                return redirect()->route('tools.index')->with('danger', __('Invalid QR code'));
            }
            $company_tool_id_exists = Tool::where("company_tool_id", $codeChunks[1])->where('company_id', $company_id)->first();
            if ($company_tool_id_exists) {
                return redirect()->route('tools.index')->with('danger', __('Duplicate tool Id number in the code'));
            }
        }
        return view('tools.create_or_edit', compact('tool','workers','categories', 'storages', 'url', 'page', 'code', 'company_tools'));
    }

    public function store()
    {
        if (Auth::check()) {
            $worker = user();
            $redirect = 'tools.index';
            $success = 'saved';
        } else {
            $worker = worker();
            $redirect = 'tools.add-more';
            $success = __('Tool was added');
        }
        $rules = Tool::rules($worker->company);
        $v = request()->validate($rules);
        if(request('tool_code')) {
            $ifExists = Tool::where('tool_code', request('tool_code'))->first();
            if($ifExists) {
                return redirect()->route('tools.index')->with('danger', __('Duplicate tool code'));
            }
        }

        $tool_code = request('tool_code') ? request('tool_code'): NULL;
        $company_tool_id = self::nextCompanyToolId($tool_code, $worker);
        if (!$company_tool_id) {
            return redirect()->route('tools.index')->with('danger', __('You can not add this pre-printed QR.'));
        }

        // getLock(user()->company->id); // to prevent company tool id duplication
        $tool = new Tool();
        $tool->company_id = $worker->company->id;
        $tool->company_tool_id = $company_tool_id;
        $tool->tool_code = request('tool_code') ? request('tool_code'): NULL;
        $tool->name = $v['name'];
        $tool->model = $v['model'];
        $tool->serial = $v['serial'];
        $tool->price = $v['price'];
        $tool->tool_category_id = $worker->company->toolCategories()->find(request('tool_category_id'))->id ?? null;
        $tool->purchased_at = $v['purchased_at'];
        if (request('storage_id')) {
            $storage = $worker->company->storages()->find($v['storage_id']);
            $tool->possessor_id = $storage->id;
            $tool->possessor_type = 'App\Models\Storage';
        }elseif (request('worker_id')) {
            $tool->possessor_id = request('worker_id');
            $tool->possessor_type = 'App\Models\Worker';
        }
        
        if (request('qr_duplicate_tool_id')) {
            $qr_duplicate_tool = Tool::findOrFail(request('qr_duplicate_tool_id'));
            $tool->images = $qr_duplicate_tool->images;
            $tool->duplicateImages();
        }
        $tool->save();
        // releaseLock(user()->company->id);
        ToolHistory::log($tool, $worker->company->user->email . ' created tool');
        $tool->addImages($v['images'] ?? []);
        
        if (request('tool_code')) {
            $qr = new Qr();
            $qr->tool_id = $tool->id;
            $qr->legacy_qr = request('tool_code');
            $qr->save();
        }

        return redirect()->route($redirect)->with('success', $success);
    }
    
    public function addMore()
    {
        if (Auth::check()) {
            $worker = user();
        } elseif (\App\Models\Worker::isLoggedIn()) {
            $worker = worker();
        } else {
            return redirect('/login');
        }
        return view('tools.add_more', compact('worker'));
    }

    public function edit($tool_id)
    {
        $tool = user()->company->findTool($tool_id);
        if (!isset($tool->purchased_at)) {
            $tool->purchased_at = date('Y-m-d');//set today as default.
        }
        $workers = user()->company->workers()->orderDefault()->get();
        $categories = user()->company->toolCategories()->orderDefault()->get();
        $storages = user()->company->storages()->orderDefault()->get();
        $url = route('tools.update', $tool->publicId());
        $page = 'edit';

        return view('tools.create_or_edit', compact('tool', 'categories', 'url', 'page', 'storages', 'workers'));
    }

    public function update($tool_id)
    {
        $rules = Tool::rules(user()->company);
        unset($rules['storage_id']);
        $v = request()->validate($rules);

        $tool = user()->company->findTool($tool_id);

        $tool->name = $v['name'];
        $tool->model = $v['model'];
        $tool->serial = $v['serial'];
        $tool->price = $v['price'];
        $tool->tool_category_id = user()->company->toolCategories()->find(request('tool_category_id'))->id ?? null;
        if (request('worker_id')) {
            $tool->possessor_id = request('worker_id');
            $tool->possessor_type = 'App\Models\Worker';
        }

        $tool->purchased_at = $v['purchased_at'];
        $tool->save();
        $tool->addImages($v['images'] ?? []);
        
        ToolHistory::log($tool, user()->name . ' edited tool');
        if (Tool::checkHomeLink()) {
            return redirect()->away(Tool::getHomeLink())->with('success', 'saved');
        } else {
            return redirect()->route('tools.index')->with('success', 'saved');
        }
    }

    public function duplicate($tool_id)
    {
        $tool = user()->company->findTool($tool_id);
        $tool = $tool->replicate();
        $categories = user()->company->toolCategories()->orderDefault()->get();
        $storages = user()->company->storages()->orderDefault()->get();
        $url = route('tools.duplicate-post', $tool_id);
        $page = 'duplicate';
        $workers = user()->company->workers()->orderDefault()->get();

        return view('tools.create_or_edit', compact('tool', 'categories', 'storages', 'url', 'page', 'workers'));
    }

    public function duplicatePost($tool_id)
    {
        $tool = user()->company->findTool($tool_id);
        $rules = Tool::rules(user()->company);
        $v = request()->validate($rules);

        getLock(user()->company->id); // to prevent company tool id duplication
        $new_tool = new Tool();
        $new_tool->company_id = user()->company->id;
        $new_tool->company_tool_id = self::nextCompanyToolId(NULL, user());
        $new_tool->name = $v['name'];
        $new_tool->model = $v['model'];
        $new_tool->serial = $v['serial'];
        $new_tool->price = $v['price'];
        $new_tool->tool_category_id = user()->company->toolCategories()->find(request('tool_category_id'))->id ?? null;
        $new_tool->purchased_at = $v['purchased_at'];
        if (request('storage_id')) {
            $storage = user()->company->storages()->find($v['storage_id']);
            $new_tool->possessor_id = $storage->id;
            $new_tool->possessor_type = 'App\Models\Storage';
        }
        $new_tool->images = $tool->images;
        $new_tool->duplicateImages();
        $new_tool->save();
        releaseLock(user()->company->id);
        ToolHistory::log($new_tool, user()->email . ' created tool');
        try {
            $new_tool->addImages($v['images'] ?? []);
        } catch (\Exception $e) {
            return redirect()->route('tools.edit', $new_tool->publicId())->withErrors($e->getMessage());
        }

        if (Tool::checkHomeLink()) {
            return redirect()->away(Tool::getHomeLink())->with('success', 'saved');
        } else {
            return redirect()->route('tools.index')->with('success', 'saved');
        }
    }

    public function destroy($tool_id)
    {
        $tool = user()->company->findTool($tool_id);
        if ($tool->trashed()) {
            abort(400, 'Already deleted');
        }
        $tool->delete();

        if (isset($tool->tool_code)) {
            Qr::where('legacy_qr', $tool->tool_code)->delete();
        }
        ToolHistory::log($tool, user()->email . ' deleted');

        return back()->with('success', 'deleted');
    }

    public function deletePhoto($tool_id, $image_nr)
    {
        $tool = user()->company->findTool($tool_id);
        $image_name = $tool->images[$image_nr];
        $tool->deleteImage($image_name);

        return back()->with('success', 'deleted');
    }

    private static function nextCompanyToolId($tool_code, $worker)
    {
        $tool_code_id = NULL;
        $company_id = $worker->company->id;
        if (isset($tool_code)) {
            $tool_code_id = explode('-', $tool_code)[1];
            $company_tool_id_exists = Tool::where("company_tool_id", $tool_code_id)->where('company_id', $company_id)->first();
            if (!$company_tool_id_exists) {
                return $tool_code_id;
            }
            return false;
        } else {
            $next_starting_id = 1;
            //last highest Id for manually added tool.
            $last_manual_tool = Tool::where('company_id', $company_id)->whereNull('tool_code')->withTrashed()->orderBy('company_tool_id', 'desc')->first();
            if ($last_manual_tool) {
                $next_starting_id = $last_manual_tool->company_tool_id + 1;
            }
            return self::findNextAvailableCompanyToolId($next_starting_id, $company_id);
        }
    }

    private static function findNextAvailableCompanyToolId ($next_starting_id, $company_id) {
        $last_company_tool = Tool::where('company_id', $company_id)->withTrashed()->max('company_tool_id');
        $limit = isset($last_company_tool) ? $last_company_tool + 1 : 1;

        for ($i = $next_starting_id; $i <= $limit; $i++) {
            // This has a performance cost. We need to optimise this solution later.
            $company_tool_id_exists = Tool::where("company_tool_id", "=", $i)->where('company_id', $company_id)->withTrashed()->first();
            if (!$company_tool_id_exists) {
                return $i;
            }
        }
        return false;
    }

    public function changeStatus($tool_id)
    {
        $tool = user()->company->findTool($tool_id);
        $statuses = ToolStatus::get();

        return view('tools.change_status', compact('tool', 'statuses'));
    }

    public function changeStatusPost($tool_id)
    {
        request()->validate([
            'status_id' => 'required|exists:tool_statuses,id',
            'description' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,png|max:10000|dimensions:max_width=5000,max_height=5000',
        ]);

        $tool = user()->company->findTool($tool_id);
        $status = ToolStatus::find(request('status_id'));
        $tool->changeStatus($status, request('description'), request('photo'));
        ToolHistory::log($tool, user()->email . ' Changed status to: ' . $status->name . ' Comments: '.$tool->status_description .'');
        if (Tool::checkHomeLink()) {
            return redirect()->away(Tool::getHomeLink())->with('success', 'Updated');
        } else {
            return redirect()->route('tools.index')->with('success', 'Updated');
        }
    }
    
    public function getList ($companyId, $status)
    {
        $query = Tool::where('company_id', $companyId);
        if ($status == 'unbalanced') {
            $query->whereNotNull('next_inventorization_at')->where('next_inventorization_at','<', Carbon::today());
        } elseif ($status == 'all') {
            
        } elseif ($status == 'with_workers') {
            $query->where('possessor_type', 'App\Models\Worker');
        } elseif ($status == 'in_storage') {
            $query->where('possessor_type', 'App\Models\Storage');
        }
        
        $dateRange = getDateRangeFromDateOption();
        if (!empty($dateRange['start_date']) && !empty($dateRange['end_date'])) {
            $query->where('created_at', '>=' , $dateRange['start_date'])->where('created_at', '<=' , $dateRange['end_date']);
        }
        $tools = $query->with('possessor')->get();
        return view('tools.list', compact('tools'));
    }
}
