<link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />
@extends('errors::minimal')
<div class="row page_headingapp">
    <div class="col-1"></div>
    <div class="col-10">
        <h4 class="mb-0 submisn_heading step_heading">{{ __('Page Expired') }}</h4>
    </div>
    <div class="col-1">
        <a href="{{ route('login') }}">
            <button type="button" class="btn-error-layout">Login</button>
        </a>
    </div>
</div>
@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('This page has expired. Please login to continue.'))
