
@extends('layouts.user')
@section('head')
    @parent
    <link href="{{ env('PUBLIC_PATH') }}/css/worker-forms.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">
    <link href="/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
    
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
    
    <style type="text/css">
       .table-nowrap td .line_down {
        display: block;
    }
    td.action .mt-1 .badge {
        font-size: 15px;
        border-radius: 50%;
    } 
    img.bg_logo {
        position: absolute;
        width: 120px;
        height: 65px;
        right: 0;
        top: 0;
    } 
    img.worker-image {
        border-radius: 50%;
        width: 60px;
        height: 60px;
    }
    ul.list-group.custom_icon_list li.list-group-item {
        padding: 0.4rem 0rem;
    }
    
    .status {
        border: none;
    }
    .table-light-color
    {
        color: #495057;
    border-color: #eff2f7;
    background-color: #fbfbfb;
    }
    </style>
    @endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                         <ul class="nav nav-tabs" role="tablist" id="myTab">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#home-1" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Sickness</span> 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#profile-1" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Holidays</span> 
                                </a>
                            </li>
                        </ul>
                    </div>
                        <!-- tab content -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane fade show active" id="home-1" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap mb-0 datasort">
                                    <thead class="table-light-color">
                                        <tr>
                                            <th class="align-middle">Requested</th>
                                            <th class="align-middle" >Date Requested</th>
                                            <th class="align-middle">Duration</th>
                                            <th class="align-middle">Comments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sickness as $sickness)
                                            <tr>
                                                <td><strong>{{$sickness['worker']->first_name}} {{$sickness['worker']->last_name}}</strong><span class="line_down">({{$sickness['worker']->worker_position}})</span></td>
                                                 <!-- add date request  -->
                                                 <td><span> {{date("d-m-Y", strtotime($sickness->created_at->toDateString()))}}</span</td>
                                                <td><span>{{date("d-m-Y", strtotime($sickness->date_from))}} - {{date("d-m-Y", strtotime($sickness->date_to))}}</span>
                                                     <span class="line_down">Total days: {{$sickness->leave_duration}}</span>
                                                </td>
                                                
                                                <!--<p><span class="badge badge-pill badge-soft-warning font-size-12">Report my sick days</span></p>-->
                                                <td><p>{{$sickness->description}}</p></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No Data Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>    
                            </div>
                            <div class="tab-pane fade" id="profile-1" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap mb-0 datasort">
                                    <thead class="table-light-color">
                                        <tr>
                                            <th class="align-middle" >Requested</th>
                                            <th class="align-middle" >Date Requested</th>
                                            <th class="align-middle">Duration</th>
                                            <th class="align-middle">Comment</th>
                                            <th class="align-middle">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($holiday as $holiday)
                                         <?php
                                                $diffInSeconds = strtotime($holiday->date_to) - strtotime($holiday->date_from);
                                                $days = ($diffInSeconds / 86400)+1; //+1 is for purpose to include both start and end dates.
                                                ?>
                                            <tr>
                                                @if($holiday->status != '4')
                                                <td><strong>{{$holiday['worker']->first_name}} {{$holiday['worker']->last_name}}</strong><span class="line_down">({{$holiday['worker']->worker_position}})</span></td>

                                                <!-- add date request  -->
                                                <td><span>{{date("d-m-Y", strtotime($holiday->created_at->toDateString()))}}</span</td>
                                                <td><span>{{date("d-m-Y", strtotime($holiday->date_from))}} - {{date("d-m-Y", strtotime($holiday->date_to))}}</span>
                                                 <span class="line_down">Total days: {{$days}}</span> 
                                                </td>
                                                <td><span>{{$holiday->description}}</span</td>

                                                <td>
                                                    <form action="{{route('sickness.holiday.update')}}" method="POST">
                                                    @csrf
                                                        <div style="display:flex; justify-content:space-between;">
                                                            <?php
                                                            $class = 'primary';
                                                            $text = '';
                                                            if ($holiday->status == '1') {
                                                                $class = 'success';
                                                                $text = 'Approved';
                                                            } elseif ($holiday->status == '2') {
                                                                $class = 'danger';
                                                                $text = 'Not Approved';
                                                            } elseif ($holiday->status == '3') {
                                                                $class = 'warning';
                                                                $text = 'Pending';
                                                            } elseif ($holiday->status == '4') {
                                                                $class = 'secondary';
                                                                $text = 'Delete Request';
                                                            } 
                                                            ?>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-{{$class}} dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">{{$text}} <i class="mdi mdi-chevron-down"></i></button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item holiday-status" href="#" data-id='1' style="display: <?= $holiday->status == '1' ? 'none' : '';?>">Approved</a>
                                                                    <a class="dropdown-item holiday-status" href="#" data-id='2' style="display: <?= $holiday->status == '2' ? 'none' : '';?>">Not Approved</a>
                                                                    <a class="dropdown-item holiday-status" href="#" data-id='3' style="display: <?= $holiday->status == '3' ? 'none' : '';?>">Pending</a>
                                                                    <a class="dropdown-item holiday-status" href="#" data-id='4' style="display: <?= $holiday->status == '4' ? 'none' : '';?>">Delete Request</a>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <textarea class="form-control" type="text" name="comment" value="" placeholder="Additionals Comments"style="display:none;" ></textarea>
                                                            </div>
                                                            <div>
                                                                <button type="submit" class="btn btn-success"style="display:none;">Submit</button>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" class='status' name="status" value="{{$holiday->status}}"/>
                                                        <input type="hidden" name="sickness_id" value="{{$holiday->id}}"/>
                                                        <input type="hidden" name="worker_id" value="{{$holiday['worker']->id}}"/>
                                                    </form>
                                                </td>
                                                @else
                                                <td colspan="5" class="text-center">No Data Found</td>
                                                @endif
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No Data Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
<script type="text/javascript">

        $('.datasort').dataTable({
            paging: false
        });
        </script>
<script>let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function(html) {
        let switchery = new Switchery(html,  { size: 'small' });
    });
</script>
<script>
    $(document).ready(function(){
       

        $('a[data-bs-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if(activeTab){
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
        $(".holiday-status").on('click', function(){
            let status = $(this).data("id");
            $("textarea").hide();
            $('button[type="submit"]').hide();
            var form = $(this).closest("form");
            $(form).find('textarea').show();
            $(form).find('button[type="submit"]').show();
            $(form).find(".status").val(status);

        });
    });
</script>

@endsection