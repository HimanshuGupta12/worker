@extends(Auth::user() ? 'layouts.user' : 'layouts.worker')
@section('head')
    <meta name="_token" content="{{ csrf_token() }}">
    @parent
    <!--    <link href="/skote/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />-->

    <!-- Lightbox css -->
    <link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />

    <link href="{{ env('PUBLIC_PATH') }}/css/worker-reports.css" rel="stylesheet" type="text/css" />


    <style type="text/css">

        .sort_icon {
            margin-top: 7px;
        }

        td.action .mt-1 .badge {
            font-size: 15px;
            border-radius: 50%;
        }
        img.worker-image {
            border-radius: 50%;
            width: 60px;
            height: 60px;
        }
        div#datatable_filter {
            display: none;
        }


        .project {
            margin: 50px;
            /*display: inline-block;*/
            position: relative;
            /*width: 300px;*/
            /*height: 200px;*/
            /*background-color: #bbb;*/
            overflow: hidden;
        }
        .project .badge.blue {
            background: darkblue;
        }
        .project .badge.red {
            background: darkred;
        }
        .project .badge.green {
            background: darkgreen;
        }
        .project .diagonal-incomplete {
            background: yellow;
            white-space: nowrap;
            position: absolute;
            /*padding: 5px 100px;*/
            min-width: 300px;
            transform: rotate(-35deg) translate(-50%, 0);
            color: black;
            text-align: center;
            text-transform: uppercase;
            font-size: 12px;
            top: -70px;
            box-sizing: border-box;
        }
        /* adding table heading bg color  */
        .thColor{
            background-color: #fbfbfb;
        }
    </style>

@endsection

@section('content')

    <div class="container-fluid">

        <div class="modal fade delete-worker-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title" id="myExtraLargeModalLabel2">Worker Data</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id='worker_data'></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" delete_url="" storage_id="" id="deleteWorkerAndData" class="btn btn-primary waves-effect waves-light">Delete Worker with data</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
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
                                                <select class="form-select" id="autoSizingSelect3" name="worker_name" placeholder="Worker name">
                                                    <option selected value=""> <i class="bx bx-slider-alt">Select worker</option>
                                                    @foreach($activeWorkers as $worker)
                                                    <option @if(isset($worker_name) && $worker_name == $worker->fullName()) selected @endif value="{{$worker->fullName()}}">{{$worker->fullName()}}</option>
                                                    @endforeach
                                                </select>
                                                <svg class="field_icon" width="16" height="18" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 11C12.7615 11 15 8.76142 15 6C15 3.23858 12.7615 1 10 1C7.23861 1 5.00003 3.23858 5.00003 6C5.00003 8.76142 7.23861 11 10 11Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M18.59 21C18.59 17.13 14.74 14 10 14C5.26003 14 1.41003 17.13 1.41003 21" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <select class="form-select" id="autoSizingSelect1" name="skills">
                                                    <option selected value=""> <i class="bx bx-slider-alt">Select category</option>
                                                    <option @if(isset($skills) && $skills == 'Carpenter') selected @endif value="Carpenter">Carpenter</option>
                                                    <option @if(isset($skills) && $skills == 'Painter') selected @endif value="Painter">Painter</option>
                                                    <option @if(isset($skills) && $skills == 'Mason') selected @endif value="Mason">Mason</option>
                                                    <option @if(isset($skills) && $skills == 'Assistant worker') selected @endif value="Assistant worker">Assistant worker</option>
                                                    <option @if(isset($skills) && $skills == 'Roofer') selected @endif value="Roofer">Roofer</option>
                                                    <option @if(isset($skills) && $skills == 'Electrician') selected @endif value="Electrician">Electrician</option>
                                                    <option @if(isset($skills) && $skills == 'Plumbing') selected @endif value="Plumbing">Plumbing</option>
                                                    <option @if(isset($skills) && $skills == 'Ventilation') selected @endif value="Ventilation">Ventilation</option>
                                                    <option @if(isset($skills) && $skills == 'Project Manager') selected @endif value="Project Manager">Project Manager</option>
                                                </select>
                                                <svg class="field_icon" width="18" height="18" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M19.2042 13.3375L13.3467 19.1951C12.0634 20.4784 9.95504 20.4784 8.66254 19.1951L2.80503 13.3375C1.5217 12.0542 1.5217 9.94589 2.80503 8.65339L8.66254 2.79587C9.94588 1.51254 12.0542 1.51254 13.3467 2.79587L19.2042 8.65339C20.4875 9.94589 20.4875 12.0542 19.2042 13.3375Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.72919 5.72913L16.2709 16.2708" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.2709 5.72913L5.72919 16.2708" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>

                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <select class="form-select" id="autoSizingSelect2" name="worker_status">
                                                    <option @if(isset($worker_status) && $worker_status == '1') selected @endif value="1">Active</option>
                                                    <option @if(isset($worker_status) && $worker_status == '0') selected @endif value="0">Inactive</option>
                                                </select>
                                                <svg class="field_icon" width="18" height="18" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.32641 15.5956C5.97557 14.899 6.96516 14.9544 7.53516 15.7144L8.33474 16.7831C8.97599 17.6302 10.0131 17.6302 10.6543 16.7831L11.4539 15.7144C12.0239 14.9544 13.0135 14.899 13.6627 15.5956C15.0718 17.0998 16.2197 16.601 16.2197 14.4952V5.57313C16.2197 2.38271 15.4756 1.58313 12.4831 1.58313H6.49808C3.50558 1.58313 2.76141 2.38271 2.76141 5.57313V14.4873C2.76933 16.601 3.92516 17.0919 5.32641 15.5956Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7.32306 7.91687H11.6772" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4">
                                            <select class="form-select" id="autoSizingSelect" name="date" onchange="status('dates', this)">
                                                <option selected="">Select date</option>
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
                                    <!-- <div class="mb-3">
                            <select class="form-select" id="autoSizingSelect3" name="worker_role">
                                <option selected value="">Permission</option>
                                <option @if(isset($worker_role) && $worker_role == 'Storage inventorization') selected @endif value="Storage inventorization">Storage inventorization</option>
                                <option @if(isset($worker_role) && $worker_role == 'See company tools') selected @endif value="See company tools">See company tools</option>
                                <option @if(isset($worker_role) && $worker_role == 'Scan to storage') selected @endif value="Scan to storage">Scan to storage</option>
                                <option @if(isset($worker_role) && $worker_role == 'Add new tools') selected @endif value="Add new tools">Add new tools</option>
                            </select>
                            <svg class="field_icon"  width="16" height="18" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M6 1V4" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M14 1V4" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M19 7.5V16C19 19 17.5 21 14 21H6C2.5 21 1 19 1 16V7.5C1 4.5 2.5 2.5 6 2.5H14C17.5 2.5 19 4.5 19 7.5Z" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6 10H14" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6 15H10" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>-->
                                        <div class="col-lg-2 col-md-4" id="dates" style='display:{{ (isset($date) && $date == "Custom" ) ? 'block' : 'none' }}'>
                                            <div>
                                                <input type="date" name='start_date' value="{{$start_date}}" class="form-control">
                                            </div>
                                            <div>
                                                <input type="date" name='end_date' value="{{$end_date}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class=" col-md-4 col-sm-auto">
                                            <div class="col-sm-auto">
                                                <div class="mb-3">
                                                    <button type="submit" class="btn btn-info-show w-md">Search</button>
                                                    <button type="button" class="btn custom_rest_btn"><a href="{{ url()->current() }}" class="">Reset</a></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Filters End-->


    <!-- Entries  -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 col-6">
                            <h4 class="report_card_title" style="margin-top: 7px;">Worker list</h4>
                        </div>

                        <div class="col-md-6 col-6">
                            <form class="row gy-2 gx-3 float-end report_forms ">
                                <div class="col-sm-auto mb-3">
                                    <a href="{{ route('workers.create') }}" class="text-white">
                                        <button type="button" class="btn btn-info-show waves-effect waves-light float-end ">
                                            + Add worker
                                        </button>
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable" class="table align-middle table-nowrap mb-0">
                                <!-- <thead class="table-light"> -->
                                <thead class="thColor">
                                <tr>
                                    <!-- <th >Id</th> -->
                                    <th >Picture</th>
                                    <th >Name</th>
                                    @if($managerDefaultAccess || in_array('tools', $managerModules))
                                        @if($worker_for_tools_access)
                                            <th>Tools / Not balanced</th>
                                        @endif
                                    @endif
                                    @if($managerDefaultAccess || in_array('projects', $managerModules))
                                        @if($worker_for_hours_access)
                                            <th>Coast / Salary</th>
                                            <th>Coast / Salary to pay</th>
                                            <th >Hours</th>
                                        @endif
                                    @endif
                                    <th >Phone</th>
                                    <th style="text-align: right;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($workers as $worker)
                                    <tr>
                                    <!-- <td><p class="rep_heading">{{$worker->id}}</p></td> -->
                                        <td class="project">
                                            @if($worker->quick_add)
                                                <div class="diagonal-incomplete">Incomplete</div>
                                            @endif
                                            @if ($worker->images)
                                                <div class="popup-gallery d-flex flex-wrap">
                                                    <a class="image-popup-no-margins" href="{{ Storage::url($worker->images) }}">
                                                        <div class="img-fluid">
                                                            <img src="{{ Storage::url($worker->images) }}" class="worker-image">
                                                        </div>
                                                    </a>
                                                    @else
                                                        <a class="image-popup-no-margins" href="{{ env('PUBLIC_PATH') }}/img/worker-default.png">
                                                            <div class="img-fluid">
                                                                <img src="{{ env('PUBLIC_PATH') }}/img/worker-default.png" class="worker-image">
                                                            </div>
                                                        </a>
                                                </div>
                                            @endif
                                        </td>
                                        <td><p class="rep_heading">{{$worker->first_name}} {{$worker->last_name}}</p>
                                            <span class="line_down"> {{$worker->allPositions->pluck('name')->join(', ', ', and ')}} </span>
                                            {{--                              <span class="line_down">{{$worker->custom_position_id ? $worker->customWorkerPosition?->name :  $worker->worker_position }}</span>--}}
                                        </td>
                                        @if($managerDefaultAccess || in_array('tools', $managerModules))
                                            @if($worker_for_tools_access)
                                                <td>{{$worker->tools_count}} / @if($worker->tools_need_inventorization_count > 0 )
                                                        <span style="color: red"> {{$worker->tools_need_inventorization_count}} </span>
                                                    @endif
                                                    @if($worker->tools_need_inventorization_count <= 0 )
                                                        <span style="color: green"> {{$worker->tools_need_inventorization_count}} </span>
                                                    @endif
                                                </td>
                                            @endif
                                        @endif
                                        @if($managerDefaultAccess || in_array('projects', $managerModules))
                                            @if($worker_for_hours_access)
                                                <td>
                                                    @if($worker->worker_cost)
                                                        Worker coast: {{ $worker->worker_cost}},- <br/>
                                                    @endif
                                                    @if($worker->worker_salary)
                                                        Worker salary: {{ $worker->worker_salary}},-
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($worker->worker_cost && isset($worker->sum_hours[0]))
                                                        Worker coast: {{$worker->sum_hours[0]->total_working_hours*$worker->worker_cost}},- <br/>
                                                    @endif
                                                    @if($worker->worker_salary && isset($worker->sum_hours[0]))
                                                        Salary to pay: {{$worker->sum_hours[0]->total_working_hours*$worker->worker_salary}},-
                                                    @endif
                                                </td>
                                                <td>{{isset($worker->sum_hours[0]) ? $worker->sum_hours[0]->total_working_hours.'h' : ''}}</td>
                                            @endif
                                        @endif
                                        <td><p class="rep_heading"><a class=" rep_heading" href="tel:+{{$worker->phone_country}} {{$worker->phone_number}}">+{{$worker->phone_country}} {{$worker->phone_number}}</a></p>
                                        </td>
                                        <td class="action align-middle" style="text-align: right;">
                                            <div class="entries_action">
                                                <div class="avatar-xs me-3 report_smry_iconsize">
                                                    <a target="_blank" href="{{ route('workers.edit', $worker->id) }}" title="Edit worker">
                                          <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                              <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                              <path d="M9.1 1H7.3C2.8 1 1 2.8 1 7.3V12.7C1 17.2 2.8 19 7.3 19H12.7C17.2 19 19 17.2 19 12.7V10.9" stroke="#2F45C5" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                                              <path d="M14.2195 1.9785L8.15489 8.04398C7.92401 8.2749 7.69312 8.72904 7.64694 9.06003L7.31601 11.3769C7.19287 12.2159 7.78547 12.8009 8.62436 12.6855L10.9409 12.3545C11.2642 12.3083 11.7182 12.0774 11.9568 11.8465L18.0214 5.78097C19.0681 4.73414 19.5607 3.51797 18.0214 1.9785C16.4822 0.439043 15.2662 0.93167 14.2195 1.9785Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                              <path d="M13.6001 2.80005C14.0884 4.54175 15.4511 5.9045 17.2001 6.40005" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                          </span>
                                                    </a>
                                                </div>
                                                <div class="avatar-xs me-3 report_smry_iconsize">
                                                    <a target="_blank" href="{{ route('sms.create', ['worker_id' => $worker->id]) }}" title="SMS">
                                          <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                                              <i class="bx bx-message-square-dots"></i>
                                          </span>
                                                    </a>
                                                </div>
                                                <div class="avatar-xs me-3 report_smry_iconsize">
                                      <span class="dropdown">
                                            <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                              <span class="avatar-title rounded-circle bg-white bg-soft text-warning font-size-15">
                                                <svg width="4" height="15" viewBox="0 0 4 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3.46154 13.2692C3.46154 13.7283 3.27919 14.1685 2.95461 14.4931C2.63003 14.8177 2.1898 15 1.73077 15C1.27174 15 0.831513 14.8177 0.50693 14.4931C0.182348 14.1685 0 13.7283 0 13.2692C0 12.8102 0.182348 12.37 0.50693 12.0454C0.831513 11.7208 1.27174 11.5385 1.73077 11.5385C2.1898 11.5385 2.63003 11.7208 2.95461 12.0454C3.27919 12.37 3.46154 12.8102 3.46154 13.2692ZM3.46154 7.5C3.46154 7.95903 3.27919 8.39926 2.95461 8.72384C2.63003 9.04842 2.1898 9.23077 1.73077 9.23077C1.27174 9.23077 0.831513 9.04842 0.50693 8.72384C0.182348 8.39926 0 7.95903 0 7.5C0 7.04097 0.182348 6.60074 0.50693 6.27616C0.831513 5.95158 1.27174 5.76923 1.73077 5.76923C2.1898 5.76923 2.63003 5.95158 2.95461 6.27616C3.27919 6.60074 3.46154 7.04097 3.46154 7.5ZM3.46154 1.73077C3.46154 2.1898 3.27919 2.63003 2.95461 2.95461C2.63003 3.27919 2.1898 3.46154 1.73077 3.46154C1.27174 3.46154 0.831513 3.27919 0.50693 2.95461C0.182348 2.63003 0 2.1898 0 1.73077C0 1.27174 0.182348 0.831513 0.50693 0.506931C0.831513 0.182348 1.27174 0 1.73077 0C2.1898 0 2.63003 0.182348 2.95461 0.506931C3.27919 0.831513 3.46154 1.27174 3.46154 1.73077Z" fill="#667685"/>
                                                    </svg>
                                              </span>
                                              </a>
                                            <span class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ $worker->workerLink() }}">App</a>
                                                <a class="dropdown-item delete-worker" data-url="{{ route('workers.report', $worker->id) }}" >Delete</a>
                                            </span>
                                       </span>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
        <!-- Entries end  -->
    </div>
</div>

@endsection

@section('scripts')
    @parent

    <!-- lightbox init js-->
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/js/pages/lightbox.init.js"></script>


    <script type="text/javascript">

        $('#datatable').dataTable({
            paging: false
        });

        function status(divId, element)
        {
            document.getElementById(divId).style.display = element.value == "Custom" ? 'block' : 'none';
        }

        $(document).ready(function () {

            $(".delete-worker").on('click', function() {
                $.ajax({
                    type: "GET",
                    url: $(this).data('url'),
                }).done(function( result ) {
                    console.log(result);
                    let data_html = '';
                    if (result.hours != null) {
                        data_html += '<div class="row">'+
                            '<div class="col-md-6"><strong>Submitted Hours</strong></div>'+
                            '<div class="col-md-6">'+result.hours+'</div>'+
                            '</div>';
                    }
                    if (result.images != null) {
                        data_html +='<div class="row">'+
                            '<div class="col-md-6"><strong>Submitted Images</strong></div>'+
                            '<div class="col-md-6">'+result.images+'</div>'+
                            '</div>';
                    }
                    if ((result.worker_tools).length !== 0) {
                        data_html +='<div class="row">'+
                            '<div class="col-md-6"><strong>Assigned Tools to Worker</strong></div>'+
                            '<div class="col-md-6">'+ Object.keys(result.worker_tools).length +'</div>'+
                            '</div>'+
                            '<br>';
                        let stores = result.company_storages;
                        if (Object.keys(result.company_storages).length > 1) {
                            data_html +='<div class="row">'+
                                '<div class="col-md-12"><h2 style="float: center;">You must scan the worker tools back to storage before deletion!</h2></div>'+
                                '</div><br/>'+
                                '<div class="row">'+
                                '<div class="col-md-6"><strong>Select storage for scanning tool</strong></div>'+
                                '<div class="col-md-6">'+
                                '<select class="form-control" id="storage_id">';
                            $.each(stores, function( i, store ) {
                                data_html +='<option value="'+store.id+'">'+ store.name +'</option>';
                            });
                            data_html +='</select>'+
                                '</div>'+
                                '</div>';
                        } else if (Object.keys(stores).length == 1) {

                            data_html +='<div class="row">'+
                                '<div class="col-md-12"><h2 style="float: center;">The worker tools will be automatically scanned to following storage</h2></div>'+
                                '</div><br/>'+
                                '<div class="row">'+
                                '<div class="col-md-6"><strong>Storage name :</strong></div>';
                            data_html +='<div class="col-md-6"><strong>'+stores[0].name+'</strong></div>';
                            $("#deleteWorkerAndData").attr('storage_id', stores[0].id);
                            console.log($("#deleteWorkerAndData").attr('storage_id'));
                            data_html +='</div>';
                        }
                    }
                    if (data_html != '') {
                        $('#worker_data').html(data_html);
                        $(".delete-worker-modal").modal('show');
                        $("#deleteWorkerAndData").attr('delete_url', result.delete_url);
                        $("#deleteWorkerAndData").attr('storage_id', $("#storage_id").val());
                    } else {
                        // delete directly.
                        $.ajax({
                            url: '/workers/'+result.id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                            },
                            success: function(result) {
                                //alert(result.message);
                                window.location.href = result.url;
                            }
                        });
                    }
                });
            });

            $(document).on("change", ".delete-worker-modal #worker_data #storage_id", function() {
                $("#deleteWorkerAndData").attr('storage_id', $(this).val());
                console.log($("#deleteWorkerAndData").attr('storage_id'));
            });

            $("#deleteWorkerAndData").on('click', function(){
                $.ajax({
                    url: $(this).attr('delete_url'),
                    type: 'POST',
                    data: {
                        "storage_id": $(this).attr('storage_id'),
                        "_token": $('meta[name="_token"]').attr('content')
                    },
                    success: function(result) {
                        //alert(result.message);
                        window.location.href = result.url;
                    }
                });
            });
        });

    </script>
@endsection
