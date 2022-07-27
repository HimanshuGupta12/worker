$(function() {

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
                            '<div class="cstm_bdge">'+item.project.company_project_id+'</div>'+item.project.name+'<span class="line_down">'+ custom_date +' '+ getTranslation('From') +' '+start_time+' '+ getTranslation('to')+' '+end_time+'</span></label>'+
                            '<div class="float-end cstm_arrow"><a class="delete-hour" data-id="'+item.id+'"><i class="bx bx-trash-alt text-white"></i></a>'+
                            '</div></div>';
                });
                if (str != '') {
                    str = '<div class="row"><h5>'+getTranslation('Submitted hours on this day')+'</h5></div>' + str;
                    $(".previous-hours").html(str).show();
                } else {
                    $(".previous-hours").html(null).hide();
                }
            },
            error: function(err) {
                console.log(err);
                alert('error');
            }
        });
    }
    
    $("#worker-hours").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slide",
        /* Labels */
        labels: {
            cancel: getTranslation("Cancel"),
            current: getTranslation("current step:"),
            pagination: getTranslation("Pagination"),
            finish: getTranslation('Submit'),
            next: getTranslation("Next"),
            previous: getTranslation("Previous"),
            loading: getTranslation("Loading ...")
        },
        onInit: function (event, current) {
            $('.actions > ul > li:first-child').attr('style', 'display:none');
            $('.actions > ul > li:nth-child(2)').attr('style', 'display:none');
            // Remove dot from step number.
            $.each($("#worker-hours ul > li"), function(index, item) {
                let num_text = $(this).find('a > span.number').text();
                num_text = num_text.split('.').join('');
                $(this).find('a > span.number').text(num_text);
            });
        },
        onStepChanged: function (event, prev, next) {
            if (prev > 0) {
                $("#steps-back").show();
                $("#normal-back").hide();
                $('.actions > ul > li:first-child').attr('style', '');
                $('.actions > ul > li:nth-child(2)').attr('style', '');
                var company_project_id = $("#project_id").data('company_project_id');
                $('.step_heading').html('<span class="cstm_bdge" style="float: none; height: 20px; padding-top: 0; padding-bottom: 0;">'+company_project_id+'</span>'+$("#project_id").data('project_name'));
            } else {
                $("#steps-back").hide();
                $("#normal-back").show();
                $('.actions > ul > li:first-child').attr('style', 'display:none');
                $('.actions > ul > li:nth-child(2)').attr('style', 'display:none');
                $('.step_heading').html(getTranslation('Register Hours'));
            }
            if (prev == 4 && next == 3) {
                $('.actions > ul > li:nth-child(3) > a').text(getTranslation('Submit'));
                $('.actions > ul > li:nth-child(3) > a').attr('style', 'background-color: #FA3B4F');
                $('.actions > ul > li:nth-child(3)').attr('aria-disabled', 'false');
                $('.actions > ul > li:nth-child(3)').attr('class', '');
                $('.actions > ul > li:first-child').attr('style', '');
                $('.actions > ul > li:first-child > a').attr('style', 'display:block');
                $('.actions > ul > li:nth-child(2)').attr('style', 'display:none');
            }
            if (prev == 3 && next == 4) {
                $('.actions > ul > li:nth-child(3) > a').text(getTranslation('Submit'));
                $('.actions > ul > li:nth-child(3) > a').attr('style', '');
                $('.actions > ul > li:first-child').attr('style', '');
                $('.actions > ul > li:nth-child(2)').attr('style', '');
            }
            if ( (prev == 0 && next == 1) || (prev == 1 && next == 0)) {
                let project_id = $("#project_id").val();
                let today = new Date();
                let offsetMins = new Date().getTimezoneOffset();
                $("#work_day").val(today.toDateString().split(' ').slice(1,).join(' '));
                let work_day_ajax = Math.floor(today.getTime()/1000);
                //We deduct offset seconds from workday only to compensate default selected date on calender.
                //Calendar default date works on the basis of local timezone. 
                work_day_ajax = work_day_ajax - (offsetMins*60);
                console.log('offsetMins = '+offsetMins);
                getProjectHoursByDay (project_id, work_day_ajax);
            }
        },
        onFinished: function (event, currentIndex) {
            $(".previous-hours").html(null).hide();
            let str = '<div class="spinner-border text-primary m-1" role="status"><span class="sr-only"></span></div>';
            $('.actions > ul > li:nth-child(3) > a').hide();
            $(str).insertAfter($('.actions > ul > li:nth-child(3) > a'));
            setTimeout(function(){
                console.log('setTimeout finished');
                if ($("#allow_photos").val() == "0" && (acceptedCount == 0 && rejectedCount == 0)) {
                    //For optional images & if no image is attached submit normal form.
                    $('#hours_form').submit();
                } else {
                    // Dropzone only submits form when any image is attached.
                    Dropzone.forElement(".dropzone").processQueue();
                }
            }, 100);
        },
        onStepChanging: function (event, prevIndex, newIndex) {
            var project_id = $("#project_id").val();
            var company_project_id = $("#project_id").data('company_project_id');
            var projectName = $("#project_id").data('project_name');
            var break_time = Number($('.break_time').val());
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            var lunch_break = $('.lunch_break.selected-btn').val();
            if (lunch_break == "0") {
                break_time = 0;
            }
            var comments = $("#comments").val();
            //var images = $("#images").val();
            var worked_hours = 0;
            var worked_mins = 0;
            var work_day = $("#work_day").val();

            if ((prevIndex == 0 && newIndex == 1) || (prevIndex == 1 && newIndex == 0)) {
                if (project_id != "") {
                    $(".project-error").hide();
                    return true;
                } else {
                    $(".project-error").show();
                }
            } else if ((prevIndex == 1 && newIndex == 2) || (prevIndex == 2 && newIndex == 1)) {
                /* Hide time errors if worker comes to back step*/
                $(".time-error > .alert").html(null);
                $(".time-error").hide();
                $(".previous-time-hours").html(null).hide();
                /* Hide time errors if worker comes to back step*/
                if (work_day !== null && work_day !== "" ) {
                    $(".date-error").hide();
                    return true;
                } else {
                    $(".date-error").show();
                }
            }
            else if ((prevIndex == 2 && newIndex == 3) || (prevIndex == 3 && newIndex == 2)) {
                var returnVal = false;
                if (start_time != "" && end_time != "") {
                    
                    let startArr = start_time.split(':');
                    let finishArr = end_time.split(':');
                    let startMins = (Number(startArr[0])*60) + Number(startArr[1]);
                    let finishMins = (Number(finishArr[0])*60) + Number(finishArr[1]);
                    let diffMins = finishMins - startMins - break_time;
                    if (diffMins <= 0) {
                        $(".time-error > .alert").html('<i class="bx bx-error-circle"></i>'+getTranslation('Please select start and finish time wisely. The work duration must have some positive value after deduction of break time.'));
                        $(".time-error").show();
                        $(".previous-time-hours").html(null).hide();
                        returnVal = false;
                    } else {
                        start_time = start_time.split(':').join(';');
                        end_time = end_time.split(':').join(';');
                        $.ajax({
                            url: '/worker/project-hours-by-time/'+ project_id+'/'+work_day+'/'+start_time+'/'+end_time,
                            type: 'GET',
                            async: false,
                            success: function(data) {
                                if (typeof data.hours != 'undefined' && (data.hours).length > 0) {
                                    var str = '';
                                    jQuery.each(data.hours, function(index, item) {
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
                                        $(".time-error > .alert").html('<i class="bx bx-error-circle"></i>'+getTranslation('Hours already submitted on this time.'));
                                        $(".time-error").show();
                                        str = '<div class="row"><h5>'+getTranslation('Submitted hours on this day')+'</h5></div>' + str;
                                        $(".previous-time-hours").html(str).show();
                                        returnVal = false;
                                    } else {
                                        $(".time-error").hide();
                                        $(".previous-time-hours").html(null).hide();
                                        returnVal = true;
                                    }
                                } else {
                                    $(".time-error").hide();
                                    $(".previous-time-hours").html(null).hide();
                                    returnVal = true;
                                }
                            },
                            error: function(err) {
                                returnVal = false;
                                console.log(err);
                                alert('error');
                            }
                        });

                        if (returnVal) {
                            worked_hours = Math.floor(diffMins / 60); // hours
                            worked_mins = diffMins - (worked_hours*60); // mins
                            $("#worked_hours").text(worked_hours+':'+worked_mins);
                            $(".time-error").hide();
                            return true;
                        }
                    }
                } else {
                    returnVal = false;
                    $(".time-error > .alert").html('<i class="bx bx-error-circle">'+getTranslation('Select both start and end time to proceed next.'));
                    $(".time-error").show();
                }
                if (returnVal) {
                    worked_hours = Math.floor(diffMins / 60); // hours
                    worked_mins = diffMins - (worked_hours*60); // mins
                    $("#worked_hours").text(worked_hours+':'+worked_mins);
                    $(".time-error").hide();
                    return true;
                }
            }
            else if ((prevIndex == 3 && newIndex == 4) || (prevIndex == 4 && newIndex == 3)) {
                if (comments == "" && $("#allow_comments").val() == "1") {
                    $(".comment-error").show();
                    return false;
                } else {
                    $(".comment-error").hide();
                }
                if ($("#allow_photos").val() == "1" /*(typeof images == 'undefined' || images == '')*/) {
                    if (acceptedCount == 0 && rejectedCount == 0) {
                        $(".image-error .message").text(getTranslation('Attach photo to proceed further.'));
                        $(".image-error").show();
                        return false;
                    } else if (rejectedCount > 0) {
                        $(".image-error .message").text(getTranslation('Fix image validation errors to proceed next'));
                        $(".image-error").show();
                        return false;
                    } else if(acceptedCount > 0 && rejectedCount == 0) {
                        $(".image-error").hide();
                    }
                } else {
                    if (acceptedCount == 0 && rejectedCount == 0) {
                        $(".image-error").hide();
                    } else if (rejectedCount > 0) {
                        $(".image-error .message").text(getTranslation('Fix image validation errors to proceed next'));
                        $(".image-error").show();
                        return false;
                    } else if(acceptedCount > 0 && rejectedCount == 0) {
                        $(".image-error").hide();
                    }
                }
                
                $(".hours_summary").find(".company_project_id").text(company_project_id);
                $(".hours_summary").find(".project_name").text(projectName);
                let a = work_day.split(' ');
                let formatted_date = [a[0], a[1]].join(' ');
                formatted_date = [formatted_date, a[2]].join(', ');
                $(".hours_summary").find(".project_date").text(formatted_date);
                $(".hours_summary").find(".project_time").text(start_time+' to '+end_time);
                $(".hours_summary").find(".project_lunch_break").text(break_time);
                $(".hours_summary").find(".project_hours_comment").text(comments);
                
                let startArr = start_time.split(':');
                let finishArr = end_time.split(':');
                let startMins = (Number(startArr[0])*60) + Number(startArr[1]);
                let finishMins = (Number(finishArr[0])*60) + Number(finishArr[1]);
                let diffMins = finishMins - startMins - break_time;
                worked_hours = Math.floor(diffMins / 60); // hours
                worked_mins = diffMins - (worked_hours*60); // mins

                worked_hours = ('0' + worked_hours).slice(-2);//Display 0 on extreme left in case of single char
                worked_mins = ('0' + worked_mins).slice(-2);//Display 0 on extreme left in case of single char
                $(".hours_summary").find(".worked_hours").text(worked_hours+':'+worked_mins);
                return true;
            }
            else if ((prevIndex == 4 && newIndex == 5) || (prevIndex == 5 && newIndex == 4)) {
                return true;
            }
            //Return false to avoid to move to next step
            //return true;
        },
    })
});

$(function() {

    $(document).on('click', '.lunch_break', function() {
        var thisObj = $(this);
        $('.lunch_break').removeClass('selected-btn');
        thisObj.addClass('selected-btn');
        if(thisObj.val() === '1') {
            $(".break_time").show();
        } else {
            $(".break_time").val(null);
            $(".break_time").hide();
        }
    });
});