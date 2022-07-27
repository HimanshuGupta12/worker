<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use App\Models\{Worker, Hour};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (isset($_GET['worker'])) {
            $worker = Worker::where('login', $_GET['worker'])->first();
            if (!isset($worker)) {
                return redirect('/login');
            }
            session()->put('worker_id', $worker->id);
            if (!session()->has('lang')) {
                $locale = array_search ($worker->language_settings, Worker::$langs);
                session()->put('lang', $locale);
            }
        } else {
            return redirect('/logout');
        }

        $seehour = $worker->see_hours;
        $lang = session()->get('lang');
        $show_balance = $worker->tools()->needInventorization()->exists();
        $balance_storage = $worker->inventory_storage && Storage::needsInventory($worker->company);
        $tool_count = $worker->tools()->count();
        $total_price = $worker->tools()->sum('price');
        $next_balance = $worker->company->inventorization ?->nextInventorizationDate()->format(dateFormat()) ?? '-';

        // Access Control
        $modules = $worker->company->checkAccess('worker_access');
        $defaultAccess = (is_bool($modules)) ? true : false;
        // Access Control

        $now = \Carbon\Carbon::now();
        $myHours = Hour::where('worker_id', worker()->id);
        $thisweekHours = $myHours->clone()->where('work_day', '>=' , $now->clone()->startOfWeek()->format('Y-m-d'))->where('work_day', '<=' , $now->clone()->endOfWeek()->format('Y-m-d'))->sum('working_hours'). " h";
        $lastweekHours = $myHours->clone()->where('work_day', '>=' , $now->clone()->startOfWeek()->subDays(7)->format('Y-m-d'))->where('work_day', '<=' , $now->clone()->endOfWeek()->subDays(7)->format('Y-m-d'))->sum('working_hours'). " h";
        
        Worker::addLoginActivity($worker->id);
        return view('worker.dashboard', compact('show_balance', 'balance_storage', 'worker', 'tool_count', 'total_price', 'next_balance',
                'seehour', 'lang', 'modules', 'defaultAccess', 'thisweekHours', 'lastweekHours'
        ));
    }

    public function scan()
    {
        return view('scanner.worker');
    }

    public function language ()
    {
        $worker = worker();
        $lang = request('lang');
        session()->put('lang', $lang);
        $worker->update(['language_settings' => Worker::$langs[$lang]]);
        return redirect()->back();
    }
}
