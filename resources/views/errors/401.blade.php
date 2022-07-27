<link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />

@extends('errors::minimal')
<div class="row page_headingapp">
    <div class="col-1" id="normal-back">
        <a href="{{ url()->previous() }}" class="trgr_ovrly">
        <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        </a>
    </div>
    <div class="col-10">
        <h4 class="mb-0 submisn_heading step_heading">{{ __('Un-authorized Access') }}</h4>
    </div>
    <div class="col-1"></div>
</div>
@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('You are not permitted to view this page. Please go back to your home page!'))
