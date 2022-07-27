
<meta name="_token" content="{{ csrf_token() }}">
    <link href="{{ env('PUBLIC_PATH') }}/css/worker-reports.css" rel="stylesheet" type="text/css" />
    <link href="{{ env('PUBLIC_PATH') }}/css/worker-forms.css" rel="stylesheet" type="text/css" />
    <style type="text/css">

    /* .vertical-menu {
        display: none;
    }
    header#page-topbar {
    display: none;
    }
    .main-content {
    width: 100%;
    margin: 0 auto !important;
    } */
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
    .datepicker .disabled.day{
        color: #adb5bd;
/*        opacity: .6;*/
    }
    .select2{width: 100% !important;}
    
   .card {
    border-radius: 7px !important;
    border: transparent;
}

  </style>

<?php

    $projectsJson = isset($projects) ? json_encode($projects->toArray()) : json_encode([]);
?>
<div class="container-fluid">
    <div class="modal fade overlapping-hours-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myExtraLargeModalLabel">Overlapping Hours</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="mb-3">
                            <div id="overlapping-hours"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveHours" class="btn btn-primary waves-effect waves-light">Save Overlapping hours</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  <form class="managerside_form" id="hours_edit_form" action="{{route('hours.update')}}" method="POST" enctype="multipart/form-data" >
    @csrf
    <input type="hidden" name="id" value="{{ $hour['id']}}" />
    <input type="hidden" name="worker_id" id="worker_id" value="{{ $hour['worker_id']}}" />

    
    <div class="row">
        <div class="col-12">
            <div id="hours-edit-fail" class="alert alert-danger" style="display: none;"></div>
        </div>
    </div>
                    
    <div class="card ">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 current-project-info">
                    <strong>Current project details</strong><br/>
                    Name : {{$hour['project']->nameAndNumber()}}<br/>
                    Address : {{$hour['project']->address}}
                </div>
                <div class="col-md-6 new-project-info"></div>
            </div>
            
        </div>    
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="w-card_title">Select Date</h4>
                    <div class="input-group" id="datepicker2">                        
                        <input type="text" class="form-control" name="work_day" data-date-format="dd M, yyyy" value="{{ date('d M, Y', strtotime($hour['work_day']))}}"
                           data-date-container='#datepicker2' data-provide="datepicker" data-date-autoclose="true">       
                    </div>
                </div> 
            </div>
        </div>    
    </div>



    <!-- <div class="mb-4">
        <label>Auto Close</label>
        <div class="input-group" id="datepicker2">
            <input type="text" class="form-control" placeholder="dd M, yyyy"
                data-date-format="dd M, yyyy" data-date-container='#datepicker2' data-provide="datepicker"
                data-date-autoclose="true">

            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
    </div> -->
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="w-card_title">Started Working at</h4>
                    <div class="input-group" id="timepicker-input-group">
                        <input type="time" class="form-control" id="start_time" name="start_time" value="{{ date('H:i', strtotime($hour['start_time']))}}">
                    </div> 
                </div> 
            </div>
        </div>  
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="w-card_title">Finished Working at</h4>
                    <div class="input-group" id="timepicker-input-group">
                        <input type="time" class="form-control" id="end_time" name="end_time" value="{{ date('H:i', strtotime($hour['end_time']))}}">
                    </div> 
                </div> 
            </div>
        </div>    

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                     <h4 class="w-card_title">Break ??</h4>
                    <div class="row">
                        <div class="col-7">
                            <div class="form-check form-switch form-switch-lg " dir="ltr">
                                <input class="form-check-input" name="lunch_break" type="checkbox" id="SwitchCheckLunchBreak" @if( $hour['lunch_break'] == '1') checked @endif >
                                <label class="form-check-label" for="SwitchCheckLunchBreak" id="SwitchCheckLunchBreakLabel">
                                    @if( $hour['lunch_break'] == '1') Yes @else No @endif
                                </label>
                            </div>
                        </div>
                        <div class="col-5">
                             <input type="text" class="break_time form-control" id="break_time" name="break_time" value="{{ $hour['break_time']}}" @if( $hour['lunch_break'] == '0') style="display: none;" @endif >
                        </div>
                    </div>
                </div> 
                
            </div>
        </div>    
    </div>
</div>
</div>
</div>
</div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                <div class="row">
                <div class="col-md-6">
                    <div class="mb-3 form-check form-switch form-switch-md">
                        <input id="change_project" class="form-check-input" type="checkbox" name="change_project" value="1">
                        <label for="change_project" class="form-check-label">Change project</label>
                    </div> 
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 project_id" style="display: none">
                        <h4 class="w-card_title">Select project</h4>
                        <select class="form-select" id="project_id" name="project_id">
                            @foreach($projects as $projRow)
                            <option value="{{$projRow->id}}" @if ($hour['project_id'] == $projRow->id ) selected @endif>{{ $projRow->nameAndNumber() }}</option>
                            @endforeach
                        </select>
                    </div> 
                </div>
            </div> 
            </div>
        </div>    
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="w-card_title">Comments</h4>
                    <textarea class="form-control" rows="3" name="comments"> {{ $hour['comments']}} </textarea>
                </div> 
            </div>
        </div>    
    </div>
    <button type="button" id="updateHours" class="btn btn-info w-md">Submit</button>
    <button type="button" style="display: none;" id="showModal" data-bs-toggle="modal" data-bs-target=".overlapping-hours-modal"></button>
  </form>
</div>

<!---------------- script start ----------->
<script src="{{ env('PUBLIC_PATH') }}/packages/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{{ env('PUBLIC_PATH') }}/js/libs/rolldate/rolldate.min.js" async></script>

<script>

jQuery(document).ready(function() {
    jQuery("#project_id").select2({
  });
});

</script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        translations = '<?php echo $translations ?>';
        translations = JSON.parse(translations);
        
    });
</script>
<script>
    jQuery(function() {
        var projects = <?php echo $projectsJson ?>;
        var initial_project_id = <?php echo $hour['project_id'] ?>;
        
        $("body").delegate(".datepicker", "focusin", function(){
            $(this).datepicker({
                format: 'dd M, yyyy',
                endDate: '0d',
            });
        });

        $('#SwitchCheckLunchBreak').change(function(e,s){
            if(e.target.checked){
                $('#SwitchCheckLunchBreakLabel').text("Yes")
                $('#break_time').show()
            }
            else{
                $('#SwitchCheckLunchBreakLabel').text("No")
                $('#break_time').hide()
            }
        })

        jQuery(document).on('click', '.lunch_break', function() {
            var thisObj = $(this);
            if(thisObj.val() === '1') {
              jQuery(".break_time").show();
            } else {
              jQuery(".break_time").val(0);
              jQuery(".break_time").hide();
            }
        });
        
        $("#project_id").on("change", function(){
            let project_id = $(this).val();
            for (let i = 0; i < projects.length; i++) {
                if (project_id == projects[i]['id']) {
                    let project_info = '<strong>New project details</strong><br/>';
                    project_info += ' Name : '+ projects[i]['name'] + '<br/>';
                    project_info += ' Address : '+  projects[i]['address'];
                    $(".new-project-info").html(project_info);
                    break;
                }
            }
        });
        
        $("#change_project").on("change", function(){
            if ($(this).is(":checked")) {
                $(".project_id").show();
            } else {
                $("#project_id").val(initial_project_id);
                $(".project_id").hide();
                $(".new-project-info").html(null);
            }
        });
        
        $("#saveHours").on('click', function(){
            $("#hours_edit_form").submit();
        });
        
        $("#updateHours").on('click', function(){
            let hourData = $("#hours_edit_form").serialize();
            $.ajax({
                url: '/hours/overlapping/'+$("#worker_id").val(),
                type: 'POST',
                data : hourData,
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                },
                success: function(data) {
                    var str = '';
                    jQuery.each(data.hours, function(index, item) {
                        str += '<div class="row hour-row-'+item.id+'" >'+
                                '<div class="col-lg-2"><div class="project-id">'+item.work_day+'</div></div>'+
                                '<div class="col-lg-4"><div class="project-name"><strong>'+item.project.name+'</strong></div></div>'+
                                '<div class="col-lg-4"><div class="project-data"><p class="date_time">'+item.start_time+' to '+item.end_time+'</p></div></div>'+
                                '<div class="col-lg-2"><strong>'+item.worker.first_name+ ' '+ item.worker.last_name +'</strong></div>'+
                            '</div><hr/>';
                    });
                    if (str != '') {
                        $("#overlapping-hours").html(str);
                        $("#showModal").trigger("click");
                    } else {
                        $("#hours-edit-fail").hide();
                        $("#overlapping-hours").html(null);
                        $("#hours_edit_form").submit();
                    }
                },
                error: function(jqXHR, exception) {
                    //console.log(err);
                    //alert('error');
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                         //msg = 'Uncaught Error.\n' + jqXHR.responseText;
                         let response = JSON.parse(jqXHR.responseText);
                         let errors = response.errors;
                         let keys = Object.keys(errors);
                         msg = '';
                         $.each(keys, function( i, key ) {
                             //console.log(errors[key]);
                             $.each(errors[key], function( k, text ) {
                                 msg += '<span>'+text+'</span><br/>';
                             });
                         });

                    }
                    $("#hours-edit-fail").show();
                    $("#hours-edit-fail").html(msg);
                }
            });
        });
    });
    </script>
    
   

