<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\View;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        
        Cashier::calculateTaxes();

//        if($this->app->environment('production')) {
//            \URL::forceScheme('https');
//        }

        if ($request->server->has('HTTP_X_ORIGINAL_HOST')) {
            $request->server->set('HTTP_X_FORWARDED_HOST', $request->server->get('HTTP_X_ORIGINAL_HOST'));
            $request->headers->set('X_FORWARDED_HOST', $request->server->get('HTTP_X_ORIGINAL_HOST'));
        }

        Paginator::useBootstrap();

        // ngrock

//        if (request()->server->has('HTTP_X_ORIGINAL_HOST')) {
//            request()->server->set('HTTP_HOST', request()->server->get('HTTP_X_ORIGINAL_HOST'));
//            request()->headers->set('HOST', request()->server->get('HTTP_X_ORIGINAL_HOST'));
//            \URL::forceScheme('https');
//        }
        
        View::composer('*', function ($view) {
            $user = [];
            $user_type = '';
            if(Auth::guard('web')->check()) {
                $user = user();
                $user_type = 'manager';
            } else if(\App\Models\Worker::isLoggedIn()) {
                $user = worker()->company->user;
                $user_type = 'worker';
            }
            if(!empty($user)) {
                // Manager Access
                $managerModules = $user->company->checkAccess('manager_access');
                $managerDefaultAccess = (is_bool($managerModules)) ? true : false;
                // Manager Access

                // Worker Access
                $workerModules = $user->company->checkAccess('worker_access');
                $workerDefaultAccess = (is_bool($workerModules)) ? true : false;
                // Worker Access

                $disable_subscription = $user->company->disable_subscription;// If true, subscription checks are not applied for the company
                // Subscription Access
                $worker_for_hours_access = $worker_for_hours_access_W = ($user->subscribed(config('constants.WORKER_FOR_HOURS')) || $user->subscribed(config('constants.WORKER_FOR_HOURS_AND_TOOLS')) || $user->onTrial() || $disable_subscription) ? true : false;
                $worker_for_tools_access = $worker_for_tools_access_W = ($user->subscribed(config('constants.WORKER_FOR_TOOLS')) || $user->subscribed(config('constants.WORKER_FOR_HOURS_AND_TOOLS')) || $user->onTrial() || $disable_subscription) ? true : false;
                // Subscription Access

                $subscription_alert = (!$user->onTrial() && !$user->checkActiveSubscription() && !$disable_subscription) ? true : false;

                $subscription_alert_W = $subscription_message_W = false;
                $days_left_W = 0;
                if($user_type == 'worker' && $subscription_alert) {// For worker app
                    if(!empty($user->trial_ends_at)) {
                        $trial_ends_at = date('Y-m-d', strtotime($user->trial_ends_at));
                        $days = round((time() - strtotime($trial_ends_at)) / 86400);
                        if($days <= config('constants.TRIAL_END_MSG_AFTER_DAYS')) {
                            $worker_for_hours_access_W = $worker_for_tools_access_W = true;
                        }
                        if($days > config('constants.TRIAL_END_MSG_AFTER_DAYS')) {// Show subscription message
                            $subscription_message_W = true;
                        } else if($days > config('constants.TRIAL_END_ALERT_AFTER_DAYS')) {// Show subscription alert popup
                            $subscription_alert_W = true;
                            $days_left_W = config('constants.TRIAL_END_MSG_AFTER_DAYS') - $days;
                        }
                    }
                }

                $view->with(compact('managerDefaultAccess', 'managerModules', 'workerDefaultAccess', 'workerModules', 'worker_for_hours_access', 'worker_for_tools_access', 'subscription_alert', 'disable_subscription', 'worker_for_hours_access_W', 'worker_for_tools_access_W', 'subscription_alert_W', 'subscription_message_W', 'days_left_W'));
            }
        });

    }
}
