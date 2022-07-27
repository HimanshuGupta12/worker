



<!--<link href="{{ URL::asset('css/worker-forms.css') }}" rel="stylesheet" type="text/css" />-->
<link href="{{ env('PUBLIC_PATH') }}/css/worker-forms.css" rel="stylesheet" type="text/css" />
<style>
    .card-title {
        margin-bottom: 30px;
        display: inline-flex;
        align-items: center;
    }
    span.number {
        background: #404040;
        color: white;
        padding: 8px 13px;
        border-radius: 40px;
        margin-right: 10px;
    }
    .worker-project {
        margin-left: 0;
    }
    .worker-project label{
        padding: 0;
    }
    .col-md-6.client_data .client_sub {
        color: #667685;
        font-size: 13px;
    }
    .proj_enc_tabs .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        background-color: #2697FF;
        border-radius: 5px;
    }
    .proj_enc_tabs .nav-pills>li>a, .nav-tabs>li>a {
        border: 1px solid #F2F3F4;
        margin-right: 8px;
        color: #2F45C5;
    }
    .col-md-6.proj_enc_tabs .tab-content.p-3.text-muted {
        padding-left: 0px !important;
    }
    .start_time_hh input {
        display: inline-block;
        width: 48%;
    }
    .end_time_hh input {
        display: inline-block;
        width: 48%;
    }

    .proj_timeing input::placeholder {
        color: #e1dddd !important;
    }
     @media only screen and (min-width: 280px) and (max-width: 768px)
        {
.save-mb {
    font-size: 17px;
}
}
</style>
<?php
$clientsJson = isset($clients) ? json_encode($clients->toArray()) : json_encode([]);
?>




<div class="container-fluid">
    <div class="modal fade create-client-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myExtraLargeModalLabel">Create Client</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $client_store_url = route('clients.store');
                    ?>
                    @include('partial.client_add', compact('client', 'client_store_url', 'project', 'page', 'url'))
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <form class="managerside_form" action="{{ $url }}" method="post" enctype="multipart/form-data">
        @csrf


        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <div class="">
{{--                        <h4 class="w-card_title">Activate Quick Add Form</h4>--}}
                        <div class="row">
                            <div class="col-md-12">
                                {{--                                <label class="form-label custom_form_label">Allow access to worker</label>--}}
                                {{--                                <span class="sub_head_title">Here we can allow and revoke access form workers.</span>--}}
                            </div>
                            @php
                                $quick_mode = $quickMode ?? request()->get('mode') == "quick";
                                if(!$quick_mode)
                                    $quick_mode = old('show_quick_add_project', null) !== null;

                            @endphp
                        </div>
                         <div class="col-md-12">
                                <div class="mb-3 form-check form-switch form-switch-md">
                                    <input class="form-check-input" type="checkbox" name="show_quick_add_project" id="show_quick_add_project"   @if ($quick_mode ) checked value="{{$quick_mode}}" @else value="0" @endif>
                                    <label for="show_quick_add" class="form-check-label">Show quick add</label>
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
                        <h4 class="w-card_title workerss"><img src="/img/Project_details.png" alt="worker">&nbsp;Project details</h4>
                        @if($page == 'edit')
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check form-switch form-switch-md mb-3">
                                        <input id="project_status" class="form-check-input" type="checkbox" name="status" @if ($project->status == 'completed') checked @endif>
                                        <label for="project_status" class="form-check-label">Complete project</label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">Project name</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $project->name) }}" placeholder="Enter project name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3" id="div-project-detail-a">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">Project start date</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $project->start_date) }}" />
                                </div>
                            </div>
                        </div>

                        <div class="row" id="div-project-detail-b">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">Project location and address*</label>
                                    <input class="form-control" type="text" name="address" value="{{ old('address', $project->address) }}" placeholder="Enter street address">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">Zip</label>
                                    <input type="text" class="form-control" name="postcode" value="{{ old('postcode', $project->postcode) }}" placeholder="Enter postel code">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3 ">
                                    <label for="formrow-inputCity" class="form-label custom_form_label">City</label>
                                    <input class="form-control" type="text" name="city" value="{{ old('city', $project->city) }}" placeholder="City">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3 ">
                                    <label for="formrow-inputCity" class="form-label custom_form_label">Country</label>
                                    <select class="form-select" id="autoSizingSelect" name="country">
                                        <option value="Denmark" {{ old('country') == "Denmark" ? 'selected' : '' }}>Denmark</option>
                                        <option value="Norway" {{ old('country') == "Norway" ? 'selected' : '' }}>Norway</option>
                                        <option value="Sweden" {{ old('country') == "Sweden" ? 'selected' : '' }}>Sweden</option>
                                        <option value="Lithuanian" {{ old('country') == "Lithuanian" ? 'selected' : '' }}>Lithuanian</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row" id="div-project-detail-c">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">Internal project descriptions for managers</label>
                                    <textarea class="form-control" name="description" style="height: 100px;" placeholder="Write a descriptions">{{ old('description', $project->description) }}</textarea>
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
                        <h4 class="w-card_title workerss"><img src="/img/Daily_working_hours.png" alt="worker">&nbsp;Daily working hours on the project</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label custom_form_label">Shift start time</label>
                                            <input type="hidden" name="shift_start" id="shift_start" value="{{!empty($project->shift_start) ? substr($project->shift_start,0,-3) : null}}">
                                            <div class="proj_timeing row" >
                                                <?php
                                                if (!empty($project->shift_start)) {
                                                    $shift_start = explode(':', $project->shift_start);
                                                    $start_hours = $shift_start[0];
                                                    $start_mins = $shift_start[1];
                                                } else {
                                                    $start_hours = null;
                                                    $start_mins = null;
                                                }
                                                ?>
                                                <div class="start_time_hh @error('shift_start') is-invalid @enderror">
                                                    <input type="number" name="start_hours" id="start_hours" placeholder="07" class="form-control timess" maxlength="2" min="0" max="23" value="{{ old('start_hours', $start_hours) }}" />
                                                    <input type="number" name="start_mins" id="start_mins" placeholder="00" class="form-control timess" maxlength="2" min="0" max="55" step="5" value="{{ old('start_mins', $start_mins) }}"/>
                                                </div>
                                                @error('shift_start')
                                                <span class="invalid-feedback" role="alert">
                                                      <strong>{{ $message }}</strong>
                                                  </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label custom_form_label">Shift end time</label>
                                            <input type="hidden" name="shift_end" id="shift_end" value="{{!empty($project->shift_end) ? substr($project->shift_end,0,-3) : null}}">
                                            <div class="proj_timeing row" >
                                                <?php
                                                if (!empty($project->shift_end)) {
                                                    $shift_end = explode(':', $project->shift_end);
                                                    $end_hours = $shift_end[0];
                                                    $end_mins = $shift_end[1];
                                                } else {
                                                    $end_hours = null;
                                                    $end_mins = null;
                                                }
                                                ?>
                                                <div class="end_time_hh @error('shift_end') is-invalid @enderror">
                                                    <input type="number" name="end_hours" id="end_hours" placeholder="15" class="form-control timess" maxlength="2" min="0" max="23" value="{{ old('end_hours', $end_hours) }}"/>
                                                    <input type="number" name="end_mins" id="end_mins" placeholder="00" class="form-control timess" maxlength="2" min="0" max="55" step="5" value="{{ old('end_mins', $end_mins) }}"/>
                                                </div>
                                                @error('shift_end')
                                                <span class="invalid-feedback" role="alert">
                                                      <strong>{{ $message }}</strong>
                                                  </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label custom_form_label">Daily breaks in minutes</label>
                                            <input class="form-control break_time" type="number" name="break_time" min="0" max="90" step="1" value="{{ old('break_time', $project->break_time) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label custom_form_label">Length of day</label>
                                            <p class="length_of_day">{{$day_duration}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label custom_form_label">Total working time</label>
                                            <p class="working_time">{{$worked_duration}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="div-add-workers">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="w-card_title workerss"><img src="/img/Add_workers.png" alt="worker">&nbsp;Add workers to the project</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label custom_form_label">Select project leader</label><br>
                                            <!--           <span class="sub_head_title">Select the worker who will be responsible for the coordination of the project </span> -->
                                            <select name="manager_id" class="form-select">
                                                <option value="">-- Select Manager --</option>
                                                @foreach ($workers as $worker)
                                                    <option value="{{ $worker->id }}" @if ($project->manager_id == $worker->id) selected @endif>{{ $worker->fullName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3 row arrange_checkbox">
                                    <label class="form-label custom_form_label">Pick workers who work on this project</label>
                                    <div class="form-check col-md-3 col-xl-2">
                                        <input id="worker_all" class="form-check-input" type="checkbox">
                                        <label for="worker_all" class="form-check-label">Select all</label>
                                    </div>
                                    @foreach ($workers as $worker)
                                        <div class="form-check col-md-3 col-xl-2">
                                            <input id="worker{{ $worker->id }}" class="form-check-input worker-check-input" type="checkbox" name="worker_ids[]" value="{{ $worker->id }}" @if ($project->workers->contains($worker)) checked @endif>
                                            <label for="worker{{ $worker->id }}" class="form-check-label">{{ $worker->fullName() }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label custom_form_label">Require workers to additionaly submit:</label>
                                <!-- <span class="sub_head_title">When submitting daily hours workers will be requested to provide photos of the completed work and fill in a comment field.</span> -->

                                <div class="mb-3 form-check form-switch form-switch-md">
                                    <input id="allow_comments" class="form-check-input" type="checkbox" name="allow_comments" value="1" @if ($project->allow_comments) checked @endif>
                                    <label for="allow_comments" class="form-check-label">Comments</label>
                                </div>
                                <div class="form-check form-switch form-switch-md mb-3">
                                    <input id="allow_photos" class="form-check-input" type="checkbox" name="allow_photos" value="1" @if ($project->allow_photos) checked @endif>
                                    <label for="allow_photos" class="form-check-label">Photos</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add client -->

        <div class="row" id="div-client-details">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="w-card_title workerss"><img src="/img/Client_details.png" alt="worker">&nbsp;Client details</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label custom_form_label">Client details</label><br>
                                            <div class="mb-3 form-check form-switch form-switch-md">
                                                <input id="allow_clients" class="form-check-input" type="checkbox" name="add_client" value="1" @if ($project->add_client) checked @endif>
                                                <label for="allow_clients" class="form-check-label">Add client</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clients-div">
                                    <div class="col-sm-12">
                                        <button type="button" class="link_btn" data-bs-toggle="modal" data-bs-target=".create-client-modal">Create New Client</button></div>
                                    <div class="col-md-4">
                                        <label class="form-label custom_form_label">Choose client</label>
                                        <select name="client_id" class="form-select clients-dropdown">
                                            <option value="">-- Select Client --</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" @if ($project->client_id == $client->id) selected @endif>{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 client_data">
                                        <label class="form-label custom_form_label">Client details:</label >
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-xs me-3">
                                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-18">
                                                <i class="bx bx-buildings"></i>
                                            </span>
                                            </div>
                                            <h5 class="font-size-13 mb-0">
                                                <span class="company_name_label"><?php (isset($client) && $client->type=='business' ? 'Company Name:' : 'Name' ) ?></span>
                                                <span class="client_sub client_company_name">
                                                <?php
                                                    $name = '';
                                                    if (isset($client) && $client->type=='business') {
                                                        $name = $client->company_name;
                                                    } elseif (isset($client) && $client->type=='private') {
                                                        $name = $client->name;
                                                    }
                                                    ?>
                                                    {{$name}}
                                            </span>
                                            </h5>
                                        </div>

                                        <div class="d-flex align-items-center mb-3 company_org_no" style="display: <?php echo (isset($client) && $client->type == 'business') ? 'block' : 'none !important'; ?>">
                                            <div class="avatar-xs me-3">
                                        <span class="avatar-title rounded-circle bg-success bg-soft text-success font-size-18">
                                        <i class="bx bx-detail"></i>
                                        </span>
                                            </div>
                                            <h5 class="font-size-13 mb-0">Company No:<span class="client_sub client_company_org_no">{{isset($client) ? $client->company_org_no : ''}}</span></h5>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-xs me-3">
                                        <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-18">
                                        <i class="bx bx-mail-send"></i>
                                        </span>
                                            </div>
                                            <h5 class="font-size-13 mb-0">Email:<span class="client_sub client_email">{{isset($client) ? $client->email : ''}}</span></h5>
                                        </div>

                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-xs me-3">
                                        <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-18">
                                        <i class="bx bx-phone-call"></i>
                                        </span>
                                            </div>
                                            <h5 class="font-size-13 mb-0 ">Phone:<span class="client_sub client_phone">{{isset($client) ? $client->phone_country.' '.$client->phone_number : ''}}</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add project time and economical details -->
        <div class="row" id="div-project-time">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="w-card_title workerss"><img src="/img/economicals_data.png" alt="worker">&nbsp;Project time and economical details</h4>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label custom_form_label">Economical and time details</label><br>
                                <div class="mb-3 form-check form-switch form-switch-md">
                                    <input id="allow_economical_details" class="form-check-input" type="checkbox" name="add_economical_details" value="1" @if ($project->add_economical_details) checked @endif>
                                    <label for="allow_economical_details" class="form-check-label">Add project time and economical details</label>
                                </div>
                            </div>

                            <div class="economical_details_div">
                                <div class="col-md-8 proj_enc_tabs">
                                    <label class="form-label custom_form_label">Contractual payment terms</label>
                                    <br>
                                    <ul class="nav nav-pills nav-justified" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link {{ ($project->payment_type) == 'hourly' ? 'active' : '' }}" data-bs-toggle="tab" href="#hourly" role="tab" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="bx bx-time-five"></i></span>
                                                <span class="d-none d-sm-block">Hourly payment</span>
                                            </a>
                                        </li>

                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link {{ ($project->payment_type) == 'fixed' ? 'active' : '' }}" data-bs-toggle="tab" href="#fixed" role="tab" aria-selected="false">
                                                <span class="d-block d-sm-none"><i class="bx bx-reset"></i></span>
                                                <span class="d-none d-sm-block">Fixed Price</span>
                                            </a>
                                        </li>

                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link {{ ($project->payment_type) == 'mixed' ? 'active' : '' }}" data-bs-toggle="tab" href="#mixed" role="tab" aria-selected="false">
                                                <span class="d-block d-sm-none"><i class="bx bx-analyse"></i></span>
                                                <span class="d-none d-sm-block">Mixed</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane {{ ($project->payment_type) == 'hourly' ? 'active' : '' }}" id="hourly" role="tabpanel">
                                            <div class="col-md-4">
                                                <label class="form-label custom_form_label">Hourly payment rate</label>
                                                <input class="form-control hourly_hourly_rate" type="number" name="hourly_hourly_rate" value="{{ ($project->payment_type) == 'hourly' ? $project->hourly_rate : null }}" placeholder="Enter rate">
                                            </div>
                                        </div>
                                        <div class="tab-pane {{ ($project->payment_type) == 'fixed' ? 'active' : '' }}" id="fixed" role="tabpanel">
                                            <div class="col-md-4">
                                                <label class="form-label custom_form_label">Fixed payment rate</label>
                                                <input class="form-control fixed_fixed_rate" type="number" name="fixed_fixed_rate" value="{{ ($project->payment_type) == 'fixed' ? $project->fixed_rate : null }}" placeholder="Enter rate">
                                            </div>
                                        </div>

                                        <div class="tab-pane {{ ($project->payment_type) == 'mixed' ? 'active' : '' }}" id="mixed" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label custom_form_label">Hourly payment rate</label>
                                                    <input class="form-control mixed_hourly_rate" type="number" name="mixed_hourly_rate" value="{{ ($project->payment_type) == 'mixed' ? $project->hourly_rate : null }}" placeholder="Enter rate">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label custom_form_label">Fixed payment rate</label>
                                                    <input class="form-control mixed_fixed_rate" type="number" name="mixed_fixed_rate" value="{{ ($project->payment_type) == 'mixed' ? $project->fixed_rate : null }}" placeholder="Enter rate">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="payment_type" id="payment_type" value="{{ $project->payment_type }}"/>
                                    <input type="hidden" name="hourly_rate" id="hourly_rate" value="{{ $project->hourly_rate }}"/>
                                    <input type="hidden" name="fixed_rate" id="fixed_rate" value="{{ $project->fixed_rate }}"/>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <label class="form-label custom_form_label">Total number of hours gvien to complete this project</label>
                                    <span class="sub_head_title">If provided - this number will be used as a reference to track time usage.</span>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <input class="form-control" type="number" name="total_hours" value="{{ $project->total_hours }}" placeholder="Enter hours">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-5">
         <button class="btn btn-info mt-3 js-disable save-mb">Save</button>
        </div>
    </form>
</div>




@push('scriptsStack')
    <script type="text/javascript">
        $(document).ready(function(){

            var checked = document.getElementById('show_quick_add_project')
            toggleShowQuickAdd(checked && checked.value === "1" )

            jQuery(".timess").keyup(function () {

                if (this.value.length == this.maxLength) {
                    jQuery(this).next('.timess').focus();
                }
            });

            var clients = <?php echo $clientsJson ?>;
            let tab = $('.proj_enc_tabs > ul > li > a.active').attr('href');
            if (typeof tab !== 'undefined') {
                tab = tab.substring(1);
                $("#payment_type").val(tab);
                $("#hourly_rate").val($("."+tab+"_hourly_rate").val());
                $("#fixed_rate").val($("."+tab+"_fixed_rate").val());
            }
            $('.proj_enc_tabs > ul > li > a').on("click",function () {
                let tab = $(this).attr('href');
                tab = tab.substring(1);
                $("#payment_type").val(tab);
            });

            $(".hourly_hourly_rate").on('change', function () {
                $("#hourly_rate").val($(".hourly_hourly_rate").val());
                $("#fixed_rate").val(null);
            });
            $(".fixed_fixed_rate").on('change', function () {
                $("#hourly_rate").val(null);
                $("#fixed_rate").val($(".fixed_fixed_rate").val());
            });
            $(".mixed_hourly_rate").on('change', function () {
                $("#hourly_rate").val($(".mixed_hourly_rate").val());
            });
            $(".mixed_fixed_rate").on('change', function () {
                $("#fixed_rate").val($(".mixed_fixed_rate").val());
            });

            let client_id = $('.clients-dropdown').val();
            if (client_id != null) {
                selectClient (client_id, clients);
            }
            $('.clients-dropdown').on('change', function(){
                let client_id = $(this).val();
                selectClient (client_id, clients);
            });

            function selectClient (client_id, clients)
            {
                for (let i = 0; i < clients.length; i++) {
                    if (client_id == clients[i]['id']) {
                        populateClient (clients[i]);
                        break;
                    }
                }
            }
            function populateClient (client)
            {
                $(".client_company_name").text('');
                $(".client_company_org_no").text('');
                $(".client_email").text('');
                $(".client_phone").text('');
                if (client['company_org_no'] != null) {
                    $(".client_company_org_no").text(client['company_org_no']);
                }
                var name = '';
                if (client['type'] == 'business') {
                    $('.company_org_no').show();
                    $('.company_org_no').addClass("d-flex");
                    $('.company_org_no').css('display','block !important');
                    $('.client_data > .company_name_label').text('Company Name:');
                    name = client['company_name'];
                } else {
                    $('.company_org_no').removeClass("d-flex");
                    $('.company_org_no').hide();
                    $('.client_data > .company_name_label').text('Name:');
                    name = client['name'];
                }
                $(".client_company_name").text(name);
                if (client['email'] != null) {
                    $(".client_email").text(client['email']);
                }
                let client_phone = '';
                if (client['phone_country'] != null) {
                    client_phone += client['phone_country'];
                }
                if (client['phone_number'] != null) {
                    client_phone += client['phone_number'];
                }
                $(".client_phone").text(client_phone);
            }

            let allow_clients = $("#allow_clients").is(':checked');
            if (!allow_clients) {
                toggle_clients_div (false);
            } else {
                toggle_clients_div (true);
            }
            $("#allow_clients").on("change", function (){
                if (!$(this).is(":checked")) {
                    toggle_clients_div (false);
                } else {
                    toggle_clients_div (true);
                }
            });

            let allow_economical_details = $("#allow_economical_details").is(':checked');
            if (!allow_economical_details) {
                toggle_economical_details (false);
            } else {
                toggle_economical_details (true);
            }
            $("#allow_economical_details").on("change", function (){
                if (!$(this).is(":checked")) {
                    toggle_economical_details (false);
                } else {
                    toggle_economical_details (true);
                }
            });

            function toggle_economical_details (checked = true) {
                if (!checked) {
                    $(".economical_details_div").hide();
                    $(".economical_details_div input").each(function(i, obj) {
                        $(this).val(null);
                    });
                } else {
                    $(".economical_details_div").show();
                }
            }

            function toggle_clients_div (checked = true) {
                if (!checked) {
                    $(".clients-div").hide();
                    $(".client_company_name").text('');
                    $(".client_company_org_no").text('');
                    $(".client_email").text('');
                    $(".client_phone").text('');
                    $('.clients-dropdown').val(null);
                } else {
                    $(".clients-div").show();
                }
            }

            $("#project_status").on("change", function(){
                let confim_msg = '';
                if ($(this).is(":checked")) {
                    $("#project_status").val('completed');
                    confim_msg = "Are you sure to complete this project?";
                } else {
                    $("#project_status").val('active');
                    confim_msg = "Are you sure to make this project active again?";
                }
                if (confirm(confim_msg)) {
                    let status_url = '';
                    let project_id = '<?php echo isset($project->id) ? $project->id : "" ?>';
                    status_url = "/projects/"+project_id+"/update_status";
                    $.ajax({
                        type: "POST",
                        url: status_url,
                        data: {
                            "status": $("#project_status").val(),
                            "_token": "<?php echo csrf_token() ?>"
                        }
                    }).done(function( result ) {
                        //if (result.success == true) {
                        //alert(result.message);
                        location.reload();
                        //}
                    });
                }
            });

            $("#create_client").on('click', function(e){

                e.preventDefault();

                $.ajax({
                    type: 'post',
                    url: $(".add_edit_clientform").attr("action"),
                    data: $(".add_edit_clientform").serialize(),
                    success: function (result) {
                        populateClients(result.clients, result.client_id);
                        $("#client-add-success").show();
                        $("#client-add-fail").hide();
                        $("#client-add-success").html('Client is created successfully.');
                        setTimeout(function(){
                            $(".btn-close").trigger("click");
                        }, 500);

                    },
                    error: function (jqXHR, exception) {
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
                        $("#client-add-fail").html(msg);
                        $("#client-add-success").hide();
                        $("#client-add-fail").show();
                    },
                });
            });

            function populateClients (clients, client_id) {
                var str_html = '<option value="">-- Select Client --</option>';
                $.each(clients, function( i, client ) {
                    if (client['id'] == client_id) {
                        populateClient(client);
                    }
                    str_html += '<option value="'+client['id']+'">'+ client['name'] +'</option>';
                });

                $(".clients-dropdown").html(str_html);
                $(".clients-dropdown").val(client_id);
                $(".clients-dropdown").select2();
            }
            $("#worker_all").on("change", function(){
                if($(this).is(":checked")) {
                    $(".worker-check-input").each(function(i, obj) {
                        $(this).prop('checked', true);
                    });
                } else {
                    $(".worker-check-input").each(function(i, obj) {
                        $(this).prop('checked', false);
                    });
                }
            });


//            $(".shift_start, .shift_end, .break_time").on('change', function(){
//                let shift_start = $(".shift_start").val();
//                let shift_end = $(".shift_end").val();
//                let break_time = Number($(".break_time").val());
//
//                let worked_duration = '';
//                let day_duration = '';
//
//                if( shift_start != '' &&  shift_end != '' && shift_end > shift_start) {
//                    worked_duration = calculateWorkDuration (shift_start, shift_end, break_time);
//                    day_duration = calculateWorkDuration (shift_start, shift_end);
//                }
//                $(".length_of_day").text(day_duration);
//                $(".working_time").text(worked_duration);
//            });

            $("#start_hours, #start_mins, #end_hours, #end_mins, .break_time").on('change keyup', function(){

                let shh = $("#start_hours").val();
                let smm = $("#start_mins").val();
                let ehh = $("#end_hours").val();
                let emm = $("#end_mins").val();
                shh = (shh != '') ? shh.padStart(2, '0') : '';// Add leading zero in case of single digit value.
                smm = (smm != '') ? smm.padStart(2, '0') : '';
                ehh = (ehh != '') ? ehh.padStart(2, '0') : '';
                emm = (emm != '') ? emm.padStart(2, '0') : '';

                $("#shift_start").val([shh, smm].join(':'));
                $("#shift_end").val([ehh, emm].join(':'));
                if ((shh != '' && smm != '') && (ehh != '' && emm != '')) {
                    let shift_start = $("#shift_start").val();
                    let shift_end = $("#shift_end").val();
                    let break_time = Number($(".break_time").val());

                    let worked_duration = '';
                    let day_duration = '';

                    if( shift_start != '' &&  shift_end != '' && shift_end > shift_start) {
                        worked_duration = calculateWorkDuration (shift_start, shift_end, break_time);
                        day_duration = calculateWorkDuration (shift_start, shift_end);
                    }
                    $(".length_of_day").text(day_duration);
                    $(".working_time").text(worked_duration);
                } else {
                    $(".length_of_day").text('');
                    $(".working_time").text('');
                }
            });

            function calculateWorkDuration (start_time, end_time, break_time = 0)
            {
                let startArr = start_time.split(':');
                let finishArr = end_time.split(':');
                let startMins = (Number(startArr[0])*60) + Number(startArr[1]);
                let finishMins = (Number(finishArr[0])*60) + Number(finishArr[1]);
                let diffMins = finishMins - startMins - break_time;

                let worked_hours = Math.floor(diffMins / 60); // hours
                let worked_mins = diffMins - (worked_hours*60); // mins

                worked_hours = ('0' + worked_hours).slice(-2);//Display 0 on extreme left in case of single char
                worked_mins = ('0' + worked_mins).slice(-2);//Display 0 on extreme left in case of single char
                return worked_hours+':'+worked_mins;
            }

        });



        function toggleShowQuickAdd(checked){
            if(checked){
                $('#div-project-detail-a').hide();
                $('#div-project-detail-b').hide();
                $('#div-project-detail-c').hide();
                $('#div-add-workers').hide();
                $('#div-client-details').hide();
                $('#div-project-time').hide();
            }else{
                $('#div-project-detail-a').show();
                $('#div-project-detail-b').show();
                $('#div-project-detail-c').show();
                $('#div-add-workers').show();
                $('#div-client-details').show();
                $('#div-project-time').show();
            }
        }

        $('#show_quick_add_project').change(function(){
            var checked = $(this).is(':checked');
            toggleShowQuickAdd(checked)
        })
    </script>
@endpush
