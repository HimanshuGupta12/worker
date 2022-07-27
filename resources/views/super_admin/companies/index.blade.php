@extends('layouts.super_admin')

@section('head')
<meta name="_token" content="{{ csrf_token() }}">
    @parent
    <link href="/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
    
    <!--<link href="{{ URL::asset('css/worker-reports.css') }}" rel="stylesheet" type="text/css" />-->  
    <link href="{{ env('PUBLIC_PATH') }}/css/worker-reports.css" rel="stylesheet" type="text/css" />


    <style type="text/css">



    .rep_heading.Active {
        color: #19BB43;
        font-weight: 300;
    }
    .rep_heading.Completed {
        color: #E92727;
        font-weight: 300;
    }
    .select2{
        width: 100% !important;
    }
    input , textarea , select {
        width: 100% !important;
    }
    .form-switch-md .form-check-input {
        width: 40px !important;
    }
    .custom_form_label {
        margin-bottom: 6px;
        color: #667685;
        font-size: 13px;
        display: block;
    }
    .sub_head_title {
        font-size: 11px;
        color: #667685;
        display: block;
        margin-bottom: 10px;
    }
        div#datatable_filter {
    display: none;
    }

    </style>
@endsection

@section('content')

<div class="container-fluid">

<!-- Filters -->
<div class="row">

    <div class="col-xl-12">
        <div class="row">
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium mb-2">Active Subscriptions / Workers</p>
                                <h4 class="mb-0">{{ $active_subscriptions . ' / ' . $active_workers }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium mb-2">Canceled Subscriptions</p>
                                <h4 class="mb-0">{{ $canceled_subscriptions }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium mb-2">Trial</p>
                                <h4 class="mb-0">{{ $on_trial }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium mb-2">Trial Ended</p>
                                <h4 class="mb-0">{{ $trial_ended }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium mb-2">Subscriptions Disabled</p>
                                <h4 class="mb-0">{{ $disabled_subscriptions }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <div class="col-lg-12">
        <div class="card">
           <div class="card-body">
                  <div class="row mt-3">
                    <div class="col-md-1">
                      <div class=" sort_icon">
                        <h5 class="font-size-18 mb-0"><i class="bx bx-slider-alt"></i>  Sort</h5>
                      </div>
                    </div>
                    <div class="col-md-11">
                      <form action="" method="get" class="report_forms">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                  <input class="form-control" type="text" name="company_name" value="{{$company_name}}" placeholder="Company name" id="">
                                  <svg class="field_icon" width="16" height="18" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 11C12.7615 11 15 8.76142 15 6C15 3.23858 12.7615 1 10 1C7.23861 1 5.00003 3.23858 5.00003 6C5.00003 8.76142 7.23861 11 10 11Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18.59 21C18.59 17.13 14.74 14 10 14C5.26003 14 1.41003 17.13 1.41003 21" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4">
                                <select class="form-select" id="user_access_type" name="user_access_type">
                                    <option value="">All</option>
                                    <option value="active" @if (isset($user_access_type) && $user_access_type == "active" ) selected @endif >Active</option>
                                    <option value="canceled" @if (isset($user_access_type) && $user_access_type == "canceled" ) selected @endif >Canceled</option>
                                    <option value="on_trial" @if (isset($user_access_type) && $user_access_type == "on_trial" ) selected @endif >Trial</option>
                                    <option value="trial_ended" @if (isset($user_access_type) && $user_access_type == "trial_ended" ) selected @endif >Trial Ended</option>
                                    <option value="disabled" @if (isset($user_access_type) && $user_access_type == "disabled" ) selected @endif >Subscriptions Disabled</option>
                                </select>
                                <svg class="field_icon" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.2042 13.3375L13.3467 19.1951C12.0634 20.4784 9.95504 20.4784 8.66254 19.1951L2.80503 13.3375C1.5217 12.0542 1.5217 9.94589 2.80503 8.65339L8.66254 2.79587C9.94588 1.51254 12.0542 1.51254 13.3467 2.79587L19.2042 8.65339C20.4875 9.94589 20.4875 12.0542 19.2042 13.3375Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5.72919 5.72913L16.2709 16.2708" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M16.2709 5.72913L5.72919 16.2708" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            
                            <div class="col-lg-2 col-md-4">
                                <select class="form-select" id="autoSizingSelect" name="date" onchange="status('dates', this)">
                                    <option value="">Select date</option>
                                    <option value="Last week" @if (isset($date) && $date == "Last week" ) selected @endif >Last week</option>
                                    <option value="This week" @if (isset($date) && $date == "This week" ) selected @endif >This week</option>
                                    <option value="Last and this week" @if (isset($date) && $date == "Last and this week" ) selected @endif >Last and this week</option>
                                    <option value="Previous two weeks" @if (isset($date) && $date == "Previous two weeks" ) selected @endif >Previous two weeks</option>
                                    <option value="Last month" @if (isset($date) && $date == "Last month" ) selected @endif >Last month</option>
                                    <option value="This month" @if (isset($date) && $date == "This month" ) selected @endif >This month</option>
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
                          <div class="col-sm-auto">
                            <div class="col-lg-2 col-md-4" id="dates" style='display:{{ (isset($date) && $date == "Custom" ) ? 'block' : 'none' }}'>
                            <div>
                                <input type="date" name='start_date' value="{{$start_date}}">
                            </div>
                            <div>
                                <input type="date" name='end_date' value="{{$end_date}}">
                            </div>
                            </div>
                            <!-- <input type="hidden" class="form-select" id="autoSizingSelect4"> -->
                            <div class="col-sm-auto">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-info w-md">Search</button>
                                    <button type="button" class="btn custom_rest_btn"><a href="{{ url()->current() }}" class="">Reset</a></button>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-sm-auto">
                            <div class="mb-3">
                                <a href="javascript:void(0);" id="runTrialEndNotificationCommand"><button type="button" class="btn btn-info w-md">Run trial end notification command</button></a>
                            </div>
                        </div> -->
                      </form>
                  </div>
                </div>
           </div>
        </div>
     </div>
  </div>
<!-- Filters End--> 

<!-- Entries -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mt-2">
                          <h4 class="report_card_title">Company list</h4>
                        </div>
                        <div class="col-md-6">
                            
                        </div>  
                     </div>
                     @if($companies)
                    <div class="table-responsive">
                       <table id="datatable" class="table align-middle table-nowrap  mb-0">
                          <thead class="table-light">
                             <tr>
                                <th class="align-middle">ID</th>
                                <th class="align-middle">Company Name</th>
                                <th class="align-middle">Email</th>
                                <th class="align-middle">Subscription Status</th>
                                <th class="align-middle">Workers Count</th>
                                <th class="align-middle">Hour Submissions</th>
                                <th class="align-middle">Tools Count</th>
                                <th class="align-middle">Last Tool Activity</th>
                                <th class="align-middle" style="text-align:right;">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                            @foreach ($companies as $company)
                             <tr>
                                <td><p class="rep_heading">{{ $company->id }}</p></td>
                                <td>
                                    {{ $company->name }}
                                    <span class="line_down">{{ (!empty($company->user->name)) ? $company->user->name : '' }}</span>
                                </td>
                                <td>
                                    {{ (!empty($company->user->email)) ? $company->user->email : '' }}
                                    <span class="line_down">{{ (!empty($company->user->phone_no)) ? '+' . $company->user->phone_country . ' ' . $company->user->phone_no : '' }}</span>
                                </td>
                                <td>
                                    @php
                                        $status = $sub_status = '';
                                        if(!empty($company->disable_subscription)) {
                                            $status = 'disabled';
                                        } else {
                                            $trial_ends_at = $company->user->trial_ends_at;
                                            $now = now();
                                            if($now < $trial_ends_at) {
                                                $days = $trial_ends_at->diff($now)->days;
                                                $status = 'Trial';
                                                $sub_status = ($days == 0) ? "Ends today" : "{$days}d. left";
                                            } else if(!empty($company->user->subscriptions[0])) {
                                                $status = $company->user->subscriptions[0]->stripe_status;
                                            } else if(!empty($trial_ends_at)) {
                                                $days = $trial_ends_at->diff($now)->days;
                                                $status = "Trial Ended";
                                                $sub_status = ($days == 0) ? "Today" : "{$days}d. ago";
                                            }
                                        }
                                    @endphp
                                    {{ strtoupper($status) }}
                                    @if(!empty($sub_status))
                                        <span class="line_down">{{ $sub_status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <p class="rep_heading">{{ $company->workers_count }}</p>
                                    <span class="line_down">Active : {{ $company->active_workers_count }}</span>
                                </td>
                                <td>
                                    <p class="rep_heading">Total: {{ $company->hour_count }}</p>
                                    <span class="line_down">Last Week : {{ $company->last_week_hours }}</span>
                                    <span class="line_down">Last Month : {{ $company->last_month_hours }}</span>
                                </td>
                                <td>{{ $company->tools_count }}</td>
                                <td>
                                    @if(!empty($company->tools))
                                        @foreach($company->tools->take(2) as $t => $tool)
                                            <p class="rep_heading" title="{{ $tool->name }}">{{ (strlen($tool->name) > 20) ? substr($tool->name, 0, 20) . '...' : $tool->name }} : {{ $tool->updated_at->format(dateFormat()) }}</p>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="action align-middle" style="text-align:right;">
                                    <button type="button" class="btn btn-outline-primary waves-effect waves-light accessControls" data-companyID="{{ $company->id }}" data-companyName="{{ $company->name }}">Access Control</button>
                                    @if(!empty($company->user->email))
                                        <a href="{{ route('sa.companies.subscription', $company->id) }}" target="_blank">
                                            <button type="button" class="btn btn-outline-success waves-effect waves-light">Subscription</button>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                          </tbody>
                       </table>
                    </div>
                    @endif
                 </div>
            </div>
             {{ $companies->withQueryString()->links() }}
        </div>
    </div>
        <!-- Entries End -->
</div>

<div class="modal fade" id="accessControlModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="companyName"></span>: Access Control Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="accessControlForm">
                    <input type="text" name="company_id" id="company_id" style="display: none;">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label custom_form_label">Enable/Disable access to modules for companies</label>
                            <span class="sub_head_title"></span>
                        </div>   
                        <div class="col-md-12">
                            <h5 class="w-card_title">Manager Modules</h5>
                            @if(!empty($modules['manager']))
                                @foreach($modules['manager'] as $m => $module)
                                    <div class="mb-3 form-check form-switch form-switch-md">
                                        <input class="form-check-input accesInputs" type="checkbox" name="manager_access[]" id="{{ $module['name'] }}" value="{{ $module['name'] }}">
                                        <label for="{{ $module['name'] }}" class="form-check-label">{{ $module['title'] }}</label>
                                    </div>
                                @endforeach
                            @endif
                        <div>
                        <div class="col-md-12">
                            <h5 class="w-card_title">Worker Modules</h5>
                            @if(!empty($modules['worker']))
                                @foreach($modules['worker'] as $m => $module)
                                    <div class="mb-3 form-check form-switch form-switch-md">
                                        <input class="form-check-input accesInputs" type="checkbox" name="worker_access[]" id="{{ $module['name'] }}" value="{{ $module['name'] }}">
                                        <label for="{{ $module['name'] }}" class="form-check-label">{{ $module['title'] }}</label>
                                    </div>
                                @endforeach
                            @endif
                        <div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
    @parent

    <script type="text/javascript">


        $('#datatable').dataTable({
           paging: false
        });
        $(document).ready(function () {
            
        });
        $(document).on('click', '.accessControls', function(e) {
            e.preventDefault();
            var companyID = $(this).attr('data-companyID');
            if(companyID) {
                var companyName = $(this).attr('data-companyName');
                $('#companyName').text(companyName);
                $.ajax({
                    url: "{{ route('sa.companies.getAccessControlSettings') }}",
                    type: 'GET',
                    data: { company_id: companyID },
                }).done(function(response) {
                    if(response.status == 'success') {
                        $('#company_id').val(companyID);
                        if(response.message == 'default_access') {
                            $('.accesInputs').prop("checked", "checked");
                        } else {
                            var manager_access = response.data.manager_access;
                            var worker_access = response.data.worker_access;
                            if(manager_access.length) {
                                for(var i = 0; i <= manager_access.length; i++) {
                                    $('#' + manager_access[i]).prop("checked", "checked");
                                }
                            }
                            if(worker_access.length) {
                                for(var i = 0; i <= worker_access.length; i++) {
                                    $('#' + worker_access[i]).prop("checked", "checked");
                                }
                            }
                        }
                        $("#accessControlModal").modal('show');
                    } else if(response.status == 'danger') {
                        showMessage(response.message, response.status);
                    }
                }).fail(function(jqXHR, textStatus) {
                    var errMsg = $.parseJSON(jqXHR.responseText);
                    errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                    showMessage(errMsg, 'danger');
                });
            }
        });
        $(document).on('submit', '#accessControlForm', function(e) {
            e.preventDefault();
            var formData = $(this).serializeArray();
            $.ajax({
                url: "{{ route('sa.companies.saveAccessControlSettings') }}",
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                },
            }).done(function(response) {
                if(response.status == 'success') {
                    $("#accessControlModal").modal('hide');
                }
                showMessage(response.message, response.status);
            }).fail(function(jqXHR, textStatus) {
                var errMsg = $.parseJSON(jqXHR.responseText);
                errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                showMessage(errMsg, 'danger');
            });
        });
        $('#accessControlModal').on('hidden.bs.modal', function () {
            $('#companyName').text('');
            $('#accessControlForm').trigger("reset");
        });
        $(document).on('click', '#runTrialEndNotificationCommand', function(e) {
            e.preventDefault();
            if(confirm("Are you sure?")) {
                window.location = "{{ route('sa.companies.runCommandManually') }}";
            }
        });
    
    </script>
@endsection