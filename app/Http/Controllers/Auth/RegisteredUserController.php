<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Worker;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Twilio\Rest\Client;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $worker = new Worker();
        return view('auth.register', compact('worker'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_no' => 'required|numeric|unique:users',
            'phone_country' => 'required|numeric',
            'password' => 'required|string|confirmed|min:8',
            'company' => 'required|string|max:255',
            'country_code' => 'required',

        ]);

        self::validatePhone($request->phone_country, $request->phone_no);
        
        $company = Company::createCompany($request->company);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'phone_country' => $request->phone_country,
            'password' => Hash::make($request->password),
            'company_id' => $company->id,
            'country_code' => $request->country_code,
            'trial_ends_at' => now()->addDays(config('constants.FREE_TRIAL_DAYS')),
        ]);

        try {
            $user->createAsStripeCustomer();
        } catch (\Exception $e) {}

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
    
    private static function validatePhone($country, $phone)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $twilio = new Client($sid, $token);

        $phone = '+' . $country . $phone;
        try {
            $phone_number = $twilio->lookups->v1->phoneNumbers($phone)->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            throw ValidationException::withMessages(['Invalid phone number format']);
        }
    }
}
