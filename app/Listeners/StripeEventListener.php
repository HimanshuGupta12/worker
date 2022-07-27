<?php

namespace App\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use App\Models\User;

class StripeEventListener
{
    /**
     * Handle received Stripe webhooks.
     *
     * @param  \Laravel\Cashier\Events\WebhookReceived  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        try {
            $stripeCustomerID = $event->payload['data']['object']['customer'];
            $user = User::where('stripe_id', $stripeCustomerID)->firstOrFail();
            if(!empty($user)) {
                if ($event->payload['type'] === 'invoice.payment_succeeded') {
                    if ( in_array($event->payload['data']['object']['billing_reason'], ['subscription_cycle', 'subscription_create']) ) {
                        $user->reportUsage();
                    }
                }/* else if ($event->payload['type'] === 'customer.tax_id.updated') {
                    if ( in_array($event->payload['data']['object']['country'], ['SE', 'LT']) ) {
                        $verification_status = $event->payload['data']['object']['verification']['status'];
                        $tax_exempt = ($verification_status == 'verified') ? 'exempt' : 'none';
                        $user->updateStripeCustomer([ 'tax_exempt' => $tax_exempt ]);
                    }
                } else if ($event->payload['type'] === 'customer.tax_id.deleted') {
                    if ( $event->payload['data']['object']['country'] != 'NO' ) {
                        $user->updateStripeCustomer([ 'tax_exempt' => 'none' ]);
                    }
                }*/
            }
        } catch (\Exception $e) {

        }
    }
}
