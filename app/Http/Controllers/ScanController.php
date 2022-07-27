<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ScanController extends Controller
{
    public function scan()
    {
        if (!request('redirect')) {
            abort(400, 'No redirect URL');
        }
        $redirect = request('redirect');
        $back = request('back');
        $translations = js_translations(App::getLocale());
        return view('scanner.default', compact('redirect', 'back', 'translations'));
    }

    public function workerInventory()
    {
        $redirect = request('redirect');
        $translations = js_translations(App::getLocale());
        return view('scanner.worker_inventory', compact('redirect', 'translations'));
    }
}
