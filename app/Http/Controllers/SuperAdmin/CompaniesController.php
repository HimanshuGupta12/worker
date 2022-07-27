<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Company,Module,CompanyAccessControl,User,UserBillingDetail,Worker};
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class CompaniesController extends Controller
{

    public function index()
    {
        $company_name = isset($_GET['company_name']) ? $_GET['company_name'] : null;
        $date = (isset($_GET['date']) && $_GET['date'] != 'Select date') ? $_GET['date'] : null;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
        $user_access_type = isset($_GET['user_access_type']) ? $_GET['user_access_type'] : null;

        $companies = Company::select('id', 'name', 'disable_subscription')
        ->with([
            'user:id,name,email,phone_country,phone_no,trial_ends_at,company_id',
            'tools' => function($query) {
                $query->select('id', 'name', 'updated_at', 'company_id')->latest('updated_at')->get();
            },
            'user.subscriptions' => function($query) {
                $query->select('id', 'stripe_status', 'user_id')->latest('updated_at')->get();
            }
        ]);

        if(!empty($user_access_type)) {
            if($user_access_type == 'disabled') {
                $companies = $companies->where('disable_subscription', 1);
            } else {
                $companies = $companies->where('disable_subscription', 0);
                if($user_access_type == 'active') {
                    $companies = $companies->whereHas('user.subscriptions', function($query) {
                        $query->where('stripe_status', 'active');
                    });
                } else if($user_access_type == 'canceled') {
                    $companies = $companies->whereHas('user.subscriptions', function($query) {
                        $query->where('stripe_status', 'canceled');
                    })
                    ->whereHas('user', function($query) {
                        $query->where('trial_ends_at', '<', Carbon::now()->format("Y-m-d H:i:s"));
                    })
                    ->whereDoesntHave('user.subscriptions', function($query) {
                        $query->where('stripe_status', 'active');
                    });
                } else if($user_access_type == 'on_trial') {
                    $companies = $companies->whereHas('user', function($query) {
                        $query->where('trial_ends_at', '>', Carbon::now()->format("Y-m-d H:i:s"));
                    });
                } else if($user_access_type == 'trial_ended') {
                    $companies = $companies->whereDoesntHave('user.subscriptions')->whereHas('user', function($query) {
                        $query->where('trial_ends_at', '<', Carbon::now()->format("Y-m-d H:i:s"));
                    });
                }
            }
        }

        $companies = $companies->withCount([
            'workers',
            'workers as active_workers_count' => function ($query) {
                $query->where('status', 1);
            },
            'tools',
            'hour',
            'hour as last_week_hours' => function ($query) {
                $query->whereBetween('work_day', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
            },
            'hour as last_month_hours' => function ($query) {
                $query->whereMonth('work_day', Carbon::now()->subMonth()->month);
            },
        ])
        ->filter()->orderDefault()->paginate(100);

        // Counts
        $active_subscriptions = User::whereRelation('company', 'disable_subscription', 0)->whereRelation('subscriptions', 'stripe_status', 'active')->count();
        $canceled_subscriptions = User::whereRelation('company', 'disable_subscription', 0)
        ->where('trial_ends_at', '<', Carbon::now()->format("Y-m-d H:i:s"))
        ->whereHas('subscriptions', function($query) {
            $query->where('stripe_status', 'canceled');
        })
        ->whereDoesntHave('subscriptions', function($query) {
            $query->where('stripe_status', 'active');
        })->count();
        $on_trial = User::whereRelation('company', 'disable_subscription', 0)->where('trial_ends_at', '>', Carbon::now()->format("Y-m-d H:i:s"))->count();
        $trial_ended = User::whereRelation('company', 'disable_subscription', 0)->whereDoesntHave('subscriptions')->where('trial_ends_at', '<', Carbon::now()->format("Y-m-d H:i:s"))->count();
        $disabled_subscriptions = Company::where('disable_subscription', 1)->count();
        $active_workers = Worker::whereRelation('company', 'disable_subscription', 0)->whereRelation('company.user.subscriptions', 'stripe_status', 'active')->where('status', 1)->count();
        // Counts

        $modules = Module::getModules();

        return view('super_admin.companies.index', compact(['companies', 'company_name', 'date', 'start_date', 'end_date', 'modules', 'user_access_type', 'active_subscriptions', 'canceled_subscriptions', 'on_trial', 'trial_ended', 'disabled_subscriptions', 'active_workers']));
    }

    /*
     * Get access control settings
     */
    public function getAccessControlSettings(Request $request) {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'danger', 'message' => $validator->getMessageBag()->first()], 422);
        } else {
            try {
                $data = $request->all();
                $company_id = $data['company_id'];
                
                $checkExists = CompanyAccessControl::where('company_id', $company_id)->exists();
                if(!$checkExists) {
                    return response()->json(['status' => 'success', 'message' => 'default_access'], 200);
                }

                $settings = CompanyAccessControl::select('manager_access', 'worker_access')->where('company_id', $company_id)->first();
                if(!empty($settings)) { $settings = $settings->toArray(); }

                $settings['manager_access'] = (!empty($settings['manager_access'])) ? json_decode($settings['manager_access']) : [];
                $settings['worker_access'] = (!empty($settings['worker_access'])) ? json_decode($settings['worker_access']) : [];
                return response()->json(['status' => 'success', 'data' => $settings], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'danger', 'message' => $e->getMessage()], 400);
            }
        }
    }

    /*
     * Save access control settings
     */
    public function saveAccessControlSettings(Request $request) {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'danger', 'message' => $validator->getMessageBag()->first()], 422);
        } else {
            try {
                $data = $request->all();
                $company_id = $data['company_id'];
                $manager_access = (!empty($data['manager_access'])) ? json_encode($data['manager_access']) : NULL;
                $worker_access = (!empty($data['worker_access'])) ? json_encode($data['worker_access']) : NULL;

                CompanyAccessControl::updateOrCreate(
                    ['company_id' => $company_id],
                    ['manager_access' => $manager_access, 'worker_access' => $worker_access]
                );
                
                return response()->json(['status' => 'success', 'message' => "Settings updated successfully."], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'danger', 'message' => $e->getMessage()], 400);
            }
        }
    }

    /*
     * Subscription details
     */
    public function subscriptionDetails($company_id) {
        $company = Company::find($company_id);
        $user = $company->user;
        $active_plan = ($user->subscriptions()->active()->exists()) ? ucfirst(str_replace('_', ' ', $user->subscriptions()->active()->first()->name)) : '';
        $user_billing_detail = $user->user_billing_detail()->first();
        if(empty($user_billing_detail)) {
            $user_billing_detail = new UserBillingDetail();
        }
        $subscriptions = $user->subscriptions()->get();
        $invoices = $user->invoices();
        
        $taxIdInfo = [];
        try {
            $stripeCustomer = $user->asStripeCustomer();
            if(!empty($user_billing_detail->vat_number_stripe_id)) {
                $taxIdInfo = $user->findTaxId($user_billing_detail->vat_number_stripe_id);
            }
        } catch (\Exception $e) {
            $stripeCustomer = [];
        }
        // echo '<pre>';print_r($taxIdInfo);die;

        return view('super_admin.companies.subscription', compact(['company', 'user', 'stripeCustomer', 'active_plan', 'user_billing_detail', 'subscriptions', 'invoices', 'taxIdInfo']));
    }

    /*
     * Save free trial settings
     */
    public function freeTrial(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'trial_ends_at' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'danger', 'message' => $validator->getMessageBag()->first()], 422);
        } else {
            try {
                $data = $request->all();
                $user_id = $data['user_id'];
                $trial_ends_at = datetimeConversionTZ($data['trial_ends_at'], 'server');

                $user = User::find($user_id);
                $user->trial_ends_at = $trial_ends_at;
                $user->save();
                
                return response()->json(['status' => 'success', 'message' => "Free trial updated successfully."], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'danger', 'message' => $e->getMessage()], 400);
            }
        }
    }
    
    /*
     * Cancel subscription
     */
    public function cancelSubscription(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'danger', 'message' => $validator->getMessageBag()->first()], 422);
        } else {
            try {
                $data = $request->all();
                $user_id = $data['user_id'];

                $user = User::find($user_id);
                $plan = $user->subscriptions()->active()->first()->name;
                $user->subscription($plan)->cancelNowAndInvoice();
                
                return response()->json(['status' => 'success', 'message' => "Subscription cancelled successfully."], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'danger', 'message' => $e->getMessage()], 400);
            }
        }
    }

    /*
     * Apply coupon on user/customer
     */
    public function applyCoupon(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'danger', 'message' => $validator->getMessageBag()->first()], 422);
        } else {
            try {
                $data = $request->all();
                $user_id = $data['user_id'];
                $coupon_id = (!empty($data['coupon_id'])) ? $data['coupon_id'] : '';

                $user = User::find($user_id);
                $user->applyCoupon('');
                $user->applyCoupon($coupon_id);
                
                return response()->json(['status' => 'success', 'message' => "Coupon settings updated successfully."], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'danger', 'message' => $e->getMessage()], 400);
            }
        }
    }

    /*
     * Create stripe customer for a user/company
     */
    public function createStripeCustomer(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'danger', 'message' => $validator->getMessageBag()->first()], 422);
        } else {
            try {
                $data = $request->all();
                $user_id = $data['user_id'];

                $user = User::find($user_id);
                $user->createAsStripeCustomer();
                
                return response()->json(['status' => 'success', 'message' => "Stripe customer created successfully."], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'danger', 'message' => $e->getMessage()], 400);
            }
        }
    }

    /*
     * Get subscription details from stripe
     */
    public function stripeSubscriptionDetails(Request $request) {
        $validator = Validator::make($request->all(), [
            'subscription_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'danger', 'message' => $validator->getMessageBag()->first()], 422);
        } else {
            try {
                $data = $request->all();
                $subscription_id = $data['subscription_id'];
                $subscription_name = $data['subscription_name'];
                $subscription_item_id = $data['subscription_item_id'];

                try {
                    $key = \config('services.stripe.secret');
                    $stripe = new \Stripe\StripeClient($key);
                    $subscription = $stripe->subscriptions->retrieve( $subscription_id, [] );
                    $usageList = $stripe->subscriptionItems->allUsageRecordSummaries( $subscription_item_id, [] );
                    $total_usage = (!empty($usageList->data[0]->total_usage)) ? $usageList->data[0]->total_usage : '';
                } catch (\Exception $e) {
                    $subscription = [];
                    $total_usage = '';
                }
                
                $html = view('super_admin.companies.subscription-detail', compact('subscription', 'subscription_name', 'total_usage'))->render();
                return response()->json(['status' => 'success', 'data' => $html], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'danger', 'message' => $e->getMessage()], 400);
            }
        }
    }

    /*
     * Set subscription on/off for a company
     */
    public function setSubscriptionStatus(Request $request) {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'disable_subscription' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'danger', 'message' => $validator->getMessageBag()->first()], 422);
        } else {
            try {
                $data = $request->all();
                $company_id = $data['company_id'];
                $disable_subscription = $data['disable_subscription'];

                $action = (!empty($disable_subscription)) ? 'disabled' : 'enabled';
                Company::where('id', $company_id)->update(['disable_subscription' => $disable_subscription]);
                
                return response()->json(['status' => 'success', 'message' => "Subscription {$action}"], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'danger', 'message' => $e->getMessage()], 400);
            }
        }
    }

    /*
     * Run trial notification command manually
     */
    public function runTrialEndNotificationCommand() {
        try {
            Artisan::call('subscriptions:trial_ending_notification');
            // echo '<pre>';print_r(Artisan::output());die;
            return redirect()->route('sa.companies.index')->with('success', 'Trial end notification command ran successfully.');
        } catch (\Exception $e) {
            return redirect()->route('sa.companies.index')->with('danger', $e->getMessage());
        }
    }

}
