<?php

namespace App\Mail\Subscriptions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionActivationMail extends Mailable
{
    use Queueable, SerializesModels;
    
    protected $user, $stripe_active_plan_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $stripe_active_plan_name)
    {
        $this->user = $user;
        $this->stripe_active_plan_name = $stripe_active_plan_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name') . ' subscription is active')->markdown('emails.subscriptions.subscription-activated', ['user' => $this->user, 'stripe_active_plan_name' => $this->stripe_active_plan_name]);
    }
}
