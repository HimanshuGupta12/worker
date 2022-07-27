<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PWAController extends Controller
{
    public function show($worker){
        $w = \App\Models\Worker::where('login', $worker)->firstOrFail();
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
        $ios = false;
        $android = false;
        if($iPod || $iPhone || $iPad  ){
            $ios = true;
        }elseif ($Android) {
            $android = true;
        }
        $manifest_url = url('/manifest/'.$worker.'/manifest.json');
        return view('worker.pwa',compact('w','manifest_url','android', 'ios'));
    }

    public function uninstall(){
        return view('worker.pwa-uninstall');
    }
}
