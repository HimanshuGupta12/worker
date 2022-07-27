<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;
use App\Models\UserBillingDetail;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscriptions\SubscriptionActivationMail;

class SubscriptionController extends Controller
{
    
    /*
     * Get stripe plans/produts
     */
    public function retrievePlans() {
        try {
            $key = \config('services.stripe.secret');
            $stripe = new \Stripe\StripeClient($key);
            $plansraw = $stripe->plans->all(['active' => true]);
            $plans = $plansraw->data;
            
            foreach($plans as $p => $plan) {
                $prod = $stripe->products->retrieve(
                    $plan->product,[]
                );
                if(!empty($prod->active)) {
                    $plan->product = $prod;
                } else {
                    unset($plans[$p]);
                }
            }
            usort($plans, function($a, $b) { return $a->product->metadata->order_position - $b->product->metadata->order_position; });
            return $plans;
        } catch (\Exception $e) {
            return [];
        }
    }

    /*
     * Show subscriptions
     */
    public function showSubscription() {
        $plans = $this->retrievePlans();
        $user = user();
        $plan = $user->subscriptions()->active()->first();
        $plan_name = (!empty($plan)) ? $plan->name : '';
        $user_billing_detail = $user->user_billing_detail()->first();
        if(empty($user_billing_detail)) {
            $user_billing_detail = new UserBillingDetail();
        }
        $taxIdInfo = $upcomingInvoice = [];
        try {
            $stripeCustomer = $user->asStripeCustomer();
            if(!empty($plan_name)) {
                $upcomingInvoice = $user->subscription($plan_name)->upcomingInvoice()->toArray();
            }
            if(!empty($user_billing_detail->vat_number_stripe_id)) {
                $taxIdInfo = $user->findTaxId($user_billing_detail->vat_number_stripe_id);
            }
        } catch (\Exception $e) {
            $stripeCustomer = [];
        }
        
        // echo '<pre>';print_r($plans);die;
        return view('subscription.subscribe', [
            'user' => $user,
            'intent' => $user->createSetupIntent(),
            'plans' => $plans,
            'plan_name' => $plan_name,
            'user_billing_detail' => $user_billing_detail,
            'taxIdInfo' => $taxIdInfo,
            'upcomingInvoice' => $upcomingInvoice,
            'stripeCustomer' => $stripeCustomer
        ]);
    }

    /*
     * Process subscriptions
     */
    public function processSubscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan' => 'required',
            // 'plan_name' => 'required',
            'payment_method' => 'required_with:edit_pm_details',
            'address_line' => 'required',
            'city' => 'required',
            // 'state' => 'required',
            'country' => 'sometimes|required',
            'postal_code' => 'required',
            'email' => 'nullable|string|email|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $user = user();
            $plan = $request->input('plan');
            $plan_name = $request->input('plan_name');
            $paymentMethod = $request->input('payment_method', NULL);
            $prev_plan_name = $request->input('prev_plan_name', NULL);
            $edit_pm_details = $request->input('edit_pm_details', NULL);
            $stripe_active_plan_name = $request->input('stripe_active_plan_name', NULL);
            $coupon_code = $request->input('coupon_code', NULL);

            // Biiling Details
            $b_data['user_id'] = $user->id;
            $b_data['company_name'] = $request->input('company_name', NULL);
            $b_data['email'] = $request->input('email', NULL);
            $b_data['address_line'] = $request->input('address_line');
            $b_data['city'] = $request->input('city');
            // $b_data['state'] = $request->input('state', NULL);
            $b_data['country'] = $request->input('country', NULL);
            $b_data['postal_code'] = $request->input('postal_code');
            $b_data['phone_number'] = $request->input('phone_number', NULL);
            $b_data['phone_country'] = $request->input('phone_country', NULL);
            
            $vat_number = $request->input('vat_number', NULL);
            $prev_vat_number = $request->input('prev_vat_number', NULL);
            $vat_number_stripe_id = $request->input('vat_number_stripe_id', NULL);
            $vat_type = 'eu_vat';

            $company_number = $request->input('company_number', NULL);
            $prev_company_number = $request->input('prev_company_number', NULL);

            $checkBillingExists = UserBillingDetail::where('user_id', $user->id)->exists();
            if($checkBillingExists) {// Update
                unset($b_data['country']);
                $billing_update = UserBillingDetail::where('user_id', $user->id)->update($b_data);
            } else {// Add
                $billing_update = UserBillingDetail::where('user_id', $user->id)->insertGetId($b_data);
            }
            
            $user->createOrGetStripeCustomer();

            try {
                if($vat_number != $prev_vat_number) {
                    if(!empty($vat_number_stripe_id)) {
                        $user->deleteTaxId($vat_number_stripe_id);
                    }
                    if(!empty($vat_number)) {
                        $createTaxId = $user->createTaxId($vat_type, $vat_number);
                        $vat_number_stripe_id = $createTaxId->id;
                    } else {
                        $vat_number_stripe_id = NULL;
                    }
                    UserBillingDetail::where('user_id', $user->id)->update(['vat_type' => $vat_type, 'vat_number' => $vat_number, 'vat_number_stripe_id' => $vat_number_stripe_id]);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['message' => 'Error creating tax ID: ' . $e->getMessage()]);
            }

            // Company number
            try {
                if($company_number != $prev_company_number) {
                    if(!empty($company_number)) {
                        $user->updateStripeCustomer([ 'invoice_settings' => [ "custom_fields" =>  [ ["name"=>"Number", "value"=>$company_number] ] ] ]);
                    } else {
                        $user->updateStripeCustomer([ 'invoice_settings' => [ "custom_fields" =>  "" ] ]);
                    }
                    UserBillingDetail::where('user_id', $user->id)->update(['company_number' => $company_number]);
                }
            } catch (\Exception $e) {}
            // Company number

            $user->syncStripeCustomerDetails();// Update stripe
            // Billing Details

            if($user->subscribedToPrice($plan, $plan_name) && empty($edit_pm_details) && empty($billing_update)) {
                return redirect()->back()->with('danger', 'You are already subscribed to this plan.');
            }

            if(!empty($paymentMethod)) {
                $user->updateDefaultPaymentMethod($paymentMethod);
            } else {
                $paymentMethod = $user->defaultPaymentMethod()->id;
            }
            try {
                // Coupon code
                if(!empty($coupon_code)) {
                    $user->applyCoupon('');
                    $user->applyCoupon($coupon_code);
                } else {
                    $user->applyCoupon('');
                }
                // Coupon code
                if($prev_plan_name != $plan_name) {
                    if(!empty($prev_plan_name)) {
                        $user->subscription($prev_plan_name)->cancelNowAndInvoice();
                    }
                    $user->newSubscription($plan_name)->meteredPrice($plan)->create($paymentMethod);
                    // End Trial
                    if(!empty($user->trial_ends_at) && $user->trial_ends_at > now()) {
                        $user->trial_ends_at = now();
                        $user->save();
                    }
                    // End Trial
                    Mail::to($user->company->user)->send(new SubscriptionActivationMail($user, $stripe_active_plan_name));
                }
            } catch (\Exception $e) {
                return back()->withErrors(['message' => 'Error creating subscription: ' . $e->getMessage()]);
            }
            
            return redirect(route('subscription.show'))->with('success', 'Subscription settings updated successfully.');
        }
    }
    
    /*
     * Start 30 days free trial
     */
    public function startFreeTrial() {
        try {
            $free_trial_days = config('constants.FREE_TRIAL_DAYS');
            user()->trial_ends_at = now()->addDays($free_trial_days);
            user()->save();
            return response()->json(['status' => 'success', 'message' => 'Your ' . $free_trial_days . ' days free trial starts.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'danger', 'message' => $e->getMessage()], 400);
        }
    }

    /*
     * Cancel subscription
     */
    public function cancelSubscription() {
        try {
            $plan = user()->subscriptions()->active()->first()->name;
            user()->subscription($plan)->cancelNowAndInvoice();
            return response()->json(['status' => 'success', 'message' => 'Subscription cancelled successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'danger', 'message' => $e->getMessage()], 400);
        }
    }

    /*
     * Show Invoices
     */
    public function showInvoice() {
        $invoices = user()->invoices();
        
        return view('subscription.invoice', compact('invoices'));
    }

}
