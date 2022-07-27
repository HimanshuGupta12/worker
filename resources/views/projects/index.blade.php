@extends('layouts.user')

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
        /*input , textarea , select {*/
        /*    width: 100% !important;*/
        /*}*/
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
        .table-light-color
        {
            color: #495057;
    border-color: #eff2f7;
    background-color: #fbfbfb;
}
        }

    </style>
@endsection

@section('content')

    <div class="container-fluid">

        <div class="modal fade delete-project-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title" id="myExtraLargeModalLabel2">Project Data</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id='project_data'></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" delete_url="" id="deleteProjectAndData" class="btn btn-primary waves-effect waves-light">Delete Project with data</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade edit-description-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title" id="myExtraLargeModalLabel2">Project Description</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea id="project_description" disabled="" name="description" rows="7" style="width: 100%"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report summry -->
        <div class="row">
            <div class="col-md-9 col-lg-9">
                <div class="card card-body card_summry">
                    <h5 class="font-size-18 mb-3 report_card_title"><i class="bx bx-slider-alt"></i>  Sort</h5>
                    <form class="report_forms">
                        <div class="row mb-3">
                            <div class="col-md-4 col-lg-4 col-sm-4">
                                <div class="mb-3">
                                    <input class="form-control" name="q" value="{{$q}}" type="search" placeholder="Name or Id" id="example-search-input">
                                    <svg class="field_icon" width="16" height="18" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 11C12.7615 11 15 8.76142 15 6C15 3.23858 12.7615 1 10 1C7.23861 1 5.00003 3.23858 5.00003 6C5.00003 8.76142 7.23861 11 10 11Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M18.59 21C18.59 17.13 14.74 14 10 14C5.26003 14 1.41003 17.13 1.41003 21" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                                <div class="mb-3">
                                    <select class="form-select" id="autoSizingSelect" name="status">
                                        <option @if(isset($projectStatus) && $projectStatus == 'active') selected @endif value="active">Active</option>
                                        <option @if(isset($projectStatus) && $projectStatus == 'completed') selected @endif value="completed">Completed</option>
                                    </select>
                                    <svg class="field_icon" width="18" height="18" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.32641 15.5956C5.97557 14.899 6.96516 14.9544 7.53516 15.7144L8.33474 16.7831C8.97599 17.6302 10.0131 17.6302 10.6543 16.7831L11.4539 15.7144C12.0239 14.9544 13.0135 14.899 13.6627 15.5956C15.0718 17.0998 16.2197 16.601 16.2197 14.4952V5.57313C16.2197 2.38271 15.4756 1.58313 12.4831 1.58313H6.49808C3.50558 1.58313 2.76141 2.38271 2.76141 5.57313V14.4873C2.76933 16.601 3.92516 17.0919 5.32641 15.5956Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7.32306 7.91687H11.6772" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class=" row mb-3 row-cols-lg-auto row-cols-sm-auto">
                                    <div class="col-6"><button type="submit" class="btn btn-info-show w-md">Search</button></div>
                                    <div class="col-6"><button type="button" class="btn custom_rest_btn"><a href="{{ url()->current() }}" class="">Reset</a></button></div>


                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-3 col-lg-3" style="min-width: 279px;">
                <div class="card card-body card_hilght">
                    <h4 class="report_card_title_white">{{$filterText}} project details</h4>
                    <div class="row cstm_sprtr">
                        <div class="col-md-6">
                            <h1 class="text-white hilited_heading">{{$filteredProjectsCount}}</h1>
                            <span class="text-white font-size-13">{{$filterText}} projects</span>
                        </div>
                        <div class="col-md-6">
                            <h1 class="text-white hilited_heading">{{$filteredProjectHours}}</h1>
                            <span class="text-white font-size-13">Total hours</span>
                        </div>
                    </div>
                    <img src="{{ env('PUBLIC_PATH') }}/img/trap-logo.png" alt="" height="40" class="bg_logo">

                </div>
            </div>
        </div>

        <!-- Report summry Worker End  -->
        <!-- Entries -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <h4 class="report_card_title">Project details</h4>
                            </div>
                            <div class="col-md-6">
                                <form class="row gy-2 gx-3 float-end report_forms ">
                                    <div class="col-sm-auto mb-3">
                                        <a href="{{ route('projects.create') }}" class="text-white">
                                            <button type="button" class="btn btn-info-show waves-effect waves-light float-end ">
                                                + Add project
                                            </button>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @if($projects)
                            <div class="table-responsive">
                                <table id="datatable" class="table align-middle table-nowrap mb-0">
                                    <thead class="table-light-color">
                                    <tr>
                                        <th class="align-middle">ID</th>
                                        <th class="align-middle">Project name</th>
                                        <th class="align-middle">Leader</th>
                                        <th class="align-middle">Hours used</th>
                                        <th class="align-middle">Project description</th>
                                        <th class="align-middle">Status</th>
                                        <th class="align-middle">Last activity</th>
                                        <th class="align-middle" style="text-align:right;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($projects as $project)
                                        <?php
                                        $hourSpent = isset($projectUsedHours[$project->id]) ? $projectUsedHours[$project->id] : 0;
                                        $payment_type = ucfirst($project->payment_type);
                                        $payment_value = '';
                                        if ($project->payment_type == 'hourly')  {
                                            $payment_value = $project->hourly_rate;
                                        } elseif ($project->payment_type == 'fixed')  {
                                            $payment_value = $project->fixed_rate;
                                        } elseif ($project->payment_type == 'mixed')  {
                                            $payment_value = "Hourly: ".$project->hourly_rate.",- ".''. "Fixed: " .$project->fixed_rate;
                                        }
                                        ?>
                                        <tr>
                                            <td><p class="rep_heading">{{$project->company_project_id}}</p></td>
                                            <td >
                                                <p class="rep_heading">
                                                    {{$project->name}}</p><span class="line_down">{{isset($project->client) ? $project->client->first_name.' '.$project->client->last_name : ''}}</span>
                                            </td>
                                            <td><p class="rep_heading">{{isset($project->manager) ? $project->manager->first_name.' '.$project->manager->last_name : ''}}</p><span class="line_down"><span style="color:#2F45C5;">

                                @if($payment_value)
                                                            {{$payment_type}}:</span> {{$payment_value}},-</span></td>
                                            @endif
                                            <td>
                                                @if (isset($project->total_hours))
                                                    <?php $progress = ($hourSpent/$project->total_hours)*100;
                                                    $color = '#5b7bb';
                                                    if ($progress >= 1 && $progress < 80) {
                                                        $color = 'green';
                                                    }elseif ($progress >= 80 && $progress < 90) {
                                                        $color = 'yellow';
                                                    }elseif ($progress >= 90 && $progress < 99) {
                                                        $color = 'orange';
                                                    }elseif ($progress >= 100 && $progress < 110) {
                                                        $color = 'red';
                                                    }elseif ($progress > 110){
                                                        $color = 'darkred';
                                                    }
                                                    ?>
                                                    <p class="rep_heading">{{$hourSpent}} / out {{$project->total_hours}}</p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar" role="progressbar" style="width: {{$progress}}%; background-color: {{$color}};" aria-valuenow="{{$hourSpent}}" aria-valuemin="0" aria-valuemax="{{$project->total_hours}}"></div>
                                                    </div>
                                                @else
                                                    <p class="rep_heading">Total hours: {{$hourSpent}}</p>
                                                @endif
                                                <span class="line_down">Invoiced : {{isset($projectInvoicedHours[$project->id]) ? $projectInvoicedHours[$project->id] : '0'}}</span></td>
                                            <td><p class="rep_heading" style="color: #667685">
                                                    @if(strlen($project->description) > 30)
                                                        {{substr($project->description,0,30)}} <a data-bs-target=".edit-description-modal" data-id="{{$project->id}}" data-description="{{$project->description}}" class="edit-project-description" data-bs-toggle="modal"> ...more</a>
                                                    @else
                                                        {{$project->description}}
                                                    @endif
                                                </p>
                                            </td>
                                            <td >
                                                @if($project->quick_add)
                                                    <div style="color: #ffcf24">Incomplete</div>
                                                @endif

                                                <p class="rep_heading {{ucfirst($project->status)}}">{{ucfirst($project->status)}}</p><span class="line_down">{{$project->start_date}}</span></td>
                                            <td><p class="rep_heading">{{ $project->updated_at->format(dateFormat()) }}</p></td>

                                            <td class="action align-middle" style="text-align:right;">



                                                <div class="entries_action" >
                                                    <div class="avatar-xs me-3 report_smry_iconsize">
                                                        <a href="{{ route('projects.edit', $project->id) }}">
                                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                               <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.1 1H7.3C2.8 1 1 2.8 1 7.3V12.7C1 17.2 2.8 19 7.3 19H12.7C17.2 19 19 17.2 19 12.7V10.9" stroke="#2F45C5" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M14.2197 1.9785L8.15508 8.04398C7.92419 8.2749 7.6933 8.72904 7.64713 9.06003L7.31619 11.3769C7.19305 12.2159 7.78566 12.8009 8.62455 12.6855L10.9411 12.3545C11.2643 12.3083 11.7184 12.0774 11.957 11.8465L18.0216 5.78097C19.0683 4.73414 19.5609 3.51797 18.0216 1.9785C16.4824 0.439043 15.2664 0.93167 14.2197 1.9785Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M13.6 2.80005C14.0882 4.54175 15.451 5.9045 17.2 6.40005" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>

                                            </span>
                                                        </a>
                                                    </div>

                                                    <div class="avatar-xs me-3 report_smry_iconsize">
                                                        <a target="_blank" href="">
                                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                                                <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M7.3 8.2001C8.29411 8.2001 9.1 7.39421 9.1 6.4001C9.1 5.40599 8.29411 4.6001 7.3 4.6001C6.30589 4.6001 5.5 5.40599 5.5 6.4001C5.5 7.39421 6.30589 8.2001 7.3 8.2001Z" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M10.9 1H7.3C2.8 1 1 2.8 1 7.3V12.7C1 17.2 2.8 19 7.3 19H12.7C17.2 19 19 17.2 19 12.7V8.2" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15.4 1V6.4L17.2 4.6" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15.4 6.4001L13.6 4.6001" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M1.60303 16.2551L6.04003 13.2761C6.75103 12.7991 7.77703 12.8531 8.41603 13.4021L8.71303 13.6631C9.41503 14.2661 10.549 14.2661 11.251 13.6631L14.995 10.4501C15.697 9.84705 16.831 9.84705 17.533 10.4501L19 11.7101" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>

                                            </span>
                                                        </a>
                                                    </div>


                                                    <div class="avatar-xs me-3 report_smry_iconsize" style="text-align: right;">
                                        <span class="dropdown">
                                              <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown"   aria-expanded="false">
                                                <span class="avatar-title rounded-circle bg-white bg-soft text-warning font-size-15">
                                                  <svg width="4" height="15" viewBox="0 0 4 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                      <path d="M3.46154 13.2692C3.46154 13.7283 3.27919 14.1685 2.95461 14.4931C2.63003 14.8177 2.1898 15 1.73077 15C1.27174 15 0.831513 14.8177 0.50693 14.4931C0.182348 14.1685 0 13.7283 0 13.2692C0 12.8102 0.182348 12.37 0.50693 12.0454C0.831513 11.7208 1.27174 11.5385 1.73077 11.5385C2.1898 11.5385 2.63003 11.7208 2.95461 12.0454C3.27919 12.37 3.46154 12.8102 3.46154 13.2692ZM3.46154 7.5C3.46154 7.95903 3.27919 8.39926 2.95461 8.72384C2.63003 9.04842 2.1898 9.23077 1.73077 9.23077C1.27174 9.23077 0.831513 9.04842 0.50693 8.72384C0.182348 8.39926 0 7.95903 0 7.5C0 7.04097 0.182348 6.60074 0.50693 6.27616C0.831513 5.95158 1.27174 5.76923 1.73077 5.76923C2.1898 5.76923 2.63003 5.95158 2.95461 6.27616C3.27919 6.60074 3.46154 7.04097 3.46154 7.5ZM3.46154 1.73077C3.46154 2.1898 3.27919 2.63003 2.95461 2.95461C2.63003 3.27919 2.1898 3.46154 1.73077 3.46154C1.27174 3.46154 0.831513 3.27919 0.50693 2.95461C0.182348 2.63003 0 2.1898 0 1.73077C0 1.27174 0.182348 0.831513 0.50693 0.506931C0.831513 0.182348 1.27174 0 1.73077 0C2.1898 0 2.63003 0.182348 2.95461 0.506931C3.27919 0.831513 3.46154 1.27174 3.46154 1.73077Z" fill="#667685"/>
                                                      </svg>
                                                  </span>
                                                </a>
                                               <span class="dropdown-menu dropdown-menu-end">
                                                  <a class="dropdown-item" href="{{ route('projects.duplicate', $project->id) }}">Duplicate</a>
                                                  <a class="dropdown-item delete-project" data-url="{{ route('projects.report', $project->id) }}" >Delete</a>
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
                        @endif
                    </div>
                </div>
                {{ $projects->withQueryString()->links() }}
            </div>
        </div>
        <!-- Entries End -->
    </div>
@endsection
@section('scripts')
    @parent





    <script type="text/javascript">
        $('#datatable').dataTable({
            paging: false,
            order: [[0, 'desc']]
        });
        $(document).ready(function () {
            $(".edit-project-description").on('click', function(){
                $("#project_description").val($(this).data("description"));
            });
            $(".delete-project").on('click', function() {
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
                    if (data_html != '') {
                        $('#project_data').html(data_html);
                        $(".delete-project-modal").modal('show');
                        $("#deleteProjectAndData").attr('delete_url', result.delete_url);

                    } else {
                        // delete directly.
                        $.ajax({
                            url: '/projects-delete/'+result.id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                            },
                            success: function(result) {
                                window.location.href = result.url;
                            }
                        });
                    }
                });
            });

            $("#deleteProjectAndData").on('click', function(){
                $.ajax({
                    url: $(this).attr('delete_url'),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                    },
                    success: function(result) {
                        window.location.href = result.url;
                    }
                });
            });
        });

    </script>
@endsection
