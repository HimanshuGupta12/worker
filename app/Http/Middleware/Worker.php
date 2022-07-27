<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\App;
use App\Models\Worker as Worker_modal;

class Worker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Worker_modal::isLoggedIn()) {
            if (request('worker')) {
                return redirect()->route('worker.login', request('worker'));
            }elseif(isset($_COOKIE["login"])) {
                $worker = Worker_modal::where('login', Worker_modal::getWorkerLoginCookie())->firstOrFail();
                Worker_modal::login($worker->id);//Did manual login to avoid un-necessory redirects to login page again & again.
                return $next($request);
            }
            return redirect()->back();
        }

        /* Set worker locale */
        $langs = Worker_modal::$langs;
        if (session()->missing('lang')) {
            $worker = worker();
            session()->put('lang', array_search($worker->language_settings, $langs)); //array_search: Search value in associative array & return the key.
        }
        $workerLang = session()->get('lang');
        $locale = array_key_exists($workerLang, $langs);
        if ($locale) {
            App::setLocale($workerLang);
        }
        
        return $next($request);
    }
}
