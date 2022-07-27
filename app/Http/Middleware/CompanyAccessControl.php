<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CompanyAccessControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $type = '')
    {
        $route_name = Route::currentRouteName();
        self::clearToolHomeLink($route_name);
        if(!empty($route_name)) {
            if(empty($type)) {
                try {
                    if(!empty(user())) {
                        $type = 'manager_access';
                    } else if(\App\Models\Worker::isLoggedIn()) {
                        $type = 'worker_access';
                    } else {
                        return redirect('/login');
                    }
                } catch(\Exception $e) {}
            }

            if(!empty($type)) {
                if($type == 'manager_access') {
                    $checkAccess = user()->company->checkAccess($type, $route_name);
                } else if($type == 'worker_access') {
                    $checkAccess = worker()->company->checkAccess($type, $route_name);
                }
                if(!$checkAccess) {
                    if($route_name == 'hours.index') {
                        return redirect(route('tools.index'));
                    }
                    abort(401);
                }
            }
        }

        return $next($request);
    }
    
    private static function clearToolHomeLink($route_name)
    {
        if (strpos($route_name, 'tools.') !== false) {
            \App\Models\Tool::forgetHomeLink();
        }
    }
}
