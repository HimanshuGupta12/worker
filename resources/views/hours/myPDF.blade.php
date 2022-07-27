<?php
$totalHour = $stats['worker']['total_hours'];
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
table {
  font-family: arial, sans-serif;
  width: 100%;
}
td, th {
  text-align: left;
  padding: 8px;
  font-size: 12px;
}
tr:nth-child(even) {
  background-color: #E1E3F9;
}
.company {
  float:right;
  margin-right:50px;
  margin-top:-4px;
  font-family: 'Roboto' , 'sans-serif';
  font-size:14px;
   }
</style>
<div>
        @if ($company->logo)
            <img src="{{ Storage::url($company->logo) }}" style="width: 100px;" />
        @else
            <img src="{{ public_path('pdf-logo.png') }}" style="width: 100px;"/>
        @endif

        <div class="">
          @if(empty($hide_contractor))
            <div class="company">
              <h4 class="report_card_title mb-0">Contractor:</h4>
              <div style="margin-top:-20px">
              <p style="margin-top:0px;margin-bottom:0px;">{{$company->name}}</p>
              <p style="margin-top:0px;margin-bottom:0px;">+{{$user->phone_country}} {{$user->phone_no}}</p>
              <p style="margin-top:0px;margin-bottom:0px;">{{$company->company_registration_number}}</p>
              <p style="margin-top:0px;margin-bottom:0px; width:200px;">{{$company->company_address}}</p>
              </div>
            </div>
          @endif
            <h4 class="report_card_title mb-0" style="font-size:14px; font-family: 'Roboto' , 'sans-serif';">Hours report</h4>
            <div style="margin-top:-20px">
            @if($worker)
            <p class="rep_heading mb-0"style="margin-top:0px;margin-bottom:0px;font-size:14px;font-family: 'Roboto' , 'sans-serif';">Worker name: <strong>{{$worker->first_name}} {{$worker->last_name}}</strong></p>
            @endif
            @if($project)
            <p class="rep_heading mb-0"style="margin-top:0px;margin-bottom:0px;font-size:14px;font-family: 'Roboto' , 'sans-serif';">Project name: <strong>{{$project->name}}</strong></p>
            @endif
            <p class="rep_heading mb-0"style="margin-top:0px;margin-bottom:0px;font-size:14px;font-family: 'Roboto' , 'sans-serif';">Period: <strong>{{$minDate}} to {{$maxDate}}</strong></p>
            <p class="rep_heading mb-0 cstn_bold"style="margin-top:0px;margin-bottom:0px;font-size:14px;font-family: 'Roboto' , 'sans-serif';">Total entries: <strong>{{$totalentries}}</strong></p>
            <p class="rep_heading mb-0 cstn_bold"style="margin-top:0px;margin-bottom:0px;font-size:14px;font-family: 'Roboto' , 'sans-serif';">Total unique days: <strong>{{$totaluniquedays}}</strong></p>
            <p class="rep_heading mb-0 cstn_bold"style="margin-top:0px;font-size:14px;font-family: 'Roboto' , 'sans-serif';">Total hours: <strong>{{$totalHour}}h</strong></p>
          </div>
        <table id="datatable" class="table table-nowrap">
            <thead class=" hr_table">
            <tr>
                <th style="width:100px;">Date</th>
                <th style="width:170px;">Worker</th>
                <th style="width:80px;">Work Hours</th>
                @if(empty($hide_breaks))
                  <th style="width:40px;">Break</th>
                @endif
                <th>{{ (empty($hide_comments)) ? 'Project/Comments' : 'Project' }}</th>
                @if(empty($hide_pictures))
                  <th></th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($hours as $i => $hour)
            <?php
              $ddate = $hour->work_day;
              $date = new DateTime($ddate);
              $week = $date->format("W");
              $hour_id = \App\Models\Hour::encodeHourId($hour->id);
            ?>
            <tr>
                <td><p style="margin-top:0px;margin-bottom: 0px;">{{date("d-m-Y", strtotime($hour->work_day))}} </p><span style="margin-bottom: 0px;">{{date("l", strtotime($hour->work_day))}} - ({{ $week }})</span></td>
                <td>
                  <p style="margin-top:0px;margin-bottom: 0px;">{{$hour['worker']->first_name}} {{$hour['worker']->last_name}} </p>
                  @if(empty($hide_worker_position))
                      <span style="color:grey"> {{$hour['worker']->allPositions->pluck('name')->join(', ', ', and ')}} </span>
                  @endif
                </td>
                <td><p style="margin-top:0px;margin-bottom:0px;">{{ substr($hour->start_time, 0, -3)}} to {{ substr($hour->end_time, 0, -3)}}</p><span style="color:grey">Total: {{$hour->working_hours}}h</span></td>
                @if(empty($hide_breaks))
                  <td>@if($hour->break_time == '0')
                      <p>No</p>
                      @else
                      <p style="margin-top:0px;margin-bottom:0px;">Yes</p><span style="color:grey">{{ $hour->break_time}} Min</span>
                      @endif
                  </td>
                @endif
                <td>
                  <p style="margin-top:0px;margin-bottom:0px;"><strong>Project name: {{$hour['project']->nameAndNumber()}}</strong></p>
                  @if(empty($hide_comments))
                    <span>{{ $hour->comments }}</span>
                  @endif
                </td>
                @if(empty($hide_pictures) && $hour->images)
                  <td>
                  <a style="visibility:hidden;">IMG</a>
                  <a href="{{route('pdfimage', ['hashed_hour_id' => $hour_id])}}" target="_blank" id="105" >
                  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAASlJREFUSEvVlcttAjEURQ8NkGxYh3SQDoAOSAdBFJBPA4QGgAYQSBQQqADoICUk+yySLNkQ3ciWLOOxLZlZ4JU19rvn/fymQc2rUbM+OYBrYAvcec68Az3gO+ZkClAlbjWTkBggJZ4FcQFdYAG0C+vyAQyAnXRcgA5uCsWtubRufcAxIr4HZsDa3OkDT0AnYvPvvBtBFWAMvFYI6fuo4iwLIM9VG60JMDT7OfBi9sp1KJIswL1Ji8SfPU+nBqJ0vQWiyALYFP4ATU/kF7gy30LpLQZ8Aa1SQO0pUgE1b9wiH4DVuYosYbWiWjW09DYeS9rU2ioSiW2chyZh28IhRlaRIw81eXQC0Fy3bZe0Tlz4tEPTn6bLMww8iT+Epmmp10H71B+tGHr5gD9nTT8ZfPn3aQAAAABJRU5ErkJggg=="/ style="width:25px;padding-left:20px;">
                    </a>
                  </td>
                @endif
              </tr>
            @endforeach
          </tbody>
        </table>
<!------------------------------------>
