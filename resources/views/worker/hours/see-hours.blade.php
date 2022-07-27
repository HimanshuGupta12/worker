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

<style type="text/css">
.select-date .datepicker.datepicker-inline {
    width: 100% !important;
}
.select-date table.table-condensed {
    width: 100%;
}
.select-date td.active.day {
    width: 43px !important;
    height: 43px !important;
}
.wizard>.content>.body{
padding: 3px 0 0 !important;
}
.dropzone {
    background: none !important;
    border: none !important;
}

    .table td p.invoiced {
      color: #2697FF;
    }
    .table td p.notinvoiced {
      color: #FFA113;
    }

    td.action .mt-1 .badge {
      font-size: 15px;
      border-radius: 50%;
    }

    ul.list-group.custom_icon_list li.list-group-item {
      padding: 0.4rem 0rem;
    }
    div#datatable_filter {
    display: none;
    }

    div#datatable_length {
    display: none;
    }

   .select2{
    width: 100% !important;
   }

</style>

@endsection
@section('content')
   <!-- ---------edit ---------- -->

   <div class="modal fade create-hour-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myExtraLargeModalLabel2">{{ __('Edit Hours') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body" id='hour_edit'>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade edit-comment-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <form id="hour_comments_form" action="{{url('hours.comments')}}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <span class="modal-title" id="myExtraLargeModalLabel2">{{ __('Comments') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea id="hour_comments" name="comments" rows="3" style="width: 100%"></textarea>
                            </div>
                        </div>
                        <input type="hidden" id="hour_id" name="id" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="button" id="saveComments" class="btn btn-primary waves-effect waves-light" data-bs-dismiss="modal">{{ __('Update comments') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<div class="container-fluid">
<div class="row page_headingapp">
        <div class="col-1">
        <a href="{{ url('/worker?worker='.worker()->login) }}" class="trgr_ovrly">
            <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        </div>
        <div class="col-10">
            <h4 class="mb-0 submisn_heading step_heading">{{ __('See Hours') }}</h4>
        </div>
        <div class="col-1"></div>
    </div>
  <!-- Filters -->
  <div class="row">
     <div class="col-lg-12">
        <div class="card">
           <div class="card-body">
            <form class="report_forms">
              <div class="row">
                    <div class="col-md-1">
                      <div class=" sort_icon">
                        <h5 class="report_card_title  mb-0"><i class="bx bx-slider-alt"></i>{{ __('Sort') }}</h5>
                      </div>
                    </div>
                    <div class="col-md-11">
                        <div class="row mobile_spacing_row" >
                        <div class="col">
                            <div class="mb-5" style="text-align: center;">
                                <label class="app_field_label " >{{ __('Start date') }}</label>
                                <div class="input-group">
                                    <input class="form-control" placeholder="{{ __('Start date') }}" type="date" data-date-format="dd-mm-yyyy" value="{{$start_date}}" name='start_date' />
                                </div>
                            </div>
                            <!--<div id="datepicker_1">
                                <input type="text" class="form-control" placeholder="{{ __('Start date') }}" name='start_date' data-date-format="dd-mm-yyyy"
                                    data-date-container='#datepicker_1' data-provide="datepicker" data-date-autoclose="true" id="datepicker1" value="{{$start_date}}">                                
                            </div>-->
                        </div>
                        <div class="col">
                            <div class="mb-5" style="text-align: center;">
                                <label class="app_field_label " >{{ __('End date') }}</label>
                                <div class="input-group">
                                    <input class="form-control" placeholder="{{ __('End date') }}" type="date" data-date-format="dd-mm-yyyy" value="{{$end_date}}" name='end_date' />
                                </div>
                            </div>
<!--                        <div id="datepicker_2">
                                <input type="text" class="form-control" placeholder="{{ __('End date') }}" name='end_date'
                                data-date-format="dd-mm-yyyy" data-date-container='#datepicker_2' data-provide="datepicker"
                                 data-date-autoclose="true" id="datepicker2" value="{{$end_date}}">
                            </div>-->
                        <!--<div id="datepicker_2" style="margin-top: 10px;">
                                <input type="text" class="form-control" placeholder="{{ __('End date') }}" name='end_date'
                                data-date-format="dd M, yyyy" data-date-container='#datepicker_2' data-provide="datepicker"
                                 data-date-autoclose="true" id="datepicker2" value="{{($end_date != null) ? date('d-m-Y', strtotime($end_date)) : date('d-m-Y')}}">
                                 <span ><i class="mdi mdi-calendar"></i></span> 
                            </div>-->
                        </div>

                        <!-- <div class="col-lg-2 col-md-4" id="dates">
                          <div> -->
                            <!-- <label for="end_date">End date</label> -->
                            <!-- <input class="form-control" type="text" placeholder="End date" onfocus="(this.type='date')" name='end_date' value="">
                          </div>
                        </div> -->


                         <div class="col-lg-4 col-md-4">
                            <button type="submit" class="btn btn-info w-md">{{ __('Show') }}</button>
                            <button type="button" class="btn custom_rest_btn">
                              <a href="{{ url()->current() }}" class="">{{ __('Reset') }}</a>
                            </button>
                          </div>
                        </div>
                    </div>
                </div>
              </form>
           </div>
        </div>

        <div class="card card-body card_summry">
                <div class="row ">

                    <div class="col-md-4 no-filter-stat" style='display:{{ (empty($start_date) && empty($end_date)) ? 'block' : 'none' }}'>
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15"></span>
                            </div>
                            <h5 class="rep_heading mb-0 ">{{__('This week')}} : <span class="cstn_bold">{{$thisweek}}</span></h5>
                        </div>
                    </div>
                    <div class="col-md-4 no-filter-stat" style='display:{{ (empty($start_date) && empty($end_date)) ? 'block' : 'none' }}'>
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15"></span>
                            </div>
                            <h5 class="rep_heading mb-0 ">{{__('Last week')}}  : <span class="cstn_bold">{{$lastweek}}</span></h5>
                        </div>
                    </div>
                    <div class="col-md-4 filter-stat" style='display:{{ !(empty($start_date) && empty($end_date)) ? 'block' : 'none' }}'>
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15"></span>
                            </div>
                            <h5 class="rep_heading mb-0 ">{{$startToEnd}} : <span class="cstn_bold">{{$startToEndHr}}</span></h5>
                        </div>
                    </div>
                    <div class="col-md-4 filter-stat" style='display:{{ !(empty($start_date) && empty($end_date)) ? 'block' : 'none' }}'>
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15"></span>
                            </div>
                            <h5 class="rep_heading mb-0 ">{{__('Total days')}} : <span class="cstn_bold">{{$days}}</span></h5>
                        </div>
                    </div>
                </div>
          </div>

     </div>
  </div>
<!-- Filters End-->

    <!--  listing start -->
    <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">

                  <div class="table-responsive">
                  <table id="datatable_see-hour" class="table table-nowrap mb-50 dt-responsive align-middle  w-100">
                      <thead class=" hr_table">
                      <tr>
                          <th>{{ __('Date') }}</th>
                          <!-- <th>Worker</th> -->
                          <!-- <th>Project</th> -->
                          <th>{{ __('Work Hours') }}</th>
                          <th>{{ __('Break') }}</th>
                          <th>{{ __('Project') }} / {{ __('Comments') }}</th>
                          <!-- <th>Invoice</th> -->
                          <th style="text-align:right;">{{ __('Action') }}</th>
                      </tr>
                      </thead>
                      <tbody>
                      @foreach($hours as $key => $hour)
                      <tr>
                          <?php
                            $ddate = $hour->work_day;
                            $date = new DateTime($ddate);
                            $week = $date->format("W");
                            ?>
                          <td data-sort='<?= strtotime($hour->work_day) ?>'><p class="rep_heading">{{date("d-m-Y", strtotime($hour->work_day))}} </p><span class="line_down">{{date("l", strtotime($hour->work_day))}} - ({{ $week }})</span></td>
                          <!-- <td><p class="rep_heading">{{$hour['worker']->first_name}} {{$hour['worker']->last_name}} </p><span class="line_down">{{$hour['worker']->worker_position}}</span></td> -->
                          <!---  <td><p class="rep_heading">{{$hour['project']->name}}</p></td>-->
                          <td><p class="rep_heading">{{ substr($hour->start_time, 0, -3)}} {{ __('to') }} {{ substr($hour->end_time, 0, -3)}}</p><span class="line_down">{{ __('Total') }}: {{$hour->working_hours}}h</span></td>
                          <td>
                              @if($hour->break_time == '0')
                              <p class="rep_heading">{{ __('No') }}</p>
                              @else
                              <p class="rep_heading">{{ __('Yes') }}</p><span class="line_down">{{ $hour->break_time}} {{ __('Min') }}</span>
                              @endif
                          </td>
                          <td>
                            <p class="rep_heading">{{$hour['project']->nameAndNumber()}}</p>
                              <span class="line_down">
                                  @if(strlen($hour->comments) > 30)
                                  {{substr($hour->comments,0,30)}} <a data-bs-target=".edit-comment-modal" data-id="{{$hour->id}}" data-comments="{{$hour->comments}}" class="edit-hour-comment" data-bs-toggle="modal"> {{ __('...more') }}</a>
                                  @else
                                  {{$hour->comments}}
                                  @endif
                              </span>
                          </td>
                          <td class="action align-middle" style="text-align:right;">
                            @if($hour->stamp_invoice == 0 && $edithour)
                            <div class="entries_action">
                              <div class="avatar-xs me-3 report_smry_iconsize">
                                <a href="" id='create-hour-{{$hour->id}}' data-bs-target=".create-hour-modal" class="edit_hours" data-hour_id ="{{$hour->id}}" data-bs-toggle="modal" class="edit_hour">
                                  <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                    <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M9.1 1H7.3C2.8 1 1 2.8 1 7.3V12.7C1 17.2 2.8 19 7.3 19H12.7C17.2 19 19 17.2 19 12.7V10.9" stroke="#2F45C5" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                      <path d="M14.2195 1.9785L8.15489 8.04398C7.92401 8.2749 7.69312 8.72904 7.64694 9.06003L7.31601 11.3769C7.19287 12.2159 7.78547 12.8009 8.62436 12.6855L10.9409 12.3545C11.2642 12.3083 11.7182 12.0774 11.9568 11.8465L18.0214 5.78097C19.0681 4.73414 19.5607 3.51797 18.0214 1.9785C16.4822 0.439043 15.2662 0.93167 14.2195 1.9785Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                      <path d="M13.6001 2.80005C14.0884 4.54175 15.4511 5.9045 17.2001 6.40005" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                  </span>
                                </a>
                              </div>
                            </div>
                            @endif
                          </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                  {{ $hours->withQueryString()->links() }}
              </div>
          </div>
      </div>
    </div>
</div>

@endsection

@section('scripts')
    @parent
<script>

function padTo2Digits(num) {
    return num.toString().padStart(2, '0');
}

function formatDate(date) {
    return [
        padTo2Digits(date.getDate()),
        padTo2Digits(date.getMonth() + 1),
        date.getFullYear(),
    ].join('-');
}

$(document).ready(function() {

    $('#datatable_see-hour').dataTable({
        paging: false,
        searching: false,
        order: [
            [ 0, "desc"]
        ]
    });

    const today = formatDate(new Date())
    $('#datepicker1').datepicker({
        endDate: today
    });
    $('#datepicker2').datepicker({
        endDate: today
    });

    $(document).on('click', '.edit_hours', function() {
        let hour_id = $(this).data('hour_id');
        $.ajax({
            url: '/hours/'+hour_id+'/edit',
            type: 'GET',
            success: function(data) {
              $("#hour_edit").html(data);
              $(".create-hour-modal").modal('show');
            },
            error: function(err) {
                console.log(err);
                alert('error');
            }
        });
    });

    $(document).on('click', '.edit-hour-comment', function() {
        $("#hour_comments").val($(this).data("comments"));
        $("#hour_id").val($(this).data("id"));
    });

    $(document).on('click', '#saveComments', function() {
        $.ajax({
            url: '/hours/comments',
            data: $("#hour_comments_form").serialize(),
            type: "POST",
            success: function (data) {
                $(".page-content").prepend('<div class="alert alert-success" style="position: absolute; top: 75px; left: 26px; right: 26px; background-color: #34c38f; color: #fff; text-align: center; border: 0; z-index: 1; border-right: 10px; min-height: 30px; display: flex; justify-content: center; align-content: center; flex-direction: column;"> '+data.success+' </div>');
                setTimeout(function(){
                  window.location.reload();
                }, 1000);
            }
        });
    })
});
</script>
@endsection
