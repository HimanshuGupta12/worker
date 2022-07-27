@extends('layouts.worker')

@section('head')
<meta name="_token" content="{{ csrf_token() }}">
<meta name="viewport" 
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

@parent
<link href="{{ env('PUBLIC_PATH') }}/css/worker-hours/worker-custom.css" rel="stylesheet" type="text/css" />
<link href="{{ env('PUBLIC_PATH') }}/css/worker.hoursapp.css" rel="stylesheet" type="text/css" />
<link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />
<link href="{{ env('PUBLIC_PATH') }}/css/worker-reports.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">

    <!-- <div class="row page_headingapp">
        <div class="col-1">
            <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="col-10">
            <h4 class="mb-0 submisn_heading step_heading"> Holidays </h4>
        </div>
        <div class="col-1"></div>
    </div> -->


    <div class="row page_headingapp">
        <div class="col-1">
            <a href="{{ url()->previous() }}" class="trgr_ovrly">
            <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </a>
        </div>
        <div class="col-10">
            <h4 class="mb-0 submisn_heading step_heading">{{ __('Holidays') }}</h4>
        </div>
        <div class="col-1"></div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-body">
        <form class="managerside_form" action="{{route('holidaySubmition')}}" method="post" enctype="multipart/form-data" >
        @csrf
<!-- period start-->
                <!-- <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <input id="" class="form-check-input" type="radio" name="leave_duration" value="Few Days Off">
                            <label for="" class="form-check-label">Few Days Off</label><br>
                            <input id="" class="form-check-input" type="radio" name="leave_duration" value="One full day">
                            <label for="" class="form-check-label">One full day</label></br>
                            <input id="" class="form-check-input" type="radio" name="leave_duration" value="requested_time">
                            <label for="" class="form-check-label">Half of the day</label>
                        </div>
                    </div>
                </div> -->
<!-- period end -->
<!-- time start -->

        <!--<div class="row" id="requested_time">-->
            <!--<div class="row">-->
            <!--    <div class="col-md-12">-->
            <!--        <div class="mb-3">-->
            <!--            <label class="form-label custom_form_label">you want to be off from:*</label>-->
            <!--            <input type="time" name="time_from" class="form-control @error('time_from') is-invalid @enderror">-->
            <!--            @error('time_from')-->
            <!--                <span class="invalid-feedback" role="alert">-->
            <!--                    <strong>{{ $message }}</strong>-->
            <!--                </span>-->
            <!--            @enderror-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="row">-->
            <!--    <div class="col-md-12">-->
            <!--        <div class="mb-3">-->
            <!--        <label class="form-label custom_form_label">and until:*</label>-->
            <!--            <input type="time" name="time_to" class="form-control @error('time_to') is-invalid @enderror">-->
            <!--            @error('time_to')-->
            <!--                <span class="invalid-feedback" role="alert">-->
            <!--                    <strong>{{ $message }}</strong>-->
            <!--                </span>-->
            <!--            @enderror-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>    -->
        <!--</div> -->
<!-- time end -->
<!-- date start -->
        <div class="row" id="requested_date">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label custom_form_label">{{ __('Holiday from') }}:*</label>
                        <input class="form-control @error('date_from') is-invalid @enderror" type="date" name="date_from" id="date_from" value=""/>
                        @error('date_from')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label custom_form_label">{{ __('Holiday to') }}:*</label>
                        <input class="form-control @error('date_to') is-invalid @enderror" type="date" name="date_to" id="date_to" value=""/>
                        @error('date_to')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div> 
        <div class="row" id="reason_description">
            <div class="row"> 
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label custom_form_label">{{ __('Reasons for request') }}</label>
                        <textarea type="text" class="form-control" name="description" value="" placeholder="{{ __('Enter Description') }}"></textarea>
                    </div>
                </div>
            </div>
        </div>
<!-- date end -->
            <button type="submit" class="btn btn-primary js-disable">{{ __('Report Holiday') }}</button>
        </form>
        </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent

@endsection