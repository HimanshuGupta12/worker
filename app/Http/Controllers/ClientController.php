<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Validation\ValidationException;
use Twilio\Rest\Client as TwilioClient;

class ClientController extends Controller
{
    private static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email:rfc,dns',
        'phone_country' => 'nullable|integer',
        'phone_number' => 'nullable|integer',
        'type' => 'nullable|string|max:255',
        'company_name' => 'required_if:type,==,business|max:255',
        'company_org_no' => 'required_if:type,==,business|max:255',
        'street' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'postcode' => 'nullable|string|max:255',
        'additional_note' => 'nullable|string'
    ];

    public function index()
    {
        $search_clients = user()->company->clients()->orderDefault()->get();
        $clients = user()->company->clients()->filter()->orderDefault()->paginate(25);

        return view('clients.index', compact('clients', 'search_clients'));
    }

    public function create()
    {
        $client = new Client();
        $url = route('clients.store');

        return view('clients.create_or_edit', compact('client', 'url'));
    }

    public function store()
    {
        $v = request()->validate(self::$rules);
        self::validatePhone($v['phone_country'], $v['phone_number']);

        $data = ['company_id' => user()->company->id] + $v;
        //\Illuminate\Support\Facades\Log::info(print_r([$v, $data], true));
        $client = Client::create($data);
        $success = 'saved';
//        if ($client->phone()) {
//            $text = 'Hello ' . $client->name . ',
//
//Welcome to WorkerNU tools management system.
//You can access your profile by clicking on this link:
//
//' . $client->clientLink();
//            sms($client->phone(), $text);
//            $success = 'SMS with a login link was sent to the client';
//        }

        if (request('redirect_url')) {
            $clients = user()->company->clients()->orderDefault()->get()->toArray();
            return ['success'=>true, 'client_id' => $client->id, 'clients'=>$clients];
        }
        return redirect()->route('clients.index')->with('success', $success)->withInput(request()->input());
    }

    public function edit($client_id)
    {
        $client = user()->company->clients()->find($client_id);
        $url = route('clients.update', $client->id);

        return view('clients.create_or_edit', compact('client', 'url'));
    }

    public function update($client_id)
    {
        $client = user()->company->clients()->find($client_id);

        $v = request()->validate(self::$rules);
        self::validatePhone($v['phone_country'], $v['phone_number']);
        $client->update($v);

        return redirect()->route('clients.index')->with('success', 'saved');
    }

    public function destroy($client_id)
    {
        $client = user()->company->clients()->find($client_id);
        if ($client->trashed()) {
            abort(400, 'Already deleted');
        }
        $client->delete();

        return back()->with('success', 'deleted');
    }
    
    private static function validatePhone($country, $phone)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $twilio = new TwilioClient($sid, $token);

        $phone = '+' . $country . $phone;
        try {
            $phone_number = $twilio->lookups->v1->phoneNumbers($phone)->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            throw ValidationException::withMessages(['Invalid phone number format']);
        }
    }
}
