@extends('layouts.worker')
@section('head')
@parent
<link href="/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
<link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />
<link href="{{ env('PUBLIC_PATH') }}/css/worker-forms.css" rel="stylesheet" type="text/css" />
@endsection
@section('header_content')
@parent
<div style="position: absolute; top: 60px; left: 0; right: 0; text-align: center;">
    <div class="font-size-20" style="color: #fff;">Report Sickness</div>
</div>
@endsection
@section('content')

<div class="container-fluid">

   <div class="row page_headingapp">
        <div class="col-1">
            <a href="{{ url()->previous() }}" class="trgr_ovrly">
                <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
        <div class="col-10">
            <h4 class="mb-0 submisn_heading step_heading">Sickness | Holidays </h4>
        </div>
        <div class="col-1"></div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="w-card_title"></h4>

                    <form id="reports" action="{{route('worker.sicknessSubmition')}}" method="POST">
                        @csrf
                            <div class="report_type form-radio-primary mb-3">

                                <label class="form-label custom_form_label">Choose repot type</label>
                                 <div class="form-check mb-1">
                                     <input class="form-check-input" type="radio" name="report" value="sickness" id="sick_radio" required/>
                                     <label class="form-check-label" for="sick_radio">Report Sickness</label>
                                 </div>
                                 <div class="form-check mb-1">
                                    <input class="form-check-input" type="radio" name="report" value="holiday"  id="holiday_radio" />
                                    <label class="form-check-label" for="holiday_radio">Request holidays / days off</label>
                                 </div>
                            </div>


                            <div class="mb-3" id="sickness" style="display: none;">
                                <label class="form-label custom_form_label">Choose the one matching best your situation*</label>

                                <div class="form-check mb-1" id="report_type">
                                        <input class="form-check-input" type="radio" name="report_type" value="one_day" id="one_day1" />
                                         <label class="form-check-label" for="one_day1">I feel sick and need to go home now </label>
                                </div>
                                <div class="form-check mb-1" id="report_type">
                                       

                                        <input class="form-check-input" type="radio" name="report_type" value="today" id="today1" />
                                         <label class="form-check-label" for="today1">I am sick and I can not come to work today</label>

                                </div>

                                <div class="form-check mb-3" id="report_type">

                                        <input class="form-check-input" type="radio" name="report_type" value="from" id="from1" />
                                         <label class="form-check-label" for="from1">Report my sick days </label>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label custom_form_label">Please explain what is wrong with you?*</label>
                                    <textarea type="text" class="form-control" name="description" value="" placeholder="Enter Description"></textarea>
                                </div>
                
                                <div class="mb-3" id="from" style="display: none;">
                                    <label class="form-label custom_form_label">I was sick from*</label>
                                    <input class="bootstrap-datepicker-inline form-control" type="date" name="date_from_sickness" id="date_from_sickness" value=""/>
                                </div>
                
                                <div class="mb-3" id="one_day">
                                    <label class="form-label custom_form_label">I was sick to*</label>
                                    <input type="date" class="form-control" name="date_to_sickness" id="date_to_sickness"  value="" />
                                </div>

                            </div>

                            <!-- holiday section start from here  -->
                            
                            <div class="mb-3" id="holiday" style="display: none;">

                                    <label class="form-label custom_form_label">Request type:*</label>
                                    <div class="form-check mb-1 request_type">
                                         <input class="form-check-input" type="radio" name="request_type" value="dates"  id="Holiday_request"/>
                                         <label class="form-check-label" for="Holiday_request">Holiday request</label>
                                    </div>
                                    <div class=" form-check mb-1 request_type">
                                        <input class="form-check-input" type="radio" name="request_type" value="request_period"  id="Days_off_request" />
                                        <label class="form-check-label" for="Days_off_request">Days off request</label>
                                    </div>
                                
                                <div class="mb-3" id="request_period" style="display: none;">
                                    <label class="form-label custom_form_label">Choose a period:*</label>

                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="radio" name="period" value="1" id="few_day"/>
                                            <label class="form-check-label" for="few_day">Few Days Off</label>
                                        </div>
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="radio" name="period" value="2" id="full_day1" />
                                            <label class="form-check-label" for="full_day1">One full day </label>
                                        </div>
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="radio" name="period" value="time" id="half_day1"/>
                                            <label class="form-check-label" for="half_day1">Half of the day</label>
                                        </div>
                                </div>

                                <div class="mb-3" id="time" style="display:none;">
                                    <label class="form-label custom_form_label" >You want to be off from:*</label>
                                    <input class="form-control" type="time" name="time_from">
                                     <label class="form-label custom_form_label" >and until:*</label>
                                    <input class="form-control" type="time" name="time_to">
                                </div>

                                <div class="mb-3" id="dates">
                                    <label class="form-label custom_form_label" >From:*</label>
                                    <input class="form-control" type="date" name="date_from_holiday" id="date_from_holiday" value=""  />
                                     <label class="form-label custom_form_label" >To:*</label>
                                    <input class="form-control" type="date" name="date_to_holiday" id="date_to_holiday" value="" />
                                </div></br>

                                <div class="mb-3" id="req_reason">
                                    <label class="form-label custom_form_label" >Reasons for request*</label>
                                    <textarea  class="form-control" type="text" name="reason_description" value=""></textarea>
                                </div>

                             </div>
                    
                                    <input type="hidden" name="date_from" id="date_from">
                                    <input type="hidden" name="date_to" id="date_to">
                            
                                <button type="submit" class="btn btn-primary js-disable">Report Sickness</button>
                            
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
        jQuery(document).ready(function () {
            jQuery("input[type=radio][name=report]").change(function () {
                if (this.value == "sickness") {
                    jQuery("#holiday").css("display", "none");
                    jQuery("#sickness").css("display", "block");
                } else if (this.value == "holiday") {
                    console.log("2");
                    jQuery("#sickness").css("display", "none");
                    jQuery("#holiday").css("display", "block");
                } 
            });
            jQuery("input[type=radio][name=report_type]").change(function () {
                if (this.value == "one_day") {
                    jQuery("#from").css("display", "none");
                    jQuery("#one_day").css("display", "block");
                } else if (this.value == "from") {
                    jQuery("#one_day").css("display", "block");
                    jQuery("#from").css("display", "block");
                } else{
                    jQuery("#from").css("display", "none");
                    jQuery("#one_day").css("display", "block");
                }
            });
            jQuery("input[type=radio][name=request_type]").change(function () {
                if (this.value == "dates") {
                    jQuery("#request_period").css("display", "none");
                    jQuery("#dates").css("display", "block");
                } else if (this.value == "request_period") {
                    jQuery("#dates").css("display", "block");
                    jQuery("#request_period").css("display", "block");
                } 
            });
            jQuery("input[type=radio][name=period]").change(function () {
                if (this.value == "time") {
                    jQuery("#time").css("display", "block");
                } else if (this.value == "1") {
                    jQuery("#time").css("display", "none");
                } else{
                    jQuery("#time").css("display", "none");
                }
            });
            jQuery("input[type=date][name=date_from_sickness]").change(function () {
                jQuery('#date_from').val(jQuery('#date_from_sickness').val());
            });
            jQuery("input[type=date][name=date_to_sickness]").change(function () {
                jQuery('#date_to').val(jQuery('#date_to_sickness').val());

            });
             jQuery("input[type=date][name=date_from_holiday]").change(function () {
                jQuery('#date_from').val(jQuery('#date_from_holiday').val());

            });
            jQuery("input[type=date][name=date_to_holiday]").change(function () {
                jQuery('#date_to').val(jQuery('#date_to_holiday').val());

            });
        });
</script>
@endsection