<style>
    .hours-form input[type=number] {
        max-width: 30%;
        margin-right: 10px;
    }
    .hours-form label {
        margin-top: 30px;
    }
    .hours-table tbody {
        display: block;
        padding: 30px;
    }
    .form-control {
        display: inline;
    }
    .radio-toolbar input[type="radio"] {
        opacity: 0;
        position: fixed;
        width: 0;
    }
    .radio-toolbar label {
        display: inline-block;
        background-color: #ddd;
        padding: 10px 20px;
        font-family: sans-serif, Arial;
        font-size: 16px;
        border: 2px solid #444;
        border-radius: 4px;
    }
    .radio-toolbar input[type="radio"]:checked + label {
        background-color:#bfb;
        border-color: #4c4;
    }
    .radio-toolbar input[type="radio"]:focus + label {
        border: 2px dashed #444;
    }
    .radio-toolbar label:hover {
        background-color: #dfd;
    }
</style>
<td>
    <table class="table table-stripped table-hover">
        <tr>
            <th>Project name</th>
            <th>Work day</th>
            <th>Start time</th>
            <th>End time</th>
            <th>Lunch break</th>
            <th>Comments</th>
        </tr>
        @foreach($hours as $hour)
            <tr>
                <th>{{ $hour->project->name }}</th>
                <th>{{ $hour->work_day }}</th>
                <th>{{ $hour->start_time }}</th>
                <th>{{ $hour->end_time }}</th>
                <th>{{ $hour->lunch_break ? $hour->break_time: '--' }}</th>
                <th>{{ $hour->comments ?? '--'  }}</th>
            </tr>
        @endforeach
    </table>
    <table class="hours-table">
        <tr>
            <td><button type="button" id="open_form" class="btn btn-secondary">+ Add a work day</button></td>
        </tr>
        <tr id="hours_form" style="display: none;">
            <td>
                <div class="col-12">
                    <form action="{{ route('hours.store') }}" method="POST" class="hours-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}" />
                        <div class="row">
                            <div class="col-md-4">
                                <label for="date">Select Date</label><br>
                                <input type="date" name="work_day" value="{{ date('Y-m-d', strtotime($project->start_date)) }}" min="{{ date('Y-m-d', strtotime($project->start_date)) }}" max="{{ date('Y-m-d') }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="start_time">Start Working at</label><br>
                                <input type="time" name="start_time" id="start_time" class="form-control work_duration" value="{{ date('H:i', strtotime($project->shift_start)) }}" />
                                <!-- Hours: <input type="number" name="start_hours" id="start_hours" class="form-control work_duration" value="{{ date('h', strtotime($project->shift_start)) }}" />
                                Minutes: <input type="number" name="start_minutes" id="start_minutes" class="form-control work_duration" value="{{ date('i', strtotime($project->shift_start)) }}" /> -->
                            </div>
                            <div class="col-md-4">
                                <label for="end_time">Finish Working at</label><br>
                                <input type="time" name="end_time" id="end_time" class="form-control work_duration" value="{{ date('H:i', strtotime($project->shift_end)) }}" />
                                <!-- Hours: <input type="number" name="hours" class="form-control work_duration" value="{{ date('h', strtotime($project->shift_end)) }}">
                                Minutes: <input type="number" name="minutes" class="form-control work_duration" value="{{ date('i', strtotime($project->shift_end)) }}"> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="date">Lunch Break?</label><br>
                                <!-- <div class="radio-toolbar">
                                    <label>Yes</label><input type="radio" class="btn btn-secondary break-toggle lunch_break" name="lunch_break" value="Yes">
                                    <label>No</label><input type="radio" class="btn btn-secondary break-toggle lunch_break" name="lunch_break" value="No">
                                </div> -->
                                <label>Yes</label><input type="radio" class="btn btn-secondary break-toggle lunch_break" name="lunch_break" value="1">
                                <label>No</label><input type="radio" class="btn btn-secondary break-toggle lunch_break" name="lunch_break" value="0" checked>
                            </div>
                            <div class="col-md-6 break-mins" style="display: none;">
                                <label for="">Lunch Break time in minutes</label><br>
                                Hours: <input type="number" name="break_time" id="lunch_break" class="form-control work_duration" max="{{ $project->break_time }}" value="{{ $project->break_time }}"/>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-md-6">
                                <label for="date">Photos</label><br>
                                <!-- <div class="radio-toolbar">
                                    <label>Yes</label><input type="radio" class="btn btn-secondary break-toggle lunch_break" name="lunch_break" value="Yes">
                                    <label>No</label><input type="radio" class="btn btn-secondary break-toggle lunch_break" name="lunch_break" value="No">
                                </div> -->
                                @if($project->allow_photos == 0)
                                    <label>Yes</label><input type="radio" class="btn btn-secondary break-toggle upload_photos" name="photos" value="1">
                                    <label>No</label><input type="radio" class="btn btn-secondary break-toggle upload_photos" name="photos" value="0" checked>
                                @endif
                            </div>
                            <div class="col-md-6 upload-images" style="display: @if($project->allow_photos == 0) none @else block @endif;">
                                <label for="">Upload photos</label><br>
                                <input type="file" class="form-control" name="images[]" accept="image/jpeg,image/png" multiple />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="date">Comment</label><br>
                                <!-- <div class="radio-toolbar">
                                    <label>Yes</label><input type="radio" class="btn btn-secondary break-toggle lunch_break" name="lunch_break" value="Yes">
                                    <label>No</label><input type="radio" class="btn btn-secondary break-toggle lunch_break" name="lunch_break" value="No">
                                </div> -->
                                @if($project->allow_comments == 0)
                                    <label>Yes</label><input type="radio" class="btn btn-secondary break-toggle add_comments" name="add_comments" value="1">
                                    <label>No</label><input type="radio" class="btn btn-secondary break-toggle add_comments" name="add_comments" value="0" checked>
                                @endif
                            </div>
                            <div class="col-md-6 add-comments" style="display: @if($project->allow_comments == 0) none @else block @endif;">
                                <label for="">Comment</label><br>
                                <textarea name="comments" rows="4" cols="50"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <h6>Project name: <b>{{ $project->name }}</b></h6>
                            <h6>Work duration: <b><span id="work_duration">{{ $project->shift_start }} - {{ $project->shift_end }}</span></b></h6>
                            <h6>Working time: <b><span id="working_time"></span></b></h6>
                            <h6 id="show_break_time" style="display: none;">Break time: <b><span id="break_time">{{ $project->break_time }}</span></b></h6>
                        </div>
                        <div class="row">
                            <input type="submit" name="store_hours" value="Add a work day" />
                        </div>
                    </form>
                </div>
            </td>
        </tr>
    </table>
</td>