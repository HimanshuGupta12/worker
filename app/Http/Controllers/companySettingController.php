<?php

namespace App\Http\Controllers;
use App\Models\{Company,User};
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Twilio\Rest\Client;

class companySettingController extends Controller
{
    private static $rules = [
        'name' => 'required',
        'phone_country' => 'required',
        'phone_no' => 'required',
        'logo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        'company_address' => 'required',
        'company_registration' => 'required',
        'country_code' => 'required',
        'notification_email' => 'email|nullable',
    ];
    
    public static function emailRule ($user)
    {
        return [
            'user_email' => 'email|unique:App\Models\User,email,'.$user->id
        ];
    }
    
    public function index()
    {
        $user = user();
        $company = $user->company;
        return view('companySetting.index',compact('user','company'));
    }

    public function settingSubmition()
    {
        try {
            $company_id = request('company_id');
            $company_logo = user()->company;
            $company_old_logo = $company_logo->logo;
            $user_id = request('user_id');

            $company = Company::findOrFail($company_id);
            $user = User::findOrFail($user_id);

            $v = request()->validate(self::$rules);

            self::validatePhone($v['phone_country'], $v['phone_no']);

            $v_email = request()->validate(self::emailRule(user()));
            $user_data = ['phone_country'=>$v['phone_country'], 'phone_no'=>$v['phone_no'], 'email' => $v_email['user_email'], 'country_code'=>$v['country_code']];
            $user->update($user_data);
            $company_data = ['name'=>$v['name'],
                             'company_address'=>$v['company_address'],
                             'company_registration_number'=>$v['company_registration'],
                             'notification_email'=>$v['notification_email']
                            ];
            $company->update($company_data);
            if($company_old_logo && isset($v['logo'])){
                $company->deleteLogo($company_old_logo);
            }
            if (isset($v['logo'])) {
                $company->updateLogo($v['logo']);
            }
            return redirect()->back()
            ->with('success', 'updated successfully.');
        } catch (\Exception $ex) {
            \Illuminate\Support\Facades\Log::info($ex->getMessage(). print_r(request()->all(), true));
            throw ValidationException::withMessages(['Phone number already used by another company. Please use a different phone.']);
        }
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
