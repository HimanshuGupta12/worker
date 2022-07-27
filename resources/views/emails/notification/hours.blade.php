@component('mail::message')
- The hours data has been modified by worker.


#    Old hours entry

<?php
    $ddate = $old_hour->work_day;
    $date = new DateTime($ddate);
    $old_week = $date->format("W");
    
    $ddate = $new_hour->work_day;
    $date = new DateTime($ddate);
    $new_week = $date->format("W");
?>
<div class="table-responsive">
<table id="datatable" class="table table-nowrap mb-50 align-middle  w-100">
<thead class=" hr_table">
<tr>
<th>Date</th>
<th>Worker</th>
<th>Work Hours</th>
<th>Break</th>
<th>Project / Comments</th>
</tr>
</thead>
<tbody>
<tr>
<td><p class="rep_heading">{{date("d-m-Y", strtotime($old_hour->work_day))}} </p><span class="line_down">{{date("l", strtotime($old_hour->work_day))}} - ({{ $old_week }})</span></td>
<td><p class="rep_heading">{{$old_hour['worker']->first_name}} {{$old_hour['worker']->last_name}} </p><span class="line_down">{{$old_hour['worker']->worker_position}}</span></td>
<td><p class="rep_heading">{{ substr($old_hour->start_time, 0, -3)}} to {{ substr($old_hour->end_time, 0, -3)}}</p><span class="line_down">Total: {{$old_hour->working_hours}}h</span></td>
<td>@if($old_hour->break_time == '0')<p class="rep_heading">No</p>@else<p class="rep_heading">Yes</p><span class="line_down">{{ $old_hour->break_time}} Min</span>@endif</td>
<td><p class="rep_heading">{{$old_hour['project']->nameAndNumber()}}</p><span class="line_down">@if(strlen($old_hour->comments) > 30){{substr($old_hour->comments,0,30)}} <a data-bs-target=".edit-comment-modal" data-id="{{$old_hour->id}}" data-comments="{{$old_hour->comments}}" class="edit-hour-comment" data-bs-toggle="modal"> ...more</a>@else{{$old_hour->comments}}@endif</span></td>
</tr>
</tbody>
</table>
</div>

<br/>
<br/>
<br/>


#    New hours entry

<div class="table-responsive">

<table id="datatable" class="table table-nowrap mb-50 align-middle  w-100">
<thead class=" hr_table">
<tr>
<th>Date</th>
<th>Worker</th>
<th>Work Hours</th>
<th>Break</th>
<th>Project / Comments</th>
</tr>
</thead>
<tbody>
<tr>
<td><p class="rep_heading">{{date("d-m-Y", strtotime($new_hour->work_day))}} </p><span class="line_down">{{date("l", strtotime($new_hour->work_day))}} - ({{ $new_week }})</span></td>
<td><p class="rep_heading">{{$new_hour['worker']->first_name}} {{$new_hour['worker']->last_name}}</p><span class="line_down">{{$new_hour['worker']->worker_position}}</span></td>
<td><p class="rep_heading">{{ substr($new_hour->start_time, 0, -3)}} to {{ substr($new_hour->end_time, 0, -3)}}</p><span class="line_down">Total: {{$new_hour->working_hours}}h</span></td>
<td>@if($new_hour->break_time == '0')<p class="rep_heading">No</p>@else<p class="rep_heading">Yes</p><span class="line_down">{{ $new_hour->break_time}} Min</span>@endif</td>
<td><p class="rep_heading">{{$new_hour['project']->nameAndNumber()}}</p><span class="line_down">@if(strlen($new_hour->comments) > 30){{substr($new_hour->comments,0,30)}} <a data-bs-target=".edit-comment-modal" data-id="{{$new_hour->id}}" data-comments="{{$new_hour->comments}}" class="edit-hour-comment" data-bs-toggle="modal"> ...more</a>@else{{$new_hour->comments}}@endif</span></td>
</tr>
</tbody>
</table>
</div>

<br/>
<br/>

Thanks,<br>
{{ config('app.name') }}
@endcomponent