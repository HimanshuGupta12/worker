@extends('layouts.user')
@section('head')
<meta name="_token" content="{{ csrf_token() }}">
    @parent

    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">

    <link href="{{ env('PUBLIC_PATH') }}/css/worker-reports.css" rel="stylesheet" type="text/css" />
    <link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        table.table {
            border: 1px solid lightgray;
        }
    </style>
        
@endsection

@section('content')

<div class="container-fluid">
    
    <!-- Modal -->
    <div class="modal fade view-btn-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h3><span class="modal-title">Universal Modal</span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal_content"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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
                        <h5 class="report_card_title  mb-0"><i class="bx bx-slider-alt"></i>Sort</h5>
                      </div>
                    </div>
                    <div class="col-md-11">
                      <div class="row mobile_spacing_row" >
                        
                        <div class="col-lg-4 col-md-4">
                          <select class="form-select" id="autoSizingSelect" name="date" onchange="status('dates', this)">
                            <option selected="">Select date</option>
                            <option value="Last week" @if (isset($date) && $date == "Last week" ) selected @endif >Last week</option>
                            <option value="This week" @if (isset($date) && $date == "This week" ) selected @endif >This week</option>
                            <option value="Last and this week" @if (isset($date) && $date == "Last and this week" ) selected @endif >Last and this week</option>
                            <option value="Previous two weeks" @if (isset($date) && $date == "Previous two weeks" ) selected @endif >Previous two weeks</option>
                            <option value="Last month" @if (isset($date) && $date == "Last month" ) selected @endif >Last month</option>
                            <option value="This month" @if (isset($date) && $date == "This month" ) selected @endif >This month</option>
                            <option value="Last three months" @if (isset($date) && $date == "Last three months" ) selected @endif >Last three months</option>
                            <option value="Last six months" @if (isset($date) && $date == "Last six months" ) selected @endif >Last six months</option>
                            <option value="Last twelve months" @if (isset($date) && $date == "Last twelve months" ) selected @endif >Last twelve months</option>
                            <option value="This year" @if (isset($date) && $date == "This year" ) selected @endif >This year</option>
                            <option value="Last year" @if (isset($date) && $date == "Last year" ) selected @endif >Last year</option>
                            <option value="Custom" @if (isset($date) && $date == "Custom" ) selected @endif >Custom</option>
                          </select>
                            <svg class="field_icon"  width="16" height="18" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M6 1V4" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M14 1V4" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M19 7.5V16C19 19 17.5 21 14 21H6C2.5 21 1 19 1 16V7.5C1 4.5 2.5 2.5 6 2.5H14C17.5 2.5 19 4.5 19 7.5Z" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6 10H14" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6 15H10" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="col-lg-4 col-md-4" id="dates" style='display:{{ (isset($date) && $date == "Custom" ) ? 'block' : 'none' }}'>
                          <div>
                              <input type="date" name='start_date' value="{{$start_date}}" class="form-control">
                          </div>
                          <div>
                            <input type="date" name='end_date' value="{{$end_date}}" class="form-control">
                          </div>
                        </div>

                         <div class="col-lg-4 col-md-4">
                            <button type="submit" class="btn btn-info w-md">Show</button>
                            <button type="button" class="btn custom_rest_btn">
                              <a href="{{ url()->current() }}" class="">Reset</a>
                            </button>
                          </div>
                        </div>
                    </div>
                </div>
              </form>
           </div>
        </div>
     </div>
  </div>
<!-- Filters End--> 
              
<!-- Report summry hours -->
    <div class="row" id="hours_details">
        <div class="col-md-12 col-lg-12">
          <div class="card card-body card_summry">
              <h4 class="report_card_title mb-3">Hours details</h4>
                <div class="row">
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                              <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                  <i class="bx bx-alarm"></i>
                              </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Total: <span class="rep_heading">{{$totalHours}}</span></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                <svg width="16" height="18" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M4.64307 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.78564 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M1.75 6.12939H12.6786" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M13.0001 5.75017V11.2145C13.0001 13.143 12.0359 14.4287 9.78585 14.4287H4.643C2.393 14.4287 1.42871 13.143 1.42871 11.2145V5.75017C1.42871 3.8216 2.393 2.53589 4.643 2.53589H9.78585C12.0359 2.53589 13.0001 3.8216 13.0001 5.75017Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 9.09291H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 11.0214H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 9.09291H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 11.0214H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 9.09291H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 11.0216H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Invoiced hours: <span class="rep_heading">{{$invoicedHours}}</span></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                <svg width="16" height="18" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M4.64307 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.78564 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M1.75 6.12939H12.6786" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M13.0001 5.75017V11.2145C13.0001 13.143 12.0359 14.4287 9.78585 14.4287H4.643C2.393 14.4287 1.42871 13.143 1.42871 11.2145V5.75017C1.42871 3.8216 2.393 2.53589 4.643 2.53589H9.78585C12.0359 2.53589 13.0001 3.8216 13.0001 5.75017Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 9.09291H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 11.0214H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 9.09291H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 11.0214H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 9.09291H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 11.0216H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Non invoiced hours: <span class="rep_heading">{{$nonInvoicedHours}}</span></h5>
                         </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="17" height="18" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M4 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.7998 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.2998 5.25391H11.4998" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.8 4.8999V9.9999C11.8 11.7999 10.9 12.9999 8.8 12.9999H4C1.9 12.9999 1 11.7999 1 9.9999V4.8999C1 3.0999 1.9 1.8999 4 1.8999H8.8C10.9 1.8999 11.8 3.0999 11.8 4.8999Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 8.01997H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 9.82002H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 8.01997H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 9.82002H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 8.01997H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 9.82002H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Hourly hours: <span class="rep_heading">{{$hourlyProjectHours}}</span></h5>
                         </div>
                        </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="17" height="18" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M4 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.7998 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.2998 5.25391H11.4998" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.8 4.8999V9.9999C11.8 11.7999 10.9 12.9999 8.8 12.9999H4C1.9 12.9999 1 11.7999 1 9.9999V4.8999C1 3.0999 1.9 1.8999 4 1.8999H8.8C10.9 1.8999 11.8 3.0999 11.8 4.8999Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 8.01997H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 9.82002H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 8.01997H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 9.82002H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 8.01997H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 9.82002H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Fixed hours: <span class="rep_heading">{{$fixedProjectHours}}</span></h5>
                         </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="17" height="18" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M4 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.7998 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.2998 5.25391H11.4998" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.8 4.8999V9.9999C11.8 11.7999 10.9 12.9999 8.8 12.9999H4C1.9 12.9999 1 11.7999 1 9.9999V4.8999C1 3.0999 1.9 1.8999 4 1.8999H8.8C10.9 1.8999 11.8 3.0999 11.8 4.8999Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 8.01997H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 9.82002H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 8.01997H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 9.82002H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 8.01997H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 9.82002H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Mixed hours: <span class="rep_heading">{{$mixedProjectHours}}</span></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="17" height="18" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M4 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.7998 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.2998 5.25391H11.4998" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.8 4.8999V9.9999C11.8 11.7999 10.9 12.9999 8.8 12.9999H4C1.9 12.9999 1 11.7999 1 9.9999V4.8999C1 3.0999 1.9 1.8999 4 1.8999H8.8C10.9 1.8999 11.8 3.0999 11.8 4.8999Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 8.01997H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 9.82002H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 8.01997H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 9.82002H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 8.01997H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 9.82002H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Workers submitted hours: <span class="rep_heading">N/A</span></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="17" height="18" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M4 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.7998 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.2998 5.25391H11.4998" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.8 4.8999V9.9999C11.8 11.7999 10.9 12.9999 8.8 12.9999H4C1.9 12.9999 1 11.7999 1 9.9999V4.8999C1 3.0999 1.9 1.8999 4 1.8999H8.8C10.9 1.8999 11.8 3.0999 11.8 4.8999Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 8.01997H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 9.82002H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 8.01997H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 9.82002H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 8.01997H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 9.82002H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Number of active workers who have submitted hours: 
                                <span class="rep_heading">{{$totalWorkersWhoSubmittedHours}} <a class="active_workers_link" data-company_id="{{user()->company_id}}">( See list )</a></span></h5>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="17" height="18" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M4 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.7998 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.2998 5.25391H11.4998" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.8 4.8999V9.9999C11.8 11.7999 10.9 12.9999 8.8 12.9999H4C1.9 12.9999 1 11.7999 1 9.9999V4.8999C1 3.0999 1.9 1.8999 4 1.8999H8.8C10.9 1.8999 11.8 3.0999 11.8 4.8999Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 8.01997H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 9.82002H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 8.01997H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 9.82002H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 8.01997H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 9.82002H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Top projects with most used vs given hours</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table id="datatable-used-vs-given-hours" class="table align-middle table-nowrap mb-0">
                                <thead class="table-light-color">
                                <tr>
                                    <th class="align-middle bold">Project Id</th>
                                    <th class="align-middle bold">Project name</th>
                                    <th class="align-middle bold">Used hours</th>
                                    <th class="align-middle bold">Given hours</th>
                                    <th class="align-middle bold">Used ratio</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($topProjectsWithUsedVsGivenHours as $key => $proj)
                                <tr>
                                    <td>{{$proj['company_project_id']}}</td>
                                    <td>{{$proj['name']}}</td>
                                    <td>{{$proj['used_hours']}}</td>
                                    <td>{{$proj['given_hours']}}</td>
                                    <td>{{$proj['used_percent']}}</td>
                                </tr>
                                <?php if ($key == 2){ break;} ?>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs me-3 report_smry_iconsize"></div>
                                <h5 class="rep_heading mb-0"><a class="projects_used_hours_link" data-company_id="{{user()->company_id}}"> (See list )</a></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Report summry hours end  -->

    <!-- Report summry Projects -->
    <div class="row" id="project_details">
        <div class="col-md-12 col-lg-12">
          <div class="card card-body card_summry">
              <h4 class="report_card_title mb-2">Project details</h4>
                <div class="row">

                    <div class="col-md-6">

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                <i class="bx bx-briefcase-alt-2"></i>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Total active Projects: <span class="rep_heading">{{$activeProjects}} <a class="active_projects_link" data-company_id="{{user()->company_id}}"> (See list )</a></span></h5>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                 <i class="bx bx-briefcase-alt-2"></i>
                            </span>
                            </div>
                             <h5 class="rep_heading mb-0 cstn_bold">Total completed project: <span class="rep_heading">{{$completedProjects}} <a class="complete_projects_link" data-company_id="{{user()->company_id}}"> (See list )</a></span></h5>
                         </div>                 
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <i class="bx bx-briefcase-alt-2"></i>
                            </span>
                            </div>
                            <h3 class="rep_heading mb-0 cstn_bold">Busy projects</h3>
                        </div>                        
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <table id="datatable-busy-projects" class="table align-middle table-nowrap mb-0">
                                <thead class="table-light-color">
                                <tr>
                                    <th class="align-middle">Id</th>
                                    <th class="align-middle">Name</th>
                                    <th class="align-middle">Active workers</th>
                                    <th class="align-middle">Total hours</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($busyProjects as $key => $proj)
                                <tr>
                                    <td>{{$proj['id']}}</td>
                                    <td>{{$proj['name']}}</td>
                                    <td>{{$proj['totalWorkers']}}</td>
                                    <td>{{$proj['totalHours']}}</td>
                                </tr>
                                <?php if ($key == 2){ break;} ?>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs me-3 report_smry_iconsize"></div>
                                <h5 class="rep_heading mb-0"><a class="busy_projects_link" data-company_id="{{user()->company_id}}"> (See list )</a></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Report summry tools -->
    <div class="row" id="tool_details">
        <div class="col-md-12 col-lg-12">
          <div class="card card-body card_summry">
              <h4 class="report_card_title mb-2">Tools details</h4>
                <div class="row">
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                <i class="bx bx-wrench"></i>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Total tools: <span class="rep_heading">{{$totalTools}} <a class="total_tools_link" data-company_id="{{user()->company_id}}"> (See list )</a></span></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                 <svg width="16" height="18" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M4.64307 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.78564 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M1.75 6.12939H12.6786" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M13.0001 5.75017V11.2145C13.0001 13.143 12.0359 14.4287 9.78585 14.4287H4.643C2.393 14.4287 1.42871 13.143 1.42871 11.2145V5.75017C1.42871 3.8216 2.393 2.53589 4.643 2.53589H9.78585C12.0359 2.53589 13.0001 3.8216 13.0001 5.75017Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 9.09291H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 11.0214H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 9.09291H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 11.0214H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 9.09291H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 11.0216H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Tools with workers: <span class="rep_heading">{{$toolsWithWorkers}} <a class="tools_with_workers_link" data-company_id="{{user()->company_id}}"> (See list )</a></span></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                 <svg width="16" height="18" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M4.64307 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.78564 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M1.75 6.12939H12.6786" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M13.0001 5.75017V11.2145C13.0001 13.143 12.0359 14.4287 9.78585 14.4287H4.643C2.393 14.4287 1.42871 13.143 1.42871 11.2145V5.75017C1.42871 3.8216 2.393 2.53589 4.643 2.53589H9.78585C12.0359 2.53589 13.0001 3.8216 13.0001 5.75017Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 9.09291H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 11.0214H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 9.09291H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 11.0214H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 9.09291H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 11.0216H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Tools in storage: <span class="rep_heading">{{$toolsInStorages}} <a class="tools_in_storage_link" data-company_id="{{user()->company_id}}"> (See list )</a></span></h5>
                         </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                 <svg width="16" height="18" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M4.64307 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.78564 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M1.75 6.12939H12.6786" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M13.0001 5.75017V11.2145C13.0001 13.143 12.0359 14.4287 9.78585 14.4287H4.643C2.393 14.4287 1.42871 13.143 1.42871 11.2145V5.75017C1.42871 3.8216 2.393 2.53589 4.643 2.53589H9.78585C12.0359 2.53589 13.0001 3.8216 13.0001 5.75017Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 9.09291H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 11.0214H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 9.09291H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 11.0214H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 9.09291H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 11.0216H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Missing tools: <span class="rep_heading">{{$unbalancedTools}} <a class="unbalanced_tools_link" data-company_id="{{user()->company_id}}"> (See list )</a></span></h5>
                         </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                 <svg width="16" height="18" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M4.64307 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.78564 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M1.75 6.12939H12.6786" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M13.0001 5.75017V11.2145C13.0001 13.143 12.0359 14.4287 9.78585 14.4287H4.643C2.393 14.4287 1.42871 13.143 1.42871 11.2145V5.75017C1.42871 3.8216 2.393 2.53589 4.643 2.53589H9.78585C12.0359 2.53589 13.0001 3.8216 13.0001 5.75017Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 9.09291H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 11.0214H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 9.09291H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 11.0214H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 9.09291H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 11.0216H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">New tools: <span class="rep_heading">{{$newTools['total_tools']}}</span></h5>&nbsp;&nbsp;&nbsp;&nbsp;<h5 class="rep_heading mb-0 cstn_bold">New tools price: <span class="rep_heading">{{$newTools['total_tools_price']}}</span></h5>
                         </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                 <svg width="16" height="18" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M4.64307 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.78564 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M1.75 6.12939H12.6786" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M13.0001 5.75017V11.2145C13.0001 13.143 12.0359 14.4287 9.78585 14.4287H4.643C2.393 14.4287 1.42871 13.143 1.42871 11.2145V5.75017C1.42871 3.8216 2.393 2.53589 4.643 2.53589H9.78585C12.0359 2.53589 13.0001 3.8216 13.0001 5.75017Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 9.09291H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 11.0214H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 9.09291H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 11.0214H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 9.09291H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 11.0216H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Days until next balancing: <span class="rep_heading">{{$daysUntillNextBalancing['days'] .' ('.$daysUntillNextBalancing['date'] .' )'}} </span></h5>
                         </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="17" height="18" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M4 0.999023V2.79902" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.7998 0.999023V2.79902" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.2998 5.25293H11.4998" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.8 4.89893V9.99893C11.8 11.7989 10.9 12.9989 8.8 12.9989H4C1.9 12.9989 1 11.7989 1 9.99893V4.89893C1 3.09893 1.9 1.89893 4 1.89893H8.8C10.9 1.89893 11.8 3.09893 11.8 4.89893Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 8.01899H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 9.81904H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 8.01899H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 9.81904H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 8.01899H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 9.81904H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Top tools in service</h5>
                        </div>
                        <table id="datatable-tools-in-service" class="table align-middle table-nowrap mb-0">
                            <thead class="table-light-color">
                                <tr>
                                    <th class="align-middle">Tool Id</th>
                                    <th class="align-middle">Tool name</th>
                                    <th class="align-middle">Date in</th>
                                    <th class="align-middle">Days</th>
                                    <th class="align-middle">Possessor</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topToolsInService as $key => $tool)
                                <?php $url = "tools?q=".$tool['company_tool_id']."&status_id=".$tool['status_id']; ?>
                                <tr>
                                    <td>{{$tool['company_tool_id']}}</td>
                                    <td>{{$tool['name']}}</td>
                                    <td>{{$tool['date']}}</td>
                                    <td>{{$tool['days']}}</td>
                                    <td>{{$tool['possessor']}}</td>
                                    <td><a href="{{$url}}" target="_blank">View</a></td>
                                </tr>
                                <?php if ($key == 2){ break;} ?>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-3 report_smry_iconsize"></div>
                            <h5 class="rep_heading mb-0"><a class="tools_in_service_link" data-company_id="{{user()->company_id}}"> (See list )</a></h5>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="17" height="18" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M4 0.999023V2.79902" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.7998 0.999023V2.79902" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.2998 5.25293H11.4998" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.8 4.89893V9.99893C11.8 11.7989 10.9 12.9989 8.8 12.9989H4C1.9 12.9989 1 11.7989 1 9.99893V4.89893C1 3.09893 1.9 1.89893 4 1.89893H8.8C10.9 1.89893 11.8 3.09893 11.8 4.89893Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 8.01899H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 9.81904H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 8.01899H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 9.81904H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 8.01899H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 9.81904H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Top workers having most tools</h5>
                            </div>
                            <table id="datatable-tools-with-workers" class="table align-middle table-nowrap mb-0">
                                <thead class="table-light-color">
                                    <tr>
                                        <th class="align-middle">Worker name</th>
                                        <th class="align-middle">Price</th>
                                        <th class="align-middle">Balancing status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topToolsWithWorkers as $key => $tool)
                                    <tr>
                                        <td>{{$tool['worker_name']}}</td>
                                        <td>{{$tool['tools_price']}}</td>
                                        <td>{{$tool['total_tools'].' / '.$tool['unbalanced_tools']}}</td>
                                    </tr>
                                    <?php if ($key == 2){ break;} ?>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs me-3 report_smry_iconsize"></div>
                                <h5 class="rep_heading mb-0"><a class="top_worker_with_most_tools_link" data-company_id="{{user()->company_id}}"> (See list )</a></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Report summry workers -->
    <div class="row" id="workers_details">
        <div class="col-md-12 col-lg-12">
          <div class="card card-body card_summry">
              <h4 class="report_card_title mb-2">Workers details</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                <i class="bx bx-happy"></i>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Top performers:</h5>
                        </div>
                        <table id="datatable-hours-submission-best" class="table align-middle table-nowrap mb-0">
                            <thead class="table-light-color">
                                <tr>
                                    <th class="align-middle">Worker name</th>
                                    <th class="align-middle">Words in comments</th>
                                    <th class="align-middle">Images submitted</th>
                                    <th class="align-middle">Delayed time(hr)</th>
                                    <th class="align-middle">Average position</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($workersPerformanceList as $key => $worker)
                                <tr>
                                    <td>{{$worker['worker_name']}}</td>
                                    <td>{{$worker['total_comments']}}</td>
                                    <td>{{$worker['total_images']}}</td>
                                    <td>{{$worker['late_submission_hours']}}</td>
                                    <td>{{$worker['average_sum']}}</td>
                                </tr>
                                <?php if ($key == 2){ break;} ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <?php
                    $workersWorstPerformanceList = $workersPerformanceList;
                    array_multisort( array_column($workersWorstPerformanceList, "average_sum"), SORT_ASC, $workersWorstPerformanceList );
                    ?>
                    <div class="col-md-6">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                            <i class="bx bx-sad"></i>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Worst performers:</h5>
                        </div>
                        <table id="datatable-hours-submission-worst" class="table align-middle table-nowrap mb-0">
                            <thead class="table-light-color">
                                <tr>
                                    <th class="align-middle">Worker name</th>
                                    <th class="align-middle">Words in comments</th>
                                    <th class="align-middle">Images submitted</th>
                                    <th class="align-middle">Delayed time(hr)</th>
                                    <th class="align-middle">Average position</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($workersWorstPerformanceList as $key => $worker)
                                <tr>
                                    <td>{{$worker['worker_name']}}</td>
                                    <td>{{$worker['total_comments']}}</td>
                                    <td>{{$worker['total_images']}}</td>
                                    <td>{{$worker['late_submission_hours']}}</td>
                                    <td>{{$worker['average_sum']}}</td>
                                </tr>
                                <?php if ($key == 2){ break;} ?>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-3 report_smry_iconsize"></div>
                            <h5 class="rep_heading mb-0"><a class="workers_performance_link" data-company_id="{{user()->company_id}}"> (See list )</a></h5>
                        </div>
                    </div>
<!--                    <div class="col-md-12">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                            <svg width="18" height="18" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M1.25 14.7432H15.0202" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M10.2075 14.7362L1.94537 14.75L1.93848 4.46363C1.93848 4.00233 2.17258 3.57543 2.55126 3.32068L5.3053 1.48237C5.7666 1.17254 6.37247 1.17254 6.83378 1.48237L9.58782 3.32068C9.97338 3.57543 10.2006 4.00233 10.2006 4.46363L10.2075 14.7362Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M13.6294 14.7499V11.989" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M13.6436 7.85791C12.8863 7.85791 12.2666 8.47757 12.2666 9.23493V10.612C12.2666 11.3693 12.8863 11.989 13.6436 11.989C14.401 11.989 15.0206 11.3693 15.0206 10.612V9.23493C15.0206 8.47757 14.401 7.85791 13.6436 7.85791Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.93848 9.23511H10.2006" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.06934 14.743V12.1611" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.06939 6.8253C6.63977 6.8253 7.10215 6.36291 7.10215 5.79253C7.10215 5.22215 6.63977 4.75977 6.06939 4.75977C5.49901 4.75977 5.03662 5.22215 5.03662 5.79253C5.03662 6.36291 5.49901 6.8253 6.06939 6.8253Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Workers birthday:</h5>
                        </div>
                    </div>
                    @foreach($workersPerformanceList as $key => $worker)
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            
                            </div>
                            <h5 class="rep_heading mb-0">Name : N/A-</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            
                            </div>
                            <h5 class="rep_heading mb-0">Date of birth : N/A-</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            
                            </div>
                            <h5 class="rep_heading mb-0">Days left : N/A-</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            
                            </div>
                            <h5 class="rep_heading mb-0">Age to become : N/A-</h5>
                        </div>
                    </div>
                    <?php if ($key == 2){ break;} ?>
                    @endforeach
                    <div class="col-md-12">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                            <svg width="18" height="18" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M1.25 14.7432H15.0202" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M10.2075 14.7362L1.94537 14.75L1.93848 4.46363C1.93848 4.00233 2.17258 3.57543 2.55126 3.32068L5.3053 1.48237C5.7666 1.17254 6.37247 1.17254 6.83378 1.48237L9.58782 3.32068C9.97338 3.57543 10.2006 4.00233 10.2006 4.46363L10.2075 14.7362Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M13.6294 14.7499V11.989" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M13.6436 7.85791C12.8863 7.85791 12.2666 8.47757 12.2666 9.23493V10.612C12.2666 11.3693 12.8863 11.989 13.6436 11.989C14.401 11.989 15.0206 11.3693 15.0206 10.612V9.23493C15.0206 8.47757 14.401 7.85791 13.6436 7.85791Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.93848 9.23511H10.2006" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.06934 14.743V12.1611" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.06939 6.8253C6.63977 6.8253 7.10215 6.36291 7.10215 5.79253C7.10215 5.22215 6.63977 4.75977 6.06939 4.75977C5.49901 4.75977 5.03662 5.22215 5.03662 5.79253C5.03662 6.36291 5.49901 6.8253 6.06939 6.8253Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Missing to provide information:</h5>
                        </div>
                    </div>
                    @foreach($workersPerformanceList as $key => $worker)
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            
                            </div>
                            <h5 class="rep_heading mb-0">Name : N/A-</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            
                            </div>
                            <h5 class="rep_heading mb-0">Date of birth : N/A-</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            
                            </div>
                            <h5 class="rep_heading mb-0">Days left : N/A-</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            
                            </div>
                            <h5 class="rep_heading mb-0">Age to become : N/A-</h5>
                        </div>
                    </div>
                    <?php if ($key == 2){ break;} ?>
                    @endforeach-->
                    <div class="row">
                        <div class="col-md-6">
                             <div class="d-flex align-items-center mb-2">
                                <div class="avatar-xs me-3 report_smry_iconsize">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                <i class="bx bx-hotel"></i>
                                </span>
                                </div>
                                <h5 class="rep_heading mb-0 cstn_bold">Upcoming holidays:</h5>
                            </div>
                            <table id="datatable-holidays" class="table align-middle table-nowrap mb-0">
                                <thead class="table-light-color">
                                    <tr>
                                        <th class="align-middle">Worker name</th>
                                        <th class="align-middle">Days until holiday</th>
                                        <th class="align-middle">Date from</th>
                                        <th class="align-middle">Date to</th>
                                        <th class="align-middle">Confirmation status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comingHolidays as $key => $holiday)
                                    <tr>
                                        <td>{{$holiday['worker']['first_name'] .' '. $holiday['worker']['last_name']}}</td>
                                        <td>{{$holiday['until_days']}}</td>
                                        <td>{{date('d-m-Y', strtotime($holiday['date_from']))}}</td>
                                        <td>{{date('d-m-Y', strtotime($holiday['date_to']))}}</td>
                                        <td>{{App\Models\Holiday::$leaveStatus[$holiday['status']]}}</td>
                                    </tr>
                                    <?php if ($key == 2){ break;} ?>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs me-3 report_smry_iconsize"></div>
                                <h5 class="rep_heading mb-0 cstn_bold"><a class="holidays_link" data-company_id="{{user()->company_id}}"> (See list )</a></h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                             <div class="d-flex align-items-center mb-2">
                                <div class="avatar-xs me-3 report_smry_iconsize">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                <i class="bx bx-sad"></i>
                                </span>
                                </div>
                                <h5 class="rep_heading mb-0 cstn_bold">Sickness Stats:</h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                             <div class="d-flex align-items-center mb-2">
                                <div class="avatar-xs me-3 report_smry_iconsize"></div>
                                <h5 class="rep_heading mb-0 cstn_bold">Best workers</h5>
                            </div>
                            <table id="datatable-workers-sickness-best" class="table align-middle table-nowrap mb-0">
                                <thead class="table-light-color">
                                    <tr>
                                        <th class="align-middle">Worker name</th>
                                        <th class="align-middle">Work days</th>
                                        <th class="align-middle">No. of sick leaves</th>
                                        <th class="align-middle">Leave Ratio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sickenessStats as $key => $sickness)
                                    <tr>
                                        <td>{{$sickness['name']}}</td>
                                        <td>{{$sickness['working_days']}}</td>
                                        <td>{{$sickness['leaves']}}</td>
                                        <td>{{$sickness['leave_ratio']}}</td>
                                    </tr>
                                    <?php if ($key == 2){ break;} ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <?php
                        $sickenessStatsDesc = $sickenessStats;
                        array_multisort( array_column($sickenessStatsDesc, "working_days"), SORT_ASC, $sickenessStatsDesc );
                        ?>
                        <div class="col-md-6">
                             <div class="d-flex align-items-center mb-2">
                                <div class="avatar-xs me-3 report_smry_iconsize"></div>
                                <h5 class="rep_heading mb-0 bold cstn_bold">Worst workers</h5>
                            </div>
                            <table id="datatable-workers-sickness-worst" class="table align-middle table-nowrap mb-0">
                                <thead class="table-light-color">
                                    <tr>
                                        <th class="align-middle">Worker name</th>
                                        <th class="align-middle">Work days</th>
                                        <th class="align-middle">No. of sick leaves</th>
                                        <th class="align-middle">Leave Ratio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sickenessStatsDesc as $key => $sickness)
                                    <tr>
                                        <td>{{$sickness['name']}}</td>
                                        <td>{{$sickness['working_days']}}</td>
                                        <td>{{$sickness['leaves']}}</td>
                                        <td>{{$sickness['leave_ratio']}}</td>
                                    </tr>
                                    <?php if ($key == 2){ break;} ?>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs me-3 report_smry_iconsize"></div>
                                <h5 class="rep_heading mb-0 cstn_bold"><a class="sicknesses_link" data-company_id="{{user()->company_id}}"> (See list )</a></h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                             <div class="d-flex align-items-center mb-2">
                                <div class="avatar-xs me-3 report_smry_iconsize">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                    <i class="bx bx-sleepy"></i>
                                </span>
                                </div>
                                <h5 class="rep_heading mb-0 cstn_bold">Non-active workers:</h5>
                            </div>
                            <table id="datatable-workers-nonactive" class="table align-middle table-nowrap mb-0">
                                <thead class="table-light-color">
                                    <tr>
                                        <th class="align-middle">Worker name</th>
                                        <th class="align-middle">Last login at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($nonActiveWorkers as $key => $worker)
                                    <tr>
                                        <td>{{$worker['first_name'] .' '. $worker['last_name']}}</td>
                                        <td>{{date('d-m-Y', strtotime($worker['last_login_at']))}}</td>
                                    </tr>
                                    <?php if ($key == 2){ break;} ?>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs me-3 report_smry_iconsize"></div>
                                <h5 class="rep_heading mb-0 cstn_bold"><a class="non_active_workers_link" data-company_id="{{user()->company_id}}"> (See list )</a></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Report summry expenses -->
    <div class="row" id="expenses_details">
        <div class="col-md-12 col-lg-12">
          <div class="card card-body card_summry">
              <h4 class="report_card_title mb-2">Expenses</h4>
                <div class="row">
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                <i class="bx bx-money"></i>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Workers' salaries: <span class="rep_heading mb-0">{{$economicalData['workers_salary_expense']}}</span></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                 <i class="bx bx-dizzy"></i>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Workers overhead: <span class="rep_heading mb-0">{{$economicalData['workers_overhead_expense']}}</span></h5>
                         </div>
                    </div>                                                                              
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <i class="bx bx-dizzy"></i>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Workers other expense: <span class="rep_heading">N/A-</span></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                             <i class="bx bx-detail"></i>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">Tools expenses: <span class="rep_heading mb-0">{{$economicalData['tool_expenses']}}</span></h5>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    @parent
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <!-- <script src="{{ env('PUBLIC_PATH') }}/skote/assets/js/pages/form-advanced.init.js"></script> -->
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/js/pages/lightbox.init.js"></script>
    
    <script type="text/javascript">
        function status(divId, element)
        {
            document.getElementById(divId).style.display = element.value == "Custom" ? 'block' : 'none';
        }
        $(document).ready(function(){

            $('#datatable-busy-projects').dataTable({
                searching: false, paging: false, info: false,
                order: [
                    [ 0, "ASC"]
                ],
            });
            
            $('#datatable-workers-nonactive').dataTable({
                searching: false, paging: false, info: false,
                order: [
                    [ 1, "DESC"]
                ],
            });
            $('#datatable-workers-sickness-best').dataTable({
                searching: false, paging: false, info: false,
                order: [
                    [ 1, "DESC"]
                ],
            });
            
            $('#datatable-workers-sickness-worst').dataTable({
                searching: false, paging: false, info: false,
                order: [
                    [ 1, "DESC"]
                ],
            });
            
            $('#datatable-hours-submission-best').dataTable({
                searching: false, paging: false, info: false,
                order: [
                    [ 4, "DESC"]
                ],
            });
            $('#datatable-hours-submission-worst').dataTable({
                searching: false, paging: false, info: false,
                order: [
                    [ 4, "ASC"]
                ],
            });
            $('#datatable-tools-in-service').dataTable({
                searching: false, paging: false, info: false,
                order: [
                    [ 3, "ASC"]
                ],
            });
            
            $('#datatable-used-vs-given-hours').dataTable({
                searching: false, paging: false, info: false,
                order: [
                    [ 4, "DESC"]
                ],
            });
            
            $('#datatable-tools-with-workers').dataTable({
                searching: false, paging: false, info: false,
                order: [
                    [ 2, "DESC"]
                ],
            });
            $('#datatable-holidays').dataTable({
                searching: false, paging: false, info: false,
                order: [
                    [ 1, "DESC"]
                ],
            });
            
            $(".complete_projects_link").on('click', function(){
                let company_id = $(this).data('company_id');
                var urlParams = new URLSearchParams(window.location.search);
                $.ajax({
                  url: '/projects/list/'+company_id+'/completed?'+urlParams.toString(),
                  type: 'GET',
                  success: function(data) {
                    $(".modal_content").html(data);
                    $(".modal-title").html('Completed projects');
                    $(".view-btn-modal").modal('show');
                  },
                  error: function(err) {
                      console.log(err);
                      alert('error');
                  }
                });      
            });
            
            $(".active_projects_link").on('click', function(){
                let company_id = $(this).data('company_id');
                var urlParams = new URLSearchParams(window.location.search);
                $.ajax({
                  url: '/projects/list/'+company_id+'/active?'+urlParams.toString(),
                  type: 'GET',
                  success: function(data) {
                    $(".modal_content").html(data);
                    $(".modal-title").html('Active projects');
                    $(".view-btn-modal").modal('show');
                  },
                  error: function(err) {
                      console.log(err);
                      alert('error');
                  }
                });
            });
            
            $(".active_workers_link").on('click', function(){
                let company_id = $(this).data('company_id');
                var urlParams = new URLSearchParams(window.location.search);
                $.ajax({
                  url: '/workers/list/'+company_id+'?'+urlParams.toString(),
                  type: 'GET',
                  success: function(data) {
                    $(".modal_content").html(data);
                    $(".modal-title").html('Active workers');
                    $(".view-btn-modal").modal('show');
                  },
                  error: function(err) {
                      console.log(err);
                      alert('error');
                  }
                });
            });
            $(".unbalanced_tools_link").on('click', function(){
                let company_id = $(this).data('company_id');
                var urlParams = new URLSearchParams(window.location.search);
                $.ajax({
                  url: '/tools/list/'+company_id+'/unbalanced?'+urlParams.toString(),
                  type: 'GET',
                  success: function(data) {
                    $(".modal_content").html(data);
                    $(".modal-title").html('Unbalanced Tools');
                    $(".view-btn-modal").modal('show');
                  },
                  error: function(err) {
                      console.log(err);
                      alert('error');
                  }
                });
            });
            
            $(".total_tools_link").on('click', function(){
                let company_id = $(this).data('company_id');
                var urlParams = new URLSearchParams(window.location.search);
                $.ajax({
                  url: '/tools/list/'+company_id+'/all?'+urlParams.toString(),
                  type: 'GET',
                  success: function(data) {
                    $(".modal_content").html(data);
                    $(".modal-title").html('All Tools');
                    $(".view-btn-modal").modal('show');
                  },
                  error: function(err) {
                      console.log(err);
                      alert('error');
                  }
                });
            });
            
            $(".tools_with_workers_link").on('click', function(){
                let company_id = $(this).data('company_id');
                var urlParams = new URLSearchParams(window.location.search);
                $.ajax({
                  url: '/tools/list/'+company_id+'/with_workers?'+urlParams.toString(),
                  type: 'GET',
                  success: function(data) {
                    $(".modal_content").html(data);
                    $(".modal-title").html('Tools with Workers');
                    $(".view-btn-modal").modal('show');
                  },
                  error: function(err) {
                      console.log(err);
                      alert('error');
                  }
                });
            });
            
            $(".tools_in_storage_link").on('click', function(){
                let company_id = $(this).data('company_id');
                var urlParams = new URLSearchParams(window.location.search);
                $.ajax({
                  url: '/tools/list/'+company_id+'/in_storage?'+urlParams.toString(),
                  type: 'GET',
                  success: function(data) {
                    $(".modal_content").html(data);
                    $(".modal-title").html('Tools in Storages');
                    $(".view-btn-modal").modal('show');
                  },
                  error: function(err) {
                      console.log(err);
                      alert('error');
                  }
                });
            });
            
            $(".holidays_link").on('click', function() {
                let holidays = '<?php echo json_encode($comingHolidays) ?>';
                holidays = jQuery.parseJSON(holidays);
                let str = '<table id="datatable-holiday-popup" class="table align-middle table-nowrap mb-0">'+
                '<thead class="table-light-color">'+
                '<tr>'+
                    '<th class="align-middle">Worker</th>'+
                    '<th class="align-middle">Days until holiday</th>'+
                    '<th class="align-middle">Date from</th>'+
                    '<th class="align-middle">Date to</th>'+
                    '<th class="align-middle">Description</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody>';
                $.each(holidays, function(index, item) {
                    str += '<tr>'+
                        '<td>'+item.worker.first_name+' '+item.worker.last_name+'</td>'+
                        '<td >'+item.until_days+'</td>'+
                        '<td >'+item.date_from+'</td>'+
                        '<td >'+item.date_to+'</td>'+
                        '<td >'+item.description+'</td>'+
                    '</tr>';
                });
                str += '</tbody></table>';
                $(".modal_content").html(str);
                $(".modal-title").html('Upcoming Holidays');
                $(".view-btn-modal").modal('show');
                
                $('#datatable-holiday-popup').dataTable({
                    searching: false, paging: false, info: false,
                    order: [
                        [ 1, "DESC"]
                    ],
                });
            });
            
            $(".busy_projects_link").on('click', function() {
                let busyProjects = '<?php echo json_encode($busyProjects) ?>';
                busyProjects = jQuery.parseJSON(busyProjects);
                let str = '<table id="datatable-busy-projects-popup" class="table align-middle table-nowrap mb-0">'+
                '<thead class="table-light-color">'+
                '<tr>'+
                    '<th class="align-middle">Id</th>'+
                    '<th class="align-middle">Name</th>'+
                    '<th class="align-middle">Active workers</th>'+
                    '<th class="align-middle">Total hours</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody>';
                $.each(busyProjects, function(index, item) {
                    str += '<tr>'+
                        '<td>'+item.id+'</td>'+
                        '<td>'+item.name+'</td>'+
                        '<td >'+item.totalWorkers+'</td>'+
                        '<td >'+item.totalHours+'</td>'+
                    '</tr>';
                });
                str += '</tbody></table>';
                $(".modal_content").html(str);
                $(".modal-title").html('Busy Projects');
                $(".view-btn-modal").modal('show');
                
                $('#datatable-busy-projects-popup').dataTable({
                    searching: false, paging: false, info: false,
                    order: [
                        [ 3, "DESC"]
                    ],
                });
            });

            $(".projects_used_hours_link").on('click', function() {
                let usedAndGivehHours = '<?php echo json_encode($topProjectsWithUsedVsGivenHours) ?>';
                usedAndGivehHours = jQuery.parseJSON(usedAndGivehHours);
                let str = '<table id="datatable-used-vs-given-hours-popup" class="table align-middle table-nowrap mb-0">'+
                '<thead class="table-light-color">'+
                '<tr>'+
                    '<th class="align-middle">Project Id</th>'+
                    '<th class="align-middle">Project name</th>'+
                    '<th class="align-middle">Used hours</th>'+
                    '<th class="align-middle">Given hours</th>'+
                    '<th class="align-middle">Used ratio</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody>';
                $.each(usedAndGivehHours, function(index, item) {
                    str += '<tr>'+
                        '<td>'+item.company_project_id+'</td>'+
                        '<td>'+item.name+'</td>'+
                        '<td >'+item.used_hours+'</td>'+
                        '<td >'+item.given_hours+'</td>'+
                        '<td >'+item.used_percent+'</td>'+
                    '</tr>';
                });
                str += '</tbody></table>';
                $(".modal_content").html(str);
                $(".modal-title").html('Projects with used vs given hours');
                $(".view-btn-modal").modal('show');
                
                $('#datatable-used-vs-given-hours-popup').dataTable({
                    searching: false, paging: false, info: false,
                    order: [
                        [ 4, "DESC"]
                    ],
                });
            });
            
            $(".workers_performance_link").on('click', function() {
                let workers = '<?php echo json_encode($workersPerformanceList) ?>';
                workers = jQuery.parseJSON(workers);
                let str = '<table id="datatable-worker-performance-popup" class="table align-middle table-nowrap mb-0">'+
                '<thead class="table-light-color">'+
                '<tr>'+
                    '<th class="align-middle">Worker</th>'+
                    '<th class="align-middle">Words in comments</th>'+
                    '<th class="align-middle">Total images</th>'+
                    '<th class="align-middle">Delayed time(hr)</th>'+
                    '<th class="align-middle">Averge sum</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody>';
                $.each(workers, function(index, item) {
                    str += '<tr>'+
                        '<td>'+item.worker_name+'</td>'+
                        '<td >'+item.total_comments+'</td>'+
                        '<td >'+item.total_images+'</td>'+
                        '<td >'+item.late_submission_hours+'</td>'+
                        '<td >'+item.average_sum+'</td>'+
                    '</tr>';
                });
                str += '</tbody></table>';
                $(".modal_content").html(str);
                $(".modal-title").html('Workers Performance List');
                $(".view-btn-modal").modal('show');
                
                $('#datatable-worker-performance-popup').dataTable({
                    searching: false, paging: false, info: false,
                    order: [
                        [ 4, "desc"]
                    ],
                });
            });
            
            $(".sicknesses_link").on('click', function() {
                let sicknesses = '<?php echo json_encode($sickenessStatsDesc) ?>';
                sicknesses = jQuery.parseJSON(sicknesses);
                let str = '<table id="datatable-sickness-popup" class="table align-middle table-nowrap mb-0">'+
                '<thead class="table-light-color">'+
                '<tr>'+
                    '<th class="align-middle">Worker</th>'+
                    '<th class="align-middle">Work days</th>'+
                    '<th class="align-middle">Leaves</th>'+
                    '<th class="align-middle">Leave ratio</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody>';
                $.each(sicknesses, function(index, item) {
                    str += '<tr>'+
                        '<td>'+item.name+'</td>'+
                        '<td >'+item.working_days+'</td>'+
                        '<td >'+item.leaves+'</td>'+
                        '<td >'+item.leave_ratio+'</td>'+
                    '</tr>';
                });
                str += '</tbody></table>';
                $(".modal_content").html(str);
                $(".modal-title").html('Sick Workers List');
                $(".view-btn-modal").modal('show');
                
                $('#datatable-sickness-popup').dataTable({
                    searching: false, paging: false, info: false,
                    order: [
                        [ 1, "desc"]
                    ],
                });
            });
            
            $(".non_active_workers_link").on('click', function() {
                let workers = '<?php echo json_encode($nonActiveWorkers) ?>';
                workers = jQuery.parseJSON(workers);
                let str = '<table id="datatable-sickness-popup" class="table align-middle table-nowrap mb-0">'+
                '<thead class="table-light-color">'+
                '<tr>'+
                    '<th class="align-middle">Worker</th>'+
                    '<th class="align-middle">Last Login at</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody>';
                $.each(workers, function(index, item) {
                    var startDate = item.last_login_at;
                    var convertedStartDate = new Date(startDate);
                    var month = ("0" + (convertedStartDate.getMonth() + 1)).slice(-2);
                    var day = ("0" + convertedStartDate.getDate()).slice(-2);
                    var year = convertedStartDate.getFullYear();
                    var shortStartDate = day + "-" + month + "-" + year;
                    
                    str += '<tr>'+
                        '<td>'+item.first_name +' '+item.last_name+'</td>'+
                        '<td >'+shortStartDate+'</td>'+
                    '</tr>';
                });
                str += '</tbody></table>';
                $(".modal_content").html(str);
                $(".modal-title").html('Non-active Workers List');
                $(".view-btn-modal").modal('show');
                
                $('#datatable-sickness-popup').dataTable({
                    searching: false, paging: false, info: false
                });
            });
            
            $(".tools_in_service_link").on('click', function() {
                let tools = '<?php echo json_encode($topToolsInService) ?>';
                tools = jQuery.parseJSON(tools);
                let str = '<table id="datatable-tools_in_service-popup" class="table align-middle table-nowrap mb-0">'+
                '<thead class="table-light-color">'+
                '<tr>'+
                    '<th class="align-middle">Tool Id</th>'+
                    '<th class="align-middle">Tool name</th>'+
                    '<th class="align-middle">Date in</th>'+
                    '<th class="align-middle">Days</th>'+
                    '<th class="align-middle">Possessor</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody>';
                $.each(tools, function(index, item) {
                    str += '<tr>'+
                        '<td>'+item.company_tool_id+'</td>'+
                        '<td>'+item.name+'</td>'+
                        '<td >'+item.date+'</td>'+
                        '<td >'+item.days+'</td>'+
                        '<td >'+item.possessor+'</td>'+
                    '</tr>';
                });
                str += '</tbody></table>';
                
                $(".modal_content").html(str);
                $(".modal-title").html('Tools In Service');
                $(".view-btn-modal").modal('show');
                
                $('#datatable-tools_in_service-popup').dataTable({
                    searching: false, paging: false, info: false,
                    order: [
                        [ 3, "desc"]
                    ],
                });
            });
            
            $(".top_worker_with_most_tools_link").on('click', function() {
                let tools = '<?php echo json_encode($topToolsWithWorkers) ?>';
                tools = jQuery.parseJSON(tools);
                let str = '<table id="datatable-tools_in_service-popup" class="table align-middle table-nowrap mb-0">'+
                '<thead class="table-light-color">'+
                '<tr>'+
                    '<th class="align-middle">Worker name</th>'+
                    '<th class="align-middle">Price</th>'+
                    '<th class="align-middle">Balancing status</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody>';
                $.each(tools, function(index, item) {
                    str += '<tr>'+
                        '<td>'+item.worker_name+'</td>'+
                        '<td >'+item.tools_price+'</td>'+
                        '<td >'+item.total_tools+' / '+item.unbalanced_tools+'</td>'+
                    '</tr>';
                });
                str += '</tbody></table>';
                
                $(".modal_content").html(str);
                $(".modal-title").html('Workers with most Tools');
                $(".view-btn-modal").modal('show');
                
                $('#datatable-tools_in_service-popup').dataTable({
                    searching: false, paging: false, info: false,
                    order: [
                        [ 2, "desc"]
                    ],
                });
            });
        });
    </script>
@endsection