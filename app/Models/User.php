<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use function Illuminate\Events\queueable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    // ---------------------------------------- relationships ----------------------------------------------------------

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user_billing_detail()
    {
        return $this->hasOne(UserBillingDetail::class);
    }

    // --------------------------------- methods -----------------------------------------------------------------------

    /**
     * Get the customer email that should be synced to Stripe.
     *
     * @return string|null
     */
    public function stripeEmail()
    {
        return (!empty($this->user_billing_detail->email)) ? $this->user_billing_detail->email : $this->email;
    }

    /**
     * Get the customer name that should be synced to Stripe.
     *
     * @return string|null
     */
    public function stripeName()
    {
        return (!empty($this->user_billing_detail->company_name)) ? $this->user_billing_detail->company_name : $this->company->name;
    }
    
    /**
     * Get the customer phone that should be synced to Stripe.
     *
     * @return string|null
     */
    public function stripePhone()
    {
        return (!empty($this->user_billing_detail->phone_number)) ? $this->user_billing_detail->phone_number : $this->phone_no;
    }
    
    /**
     * Get the customer address that should be synced to Stripe.
     *
     * @return string|null
     */
    public function stripeAddress()
    {
        $address = ['country' => $this->country_code];
        if(!empty($this->user_billing_detail->address_line)) {
            $address = [
                'line1' => $this->user_billing_detail->address_line,
                'city' => $this->user_billing_detail->city,
                // 'state' => $this->user_billing_detail->state,
                'country' => $this->user_billing_detail->country,
                'postal_code' => $this->user_billing_detail->postal_code,
            ];
        }
        return $address;
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updated(queueable(function ($user) {
            if ($user->hasStripeId()) {
                $user->syncStripeCustomerDetails();
            }
        }));
    }

    /*
     * Check if user has active plan
     */
    public function checkActiveSubscription() {
        return $this->subscriptions()->active()->exists();
    }

    /*
     * Report usage(worker) for metered billing
     */
    public function reportUsage() {
        if ($this->hasStripeId() && !$this->onTrial() && $this->checkActiveSubscription()) {
            $usage = $this->activeWorkersCount();
            $plan = $this->subscriptions()->active()->first()->name;
            $this->subscription($plan)->reportUsage($usage);
        }
    }

    /*
     * Retrun active workers count
     */
    public function activeWorkersCount() {
        return $this->company()->first()->workers()->where('status', 1)->count();
    }

    /*
     * Check workers access
     */
    public function checkWorkerAccess() {
        return (!$this->onTrial() && !$this->subscriptions()->active()->count()) ? false : true;
    }
  
}
