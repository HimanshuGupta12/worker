<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login($worker_login_id)
    {
        $worker = Worker::where('login', $worker_login_id)->firstOrFail();
        Worker::login($worker->id);
        if (!isset($_COOKIE["login"])) {
            Worker::setWorkerLoginCookie($worker_login_id);//Set worker hash login in cookie as well.
        }
        return redirect()->route('worker', ['worker' => $worker->login]);
    }
}
