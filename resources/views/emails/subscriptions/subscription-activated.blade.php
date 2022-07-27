@component('mail::message')

<h1>Hi {{ ucwords($user->name) }}</h1>
<p>Your subscription plan {!! (!empty($stripe_active_plan_name)) ? '(<b>' . $stripe_active_plan_name . '</b>)' : '' !!} has been activated.</p>
<p>You can manage your subscriptions <a href="{{ route('subscription.show') }}">here</a>.</p>

Thanks,<br>
{{ config('app.name') }} team<br>
Support: {{ config('constants.SUPPORT_PHONE_NUMBER') }}<br>
{{ config('constants.SUPPORT_EMAIL_ADDRESS') }}
@endcomponent