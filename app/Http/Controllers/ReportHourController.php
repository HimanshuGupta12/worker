<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportHourController extends Controller
{
    public function index()
    {
    	return view('reports.hours-report');
    }
}
