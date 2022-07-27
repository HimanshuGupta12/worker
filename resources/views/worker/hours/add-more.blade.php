@extends('layouts.worker-hours-submitted')
@section('head')
    @parent
    <link href="{{ env('PUBLIC_PATH') }}/css/worker.hoursapp.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        
        .sumitted_heading {
            color: #fff;
            font-size: 30px;
            font-weight: 600;
            padding-left: 7px;
            padding-right: 7px;
        }

        .icon_txt_wpr.text-center {
            margin-top: 14%;
            margin-bottom: 24px;
        }
        .mb-12.text-center.gap-2.success_actions {
        padding-bottom: 69px;
        }
        .app_last_btns a {
            font-size: 17px;
        }
        .cstm_bdge {
            float: left;
            background-color: #DAEAFF;
            text-align: center;
            vertical-align: middle;
            padding: 0 16px;
            margin-right: 10px;
            border-radius: 5px;
            height: 20px;
            width: 42px;
        }
    </style>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="icon_txt_wpr text-center">
                    <svg width="101" height="100" viewBox="0 0 137 136" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M122.756 27.4002C104.555 39.6067 77.7139 68.6004 67.12 91.3603C58.4268 79.0322 50.3822 68.1095 37.7344 57.7858C31.6446 52.8176 24.1136 62.0288 30.2213 67.0059C43.8916 78.1674 53.9991 88.9866 62.6202 102.409C63.2265 103.353 64.0787 104.113 65.0851 104.608C66.0915 105.103 67.2139 105.313 68.3312 105.217C69.4486 105.122 70.5186 104.723 71.4259 104.063C72.3332 103.404 73.0434 102.51 73.4799 101.477C82.5785 79.9466 105.64 49.5971 122.63 35.3412C133.697 53.9662 135.368 78.1629 122.756 99.549C98.1541 141.38 38.0767 141.11 13.8079 99.0986C-10.4609 57.0876 19.8931 4.50974 68.4352 4.50974C83.6751 4.40536 98.4222 9.90347 109.874 19.9592C110.312 20.3372 110.879 20.5321 111.456 20.5036C112.034 20.4751 112.579 20.2253 112.978 19.8061L113.009 19.7746C113.21 19.5553 113.366 19.2982 113.468 19.0182C113.569 18.7383 113.614 18.441 113.6 18.1436C113.586 17.8462 113.512 17.5546 113.385 17.2856C113.257 17.0166 113.077 16.7756 112.856 16.5766C101.05 6.21234 85.4161 -0.2152 67.5208 0.00550772C15.6771 0.66763 -16.0011 56.448 9.92524 101.351C35.9282 146.393 100.942 146.393 126.945 101.351C140.976 77.0504 138.296 48.5026 122.756 27.4002Z" fill="white"/>
                    </svg>
                    <div class="col-12 text-center"><h2 class="sumitted_heading text-center"> {{ __("Your hours are submitted") }}</h2></div>
                    
                </div>


                <div class="mb-3 hours_sumry_bg">
                    <table class="table mb-0 hours_submitted">
                        <tbody>
                            <tr>
                                <td><span class="secondary_hdg">{{ __("Project") }}:</span></td>
                                <td><div class="company_project_id cstm_bdge">{{$hour->project->company_project_id}}</div><span class="primary_hdg project_name">{{$hour->project->name}}</span></td>
                            </tr>
                            <tr>
                                <td><span class="secondary_hdg">{{ __("Date") }}:</span></td>
                                <td><span class="primary_hdg project_date">{{ date("F j, Y", strtotime($hour->work_day))}}</span></td>
                            </tr>
                            <tr>
                                <td><span class="secondary_hdg">{{ __("Time") }}:</span></td>
                                <td><span class="primary_hdg project_time">{{date('H:i', strtotime($hour->start_time))}} {{ __("to") }} {{date('H:i', strtotime($hour->end_time))}}</span></td>
                            </tr>
                            <tr>
                                <td><span class="secondary_hdg">{{ __("Lunch time") }}:</span></td>
                                <td><span class="primary_hdg project_lunch_break">{{$hour->break_time}} {{ __("minutes") }}</span></td>
                            </tr>
                            <tr>
                                <td><span class="secondary_hdg">{{ __("Hours worked") }}:</span></td>
                                <td><span class="primary_hdg worked_hours">{{$work_duration}}h</span></td>
                            </tr>
                            <tr>
                                <td><span class="secondary_hdg">{{ __("Comments") }} :</span></td>
                                <td><span class="primary_hdg project_hours_comment">{{$hour->comments}}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mb-12 text-center gap-2 success_actions">
                    <button type="button" class="btn app_btn_blue app_last_btns">
                        <a href="{{ route('worker.hours') }}" style="color:#fff;width: 100%;display: inline-block;" class="trgr_ovrly"><i class="bx bx-time-five"></i> {{ __("Add More Hours") }}</a>
                    </button>
                    <br><br>

                    <button type="button" class="btn  app_last_btns" style="background-color: #fff;">
                        <a href="{{ $worker->workerLink() }}" style="color: #2F45C5; width: 100%;display: inline-block;" class="trgr_ovrly"><i class="bx bx-home-alt" style="color: #2F45C5;"></i> {{ __("Back to Home") }}</a>
                    </button>
                </div>
            </div>
        </div>
    </div>

    
@endsection