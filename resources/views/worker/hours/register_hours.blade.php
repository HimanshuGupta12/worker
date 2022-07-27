@extends('layouts.worker')

@section('head')
<meta name="_token" content="{{ csrf_token() }}">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
@parent
<link href="{{ env('PUBLIC_PATH') }}/css/worker-hours/worker-custom.css" rel="stylesheet" type="text/css" />
<!-- Plugins css -->
<link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/timedropper/1.0/timedropper.css">


<link href="{{ env('PUBLIC_PATH') }}/css/worker.hoursapp.css" rel="stylesheet" type="text/css" />
<link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />

<style type="text/css">
.select-date .datepicker.datepicker-inline {
    width: 100% !important;
}
td.highlight:after{
    content:"\A";
    white-space: pre;
    width:6px;
    height:6px;
    border-radius:50%;
    background: green;
    display:grid;
}
/*Responsive */

@media only screen and (max-width: 768px){

    td.highlight:after{
        margin-left: 43%;
    }
}

@media only screen and (min-width: 768px){

    td.highlight:after{
        margin-left: 48%;
    }
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
.dropzone .dz-message{

    margin: 0em 0 !important;
}
.dz-progress {
    display: none;
}
</style>

@endsection

@section('content')
<div class="container-fluid">
    <div class="row page_headingapp">
        <div class="col-1" id="normal-back">
            <a href="{{ url()->previous() }}" class="trgr_ovrly">
            <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </a>
        </div>
        <div class="col-1" id="steps-back" style="display: none;">
            <a id="previous-steps" role="menuitem">
            <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </a>
        </div>
        <div class="col-10">
            <h4 class="mb-0 submisn_heading step_heading">{{ __('Register Hours') }}</h4>
            <!-- <div class="mt-1">Total working hours: <b>{{$total_hours}}</b></div> -->
        </div>
        <div class="col-1"></div>
    </div>

    <div class="row">
<!--        <p class="card-title-desc">Please fill in your working hours for this period. Your last declared day was on : {{ date('d.m.Y') }}</p>-->
        <div class="col-lg-12">
            <form action="{{ $url }}" method="POST" multiple="multiple" class="hours_submisn dropzone" id="hours_form" enctype="multipart/form-data">
                @csrf
                <div id="worker-hours" class="hours_sbmsn_steps">
                    <!-- Select Project -->
                    <h3></h3>
                    <section>
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="step_heading_wpr">
                                    <p class="mb-0 submisn_subheading">{{ __('Please fill in your working hours for this period.') }}</p>
                                    @if (!empty($lastWorkDay))
                                    <p class="submisn_subheading">{{ __('your last declared day was on') }}: <span style="color: #2697FF;">{{ date('d.m.Y', strtotime($lastWorkDay)) }}</span></p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="app_field_label" for="">{{ __('Select project') }}</label>
                                    @foreach ($projects as $project)
                                    <div class="form-check mb-3 project_listing select_project" data-pid="{{ $project->id }}" data-pname="{{ $project->name }}" data-company_project_id="{{ $project->company_project_id }}" style="cursor: pointer;">
                                        <input class="form-check-input" type="radio" name="project" id="{{ $project->id }}" checked="">
                                        <label class="form-check-label app_label" for="{{ $project->id }}"><div class="cstm_bdge">{{ $project->company_project_id }}</div>{{ $project->name }}<span class="line_down">{{ $project->address }}</span></label>
                                        <div class="float-end cstm_arrow"><i class="bx bx-chevron-right"></i></div>
                                    </div>
                                    <div id="add_hours_{{$project->id}}"></div>
                                    @endforeach
                                    <input type="hidden" name="project_id" id="project_id" data-project_name="" data-company_project_id="" value="">
                                </div>
                                <div class="mb-3 project-error" style="display: none;">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bx bx-error-circle"></i>
                                        <span>{{ __('Select a project to proceed further.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Select Date -->
                    <h3></h3>
                    <section class="select-date">
                        <div class="row">
                            <div class="col-lg-12">

                                 <div class="step_heading_wpr">
                                    <p class="submisn_subheading">{{ __('Please select your project date') }}</p>
                                </div>

                                <div class="mb-3 date-error" style="display: none;">
                                    <div class="fade show">
                                        <span>{{ __('Please select your project date') }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="app_field_label">{{ __('Select date') }}</label>
                                    <div data-provide="datepicker" data-date-end-date="0d" data-date="0d" class="bootstrap-datepicker-inline hours_datepicker"></div>
                                    <input type="hidden" name="work_day" id="work_day" value="">
                                </div>
                                <div class="mb-3 previous-hours" style="display: none;"></div>
                            </div>
                        </div>
                        @if(!isMobile())
                        <div class="col-sm-4"  style="display: none " id="lateSubmissionDiv">
                            <p style="color: #fff;font-size: 13px; background: #db1c5f;padding: 6px;text-align: center;border-radius: 4px;">{{$messageForLateSubmission}}</p>
                            <textarea rows="4" class="form-control col-sm-12" name="late_submission_reason" id="late_submission_reason" placeholder="{{__('Reason for late work submission')}}"></textarea>
                        </div>
                        @else
                        <div class="row "  style="display: none" id="lateSubmissionDiv">
                            <p style="color: #fff;font-size: 13px; background: #db1c5f;padding: 6px;text-align: center;border-radius: 4px;">{{$messageForLateSubmission}}</p>
                            <textarea rows="4" class="form-control col-sm-12" name="late_submission_reason" id="late_submission_reason" placeholder="{{__('Reason for late work submission')}}"></textarea>
                        </div>
                        @endif
                    </section>

                    <!-- Select time -->
                    <h3></h3>
                    <section>
                        <div>
                            <div class="row">
                                <div class="col-lg-12">

                                     <div class="step_heading_wpr">
                                        <p class="submisn_subheading">{{ __('Please select your working hours and break time') }}</p>
                                     </div>

                                    <div class="time-error" style="display: none;">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="bx bx-error-circle"></i>
                                            {{ __('At this time hours already submitted') }}
                                        </div>
                                    </div>
                                    <div class="mb-5" style="text-align: center;">
                                        <label class="app_field_label " >{{ __('Started working at') }}</label>
                                        <div class="input-group" id="timepicker-input-group">
                                            <input type="text" class="form-control" id="start_time" name="start_time" style="text-align: center; font-size: 20px;">
                                        </div>
                                    </div>
                                    <div class="mb-5" style="text-align: center;" >
                                        <label class="app_field_label" >{{ __('Finished working at') }}</label>
                                        <div class="input-group" id="timepicker-input-group1">
                                            <input type="text" class="form-control" id="end_time" name="end_time" style="text-align: center; font-size: 20px;">
                                        </div>
                                    </div>
                                    <div class="previous-time-hours" style="display: none;"></div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="app_field_label">{{ __('Break time ?') }}</label>
                                            </div>

                                            <div class="col-6 ">
                                                <input type="radio" class="btn-check lunch_break" name="lunch_break" id="lunch_yes" value="1">
                                                <label class="btn app_btn_ltblue" for="lunch_yes">&nbsp; &nbsp;{{ __('Yes') }}&nbsp;&nbsp;</label>
                                                <input type="radio" class="btn-check lunch_break" name="lunch_break" id="lunch_no" value="0">
                                                <label class="btn app_btn_blue" for="lunch_no">&nbsp;&nbsp;{{ __('No') }}&nbsp;&nbsp;</label>
                                            </div>

                                            <div class="col-6">
                                                <input type="number" id="break_time_value" class="form-control break_time" name="break_time" placeholder="0" style="font-size: 17px;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Comments and pictures -->
                    <h3></h3>
                    <section>
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="step_heading_wpr">
                                    <p class="submisn_subheading">{{ __('Please add comments about work and photo of work') }}</p>
                                </div>

                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="app_field_label" >{{ __('Comments') }}</label>
                                        <textarea class="form-control" name="comments" id="comments" rows="3" placeholder="{{ __('Write a comment...') }}"></textarea>
                                        <input type="hidden" name="allow_comments" id="allow_comments" value="" />
                                    </div>
                                </div>
                                <div class="mb-3 comment-error" style="display: none;">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bx bx-error-circle"></i>
                                        <span>{{ __("Write comments to proceed further.") }}</span>
                                    </div>
                                </div>
                                <div class="dz-message needsclick" style="background-color: #fff !important;
    border: 1px solid #F2F3F4;
    border-radius: 5px;
    padding: 15px;">
                                    <label class="app_field_label" style="float: left;">{{ __("Pictures") }}</label>

                                        <div class="fallback">
                                            <input name="images" id="images" type="file" multiple="multiple">
                                        </div>
<!--                                        <div class="dz-clickable hour_picupload">-->
                                            <div>
                                                <div class="mb-0">
                                                    <i class="display-4 bx bxs-cloud-upload text-primary"></i>
                                                </div>
                                            </div>
<!--                                        </div>-->

                                    <input type="hidden" name="allow_photos" id="allow_photos" value="" />
                                    <input type="hidden" name="count_photos" id="count_photos" value="0" />
                                    <div id="previews"></div>
                                    <p class="text-muted mb-0" style="    font-size: 12px;
    text-align: center;">{{ __("Drop photo here or click to upload") }}</p>
                                </div>
                                <div class="mb-3 image-error" style="display: none;">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bx bx-error-circle"></i>
                                        <span class="message">{{ __("Attach photo to proceed further.") }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Summry -->
                    <h3></h3>
                    <section>
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                 <div class="step_heading_wpr">
                                    <p class="submisn_subheading" style="color: #2697FF;">{{ __("Day summary") }}</p>
                                </div>
                                <div class="mb-3 hours_sumry_bg">
                                    <table class="table mb-0 hours_summary">
                                        <tbody>
                                            <tr>
                                                <td><span class="secondary_hdg">{{ __("Project") }} :</span></td>
                                                <td><div class="company_project_id cstm_bdge" style="height: 20px; padding-top: 0; padding-bottom: 0;"></div><span class="primary_hdg project_name"></span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="secondary_hdg">{{ __("Date") }}:</span></td>
                                                <td><span class="primary_hdg project_date"></span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="secondary_hdg">{{ __("Work time") }}:</span></td>
                                                <td><span class="primary_hdg project_time"></span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="secondary_hdg">{{ __("Lunch time") }}:</span></td>
                                                <td><span class="primary_hdg project_lunch_break"></span> {{ __("min") }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="secondary_hdg">{{ __("Hours worked") }}:</span></td>
                                                <td><span class="primary_hdg worked_hours"></span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="secondary_hdg">{{ __("Comments") }} :</span></td>
                                                <td><span class="primary_hdg project_hours_comment"></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </form>
        </div><!-- end col -->
    </div><!-- end row -->
</div>
@endsection

@section('scripts')
@parent
<script type="text/javascript">
    jQuery(document).ready(function () {
        translations = '<?php echo $translations ?>';
        translations = JSON.parse(translations);

    });
</script>
<script type="text/javascript" src="{{ env('PUBLIC_PATH') }}/js/worker-hours/worker-custom.js"></script>

<!-- jquery step -->
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/jquery-steps/build/jquery.steps.min.js"></script>

<!-- Plugins js -->
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/dropzone/min/dropzone.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/timedropper/1.0/timedropper.js"></script>-->
<script src="{{ env('PUBLIC_PATH') }}/js/libs/timedropper-jquery.js"></script>
<script type="text/javascript" src="{{ env('PUBLIC_PATH') }}/js/libs/rolldate/rolldate.min.js" async></script>

<script type="text/javascript">

    Dropzone.autoDiscover = false;

    jQuery(document).ready(function () {
        var projects = <?php echo $projects ?>;
        var workDays = <?php echo $workDays ?>;
        rejectedCount = 0;
        acceptedCount = 0;

        var myDropzone = new Dropzone(".dropzone", {
            paramName: "images",
            autoProcessQueue: false,
            uploadMultiple: true, // uplaod files in a single request
            parallelUploads: 20, // use it with uploadMultiple
            maxFilesize: 20, // MB
            maxFiles: 20,
            //acceptedFiles: "image/*,application/pdf,.psd",
            acceptedFiles: "image/*,application/pdf",
            addRemoveLinks: true,
            // Language Strings
            dictInvalidFileType: "Invalid File Type",
            dictCancelUpload: "Cancel",
            dictRemoveFile: "Remove",
            dictDefaultMessage: "Drop files here to upload",
            previewsContainer: "#previews",
            //resizeWidth: 800,
            //resizeHeight: 800,
            //resizeQuality: 0.95,
            //resizeMethod: "contain",
            autoQueue: true,
            init: function() {
                this.on("addedfile", function(origFile) {
                    //Global var for steps processing
                    rejectedCount = this.getRejectedFiles().length - 1;
                    acceptedCount = this.getAcceptedFiles().length + 1;

                    var MAX_WIDTH  = 800;
                    var MAX_HEIGHT = 800;

                    var reader = new FileReader();

                    // Convert file to img
                    reader.addEventListener("load", function(event) {
                      var origImg = new Image();
                      origImg.src = event.target.result;

                      origImg.addEventListener("load", function(event) {

                        var width  = event.target.width;
                        var height = event.target.height;

                        // Don't resize if it's small enough
                        if (width <= MAX_WIDTH && height <= MAX_HEIGHT) {
                          //myDropzone.enqueueFile(origFile);
                          return;
                        }

                        // Calc new dims otherwise
                        if (width > height) {
                          if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;
                          }
                        } else {
                          if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;
                          }
                        }

                        // Resize logic starts
                        var canvas = document.createElement('canvas');
                        canvas.width = width;
                        canvas.height = height;

                        var ctx = canvas.getContext("2d");
                        ctx.drawImage(origImg, 0, 0, width, height);

                        var resizedFile = base64ToFile(canvas.toDataURL(), origFile);

                        // Replace original with resized
                        $.each(myDropzone.files, function(index, item) {
                            if(item.upload.filename == origFile.name) {
                                console.log('file resized!!!!');
                                myDropzone.files[index] = resizedFile;
                                //myDropzone.enqueueFile(resizedFile);
                            }
                        });
                      });
                    });

                    reader.readAsDataURL(origFile);
                });

                this.on("removedfile", function(file) {
                    //Manually delete file from dropzone queue.
                    $.each(myDropzone.files, function(index, item) {
                        if(item.upload.filename == file.name) {
                            delete myDropzone.files[index];
                        }
                    });
                    rejectedCount = this.getRejectedFiles().length;
                    acceptedCount = this.getAcceptedFiles().length;
                });
                this.on("success", function(file, response) {
                    window.location.href = response.redirect;
                });
                this.on("errormultiple", function(file, errorMessage, xhr){
                    rejectedCount = this.getRejectedFiles().length;
                    acceptedCount = this.getAcceptedFiles().length;
                });
            },
        });

        function base64ToFile(dataURI, origFile) {
            var byteString, mimestring;

            if(dataURI.split(',')[0].indexOf('base64') !== -1 ) {
              byteString = atob(dataURI.split(',')[1]);
            } else {
              byteString = decodeURI(dataURI.split(',')[1]);
            }

            mimestring = dataURI.split(',')[0].split(':')[1].split(';')[0];

            var content = new Array();
            for (var i = 0; i < byteString.length; i++) {
              content[i] = byteString.charCodeAt(i);
            }

//            var newFile = new File(
//              [new Uint8Array(content)], origFile.name, {type: mimestring}
//            );

            var newFile = new Blob(
              [new Uint8Array(content)], {type: mimestring}
            );

            // Copy props set by the dropzone in the original file
            var origProps = [
              "upload", "status", "previewElement", "previewTemplate", "accepted"
            ];

            $.each(origProps, function(i, p) {
              newFile[p] = origFile[p];
            });

            return newFile;
        }

        var isClicked = false;
        $(document).on('click', '.select_project', function() {
            if(!isClicked){
                isClicked = true;
                var pid = $(this).data('pid');
                $("#project_id").val(pid);
                var pname = $(this).data('pname');
                var company_project_id = $(this).data('company_project_id');
                $("#project_id").data("project_name", pname);
                $("#project_id").data("company_project_id", company_project_id);
                populateProjectData (pid);

                // Trigger next step.
                $('.actions > ul > li:nth-child(2) > a').trigger('click');

                setTimeout(function(){
                    applyRollDate();
                    isClicked = false;
                },500);
            }
        });

        $(document).on('click', '.delete-hour', function() {
            var id = $(this).data('id');
            if (confirm(getTranslation('Are you sure to delete these hours..?'))) {
                $.ajax({
                    url: '/worker/delete-hours/'+id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                    },
                    success: function(result) {
                        //alert(result.message);
                        $.each($("#worker-hours").find('.hour-row-'+id), function(index, item) {
                            $(this).html(null);
                        });
                        $(".time-error > .alert").html(null);
                        $(".time-error").hide();
                    }
                });
            }
        });

        function populateProjectData (pid)
        {
            $.each(projects, function(index, item) {
                if (item.id == pid) {
                    if (item.shift_start != null) {
                        let shift_start = item.shift_start;
                        if (shift_start != 'undefined') {
                            shift_start = shift_start.split(':');
                            shift_start = [shift_start[0], shift_start[1]].join(':');
                            $('#start_time').val(shift_start);
                        } else {
                            $('#start_time').val(null);
                        }
                    } else {
                        $('#start_time').val(null);
                    }
                    if (item.shift_end != null) {
                        let shift_end = item.shift_end;
                        if (shift_end != 'undefined') {
                            shift_end = shift_end.split(':');
                            shift_end = [shift_end[0], shift_end[1]].join(':');
                            $('#end_time').val(shift_end);
                        } else {
                            $('#end_time').val(null);
                        }
                    } else {
                        $('#end_time').val(null);
                    }
                    if (item.break_time != null && item.break_time > 0) {
                        $('.break_time').val(item.break_time);
                        $('#lunch_yes').trigger('click');
                    } else {
                        $('.break_time').val(null);
                        $('#lunch_no').trigger('click');
                    }
                    if (item.allow_comments != null) {
                        $('#allow_comments').val(item.allow_comments);
                    }
                    if (item.allow_photos != null) {
                        $('#allow_photos').val(item.allow_photos);
                    }
                }
            });
        }

        jQuery('.hours_datepicker').datepicker({
            language: "pt-BR",
            beforeShowDay: function(date) {
               var hilightedDays = workDays;
               const year = date.getFullYear();
               const month = String(date.getMonth() + 1).padStart(2, '0');
               const day = String(date.getDate()).padStart(2, '0');
               const calendarDate = [year, month, day].join('-');
               if (~hilightedDays.indexOf(calendarDate)) {
                  return {classes: 'highlight'};
               }
            }
        }).on('changeDate', function() {
            $(".date-error").hide();
            var pid = $("#project_id").val();
            var work_day = $(this).datepicker('getDate');
            $("#work_day").val(work_day.toDateString().split(' ').slice(1,).join(' '));
            work_day = Date.UTC(work_day.getFullYear(), work_day.getMonth(), work_day.getDate());

            var diffinSeconds = Math.abs(Date.now() - work_day) / 1000;
            var diffinDays = Math.floor(diffinSeconds / 86400);
            var v = $('a[href*="#next"]')[0]
            if(diffinDays > {{$maxDayForLateSubmission}}){
                $('#lateSubmissionDiv').show()
                if(!$('#late_submission_reason').val()){
                    v.style.display = 'none';
                }else{
                    v.style.display = 'block';
                }
                return
            }else{
                v.style.display = 'block';
                $('#lateSubmissionDiv').hide()
            }

            work_day = work_day/1000;
            getProjectHoursByDay (pid, work_day);
        });


        $('#late_submission_reason').bind('input propertychange', function() {
            var v = $('a[href*="#next"]')[0]
            if(this.value.length){
                v.style.display = 'block'
            }else{
                v.style.display = 'none'
            }
        });

        function getProjectHoursByDay (pid, work_day)
        {
            $.ajax({
                url: '/worker/project-hours-by-day/'+ pid+'/'+work_day,
                type: 'GET',
                success: function(data) {
                    var str = '';
                    jQuery.each(data, function(index, item) {
                        let start_time = item.start_time;
                        if (start_time != undefined) {
                            start_time = start_time.split(':');
                            start_time = [start_time[0], start_time[1]].join(':');
                        }
                        let end_time = item.end_time;
                        if (end_time != 'undefined') {
                            end_time = end_time.split(':');
                            end_time = [end_time[0], end_time[1]].join(':');
                        }
                        let custom_date = new Date(item.work_day);
                        let dd = String(custom_date.getDate()).padStart(2, '0');
                        let mm = String(custom_date.getMonth() + 1).padStart(2, '0'); //January is 0!
                        let yyyy = custom_date.getFullYear();
                        custom_date = dd + '-' + mm + '-' + yyyy;

                        str += '<div class="hour-row-'+item.id+' form-check mb-3 project_listing alrt_exists">'+
                                '<label class="form-check-label app_label" for="1">'+
                                '<div class="cstm_bdge">'+item.project.company_project_id+'</div>'+item.project.name+'<span class="line_down">'+custom_date +' '+ getTranslation('From') +' '+ start_time +' '+ getTranslation('to') +' '+ end_time+'</span></label>'+
                                '<div class="float-end cstm_arrow"><a class="delete-hour" data-id="'+item.id+'"><i class="bx bx-trash-alt text-white"></i></a>'+
                               '</div></div>';
                    });
                    if (str != '') {
                        str = '<div class="row"><h5>'+getTranslation('Submitted hours on this day')+'</h5></div>' + str;
                        $(".previous-hours").html(str).show();
                    } else {
                        $(".previous-hours").html(null).hide();
                        if ($("#work_day").val() != "") {
                            // Trigger next step.
                            $('.actions > ul > li:nth-child(2) > a').trigger('click');
                        }
                    }
                },
                error: function(err) {
                    console.log(err);
                    alert('error');
                }
            });
        }

        function getTranslation (key_required) {
            let required_value = key_required;
            let keys = Object.keys(translations);
            let texts = Object.values(translations);
            $.each(keys, function( i, key ) {
                if (key == key_required) {
                    required_value = texts[i];
                }
            });
            return required_value;
        }

        $("#steps-back").on("click", function(){
            $('.actions > ul > li:first-child a').trigger("click");
        });

        function applyRollDate()
        {
            let today = new Date();
            let start_time = $('#start_time').val();
            let end_time = $('#end_time').val();
            start_time = (start_time == '') ? today.getHours()+':'+today.getMinutes() : start_time;
            end_time = (end_time == '') ? today.getHours()+':'+today.getMinutes() : end_time;
            
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = yyyy+ '-' +mm+ '-' +dd;
            new Rolldate({
                el: '#start_time',
                format: 'hh:mm',
                minStep: 5,
                value: today+' '+start_time+':00',
                lang: {
                    title: getTranslation('Start time'),
                    cancel: getTranslation('Cancel'),
                    confirm: getTranslation('Confirm'),
                    year: '',
                    month: '',
                    day: '',
                    hour: '',
                    min: '',
                    sec: ''
                }
            });
            new Rolldate({
                el: '#end_time',
                format: 'hh:mm',
                minStep: 5,
                value: today+' '+end_time+':00',
                lang: {
                    title: getTranslation('End time'),
                    cancel: getTranslation('Cancel'),
                    confirm: getTranslation('Confirm'),
                    year: '',
                    month: '',
                    day: '',
                    hour: '',
                    min: '',
                    sec: ''
                }
            });
            $('#start_time').val(start_time);
            $('#end_time').val(end_time)
        }
        $('#break_time_value').click(function(){
            this.value = null
        })

//        $("#start_time").timeDropper({
//            format: 'HH:mm',
//            meridians: false,
//            setCurrentTime: false,
//            minutesSteps: 5
//        });
//
//        jQuery("#end_time").timeDropper({
//            format: 'HH:mm',
//            meridians: false,
//            setCurrentTime: false,
//            minutesSteps: 5
//        });
    });

</script>

@endsection
