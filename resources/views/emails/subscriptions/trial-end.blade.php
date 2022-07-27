@component('mail::message')

@php
    $APP_NAME = config('app.name');
    $trial_ends_at = datetimeConversionTZ($user->trial_ends_at, 'user');
    $dayName = date('l', strtotime($trial_ends_at));
    $dayTime = date('H:i', strtotime($trial_ends_at));
    $TRIAL_END_MSG_AFTER_DAYS = config('constants.TRIAL_END_MSG_AFTER_DAYS');
    $TRIAL_END_ALERT_AFTER_DAYS = config('constants.TRIAL_END_ALERT_AFTER_DAYS');
    if($reminder_type == 'now') {
        $msg = "Your trial period has ended today at <b>{$dayName} {$dayTime}</b> and your access to the {$APP_NAME} panel has been limited. Your workers are still able to access their profiles and submit the hours for the next {$TRIAL_END_MSG_AFTER_DAYS} days. After {$TRIAL_END_ALERT_AFTER_DAYS} days they will start to receive a notification every time they load their profile informing them that the system has not been paid for.";
    } else {
        if($reminder_type == 'after') {
            $msg = "This is a friendly reminder that your trial of {$APP_NAME} system ended {$days} days ago.";
        } else if($reminder_type == 'before') {
            if(empty($days)) {
                $msg = "This is a friendly reminder that your trial of {$APP_NAME} system is about to end today at <b>{$dayName} {$dayTime}</b>.";
            } else {
                $msg = "This is a friendly reminder that your trial of {$APP_NAME} system is about to end in {$days} days at <b>{$dayName} {$dayTime}</b>.";
            }
        }
        $msg .= ' If you want to use the system it’s time to subscribe now.';
    }
@endphp

<h1>Hi {{ ucwords($user->name) }}</h1>
<p>
    {!! $msg !!}
</p>

<a href="{{ route('subscription.show', ['u' => base64_encode($user->email)]) }}" class="button button-blue" target="_blank" rel="noopener">Subscribe now</a>

<p>
    Once you add your credit card details and create a subscription, we will automatically charge you every month. The system will calculate how many active workers you have had last month and you will be charged according to the module you have selected and the maximum number of workers you had that month. The invoice will be sent to this email address and it can also be found under the billing section inside your worker account.
</p>

Kind regards,<br>
{{ $APP_NAME }} team<br>
Support: {{ config('constants.SUPPORT_PHONE_NUMBER') }}<br>
{{ config('constants.SUPPORT_EMAIL_ADDRESS') }}
@endcomponent