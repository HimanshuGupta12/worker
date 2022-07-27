<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class SubscriptionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $plan)
    {
        $route_name = Route::currentRouteName();
        $user = [];
        if (Auth::check()) {
            $user = user();
            $user_type = 'manager';
        } else if(\App\Models\Worker::isLoggedIn()) {
            $user = worker()->company->user;
            $user_type = 'worker';
        } else {
            return redirect('/login');
        }

        $worker_for_access = false;
        if($user_type == 'worker' && !$user->onTrial() && !$user->checkActiveSubscription() && !$user->company->disable_subscription) {// For worker app
            if(!empty($user->trial_ends_at)) {
                $trial_ends_at = date('Y-m-d', strtotime($user->trial_ends_at));
                $days = round((time() - strtotime($trial_ends_at)) / 86400);
                if($days <= config('constants.TRIAL_END_MSG_AFTER_DAYS')) {
                    $worker_for_access = true;
                }
            }
        }

        if (!empty($user) && !$user->subscribed($plan) && !$user->subscribed(config('constants.WORKER_FOR_HOURS_AND_TOOLS')) && !$user->onTrial() && !$user->company->disable_subscription && !$worker_for_access) {
            if($user_type == 'worker') {
                abort(401);
            }
            if($route_name == 'hours.index') {
                return redirect(route('tools.index'));
            }
            return redirect(route('subscription.show'))->with('danger', 'Unsubscribed access. Please review your subscription.');
        }

        return $next($request);
    }
}
