@extends('layouts.worker')
@section('head')
@parent
<link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />
<link href="{{ env('PUBLIC_PATH') }}/css/worker-forms.css" rel="stylesheet" type="text/css" />
@endsection
@section('header_content')
@parent
<div style="position: absolute; top: 60px; left: 0; right: 0; text-align: center;">
    <div class="font-size-20" style="color: #fff;">{{ __('Report Sickness') }}</div>
</div>
@endsection
@section('content')

<div class="container-fluid">

    <div class="row page_headingapp">
        <div class="col-1">
            <a href="{{ url()->previous() }}">
            <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </a>
        </div>
        <div class="col-10">
            <h4 class="mb-0 submisn_heading step_heading"> {{ __('Sickness') }} </h4>
        </div>
        <div class="col-1"></div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-body">
                    <form class="managerside_form" action="{{route('sickworker.sicknessSubmition')}}" method="POST">
                        @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label custom_form_label">{{ __('Please explain what is wrong with you?') }}</label>
                                        <textarea type="text" class="form-control" name="description" value="" placeholder="{{ __('Enter Description') }}"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label custom_form_label">{{ __('I was sick from') }}*</label>
                                        <input class="form-control @error('date_from') is-invalid @enderror" type="date" name="date_from" id="date_from" value=""/>
                                        @error('date_from')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label custom_form_label">{{ __('I was sick to') }}*</label>
                                        <input class="form-control @error('date_to') is-invalid @enderror" type="date" name="date_to" id="date_to" value=""/>
                                        @error('date_from')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div> 
                        <button type="submit" class="btn btn-primary js-disable">{{ __('Report Sickness') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection