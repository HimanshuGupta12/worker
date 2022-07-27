@extends('layouts.user')

@section('head')
<link href="{{ URL::asset('css/worker-forms.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')

<div class="container-fluid">
    <form class="managerside_form" action="{{ route('message.store') }}" method="post" enctype="multipart/form-data" >
         @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="w-card_title">Workers</h4>
                        <div class="row"> 
                                <div class="form-check font-size-16 align-middle">
                                    <input class="form-check-input" type="checkbox" id="checkall-workers">
                                    <label class="form-check-label" for="checkall-workers">Select all</label>
                                </div>
                                @foreach ($workers as $worker)
                                    <div class="col-md-4 form-check">
                                        <input id="worker{{ $worker->id }}" class="form-check-input checkbox" type="checkbox" name="worker_ids[]" value="{{ $worker->id }}" >
                                        <label for="worker{{ $worker->id }}" class="form-check-label">{{ $worker->fullName() }}</label>
                                    </div>
                                @endforeach
                        </div>
                    </div>
                </div>    
            </div>
        </div>  
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="w-card_title">Message</h4>
                        <div class="row">
                            <!-- <div class="col-md-12">
                                <label class="form-label custom_form_label">Allow access to worker</label>
                                <span class="sub_head_title">Here we can allow and revoke access form workers.</span>
                            </div>    -->
                            <div class="col-md-4">
                                <div class="mb-3 form-check form-switch form-switch-md">
                                    <textarea class="form-control" name="text" style="height: 100px;" maxlength="{{ config('constants.SMS_TEXT_MAX_LENGTH') }}"></textarea>
                                    <!-- <font size="1" style="float: right;">(Maximum characters: {{ config('constants.SMS_TEXT_MAX_LENGTH') }})</font> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div> 
        <button class="btn btn-info mt-3 js-disable">Send</button>
    </form>
</div>

@endsection

@section('scripts')
<script>
    // Check or Uncheck All checkboxes
    $("#checkall-workers").change(function(){
        var checked = $(this).is(':checked');
        if(checked){
        $(".checkbox").each(function(){
            $(this).prop("checked",true);
        });
        }else{
        $(".checkbox").each(function(){
            $(this).prop("checked",false);
        });
        }
    });

    // Changing state of CheckAll checkbox 
    $(".checkbox").click(function(){
        if($(".checkbox").length == $(".checkbox:checked").length) {
        $("#checkall-workers").prop("checked", true);
        } else {
        $("#checkall-workers").prop("checked", false);
        }
    });
</script>

@endsection