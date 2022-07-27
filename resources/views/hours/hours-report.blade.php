@extends('layouts.user')
@section('head')
<meta name="_token" content="{{ csrf_token() }}">
    @parent

    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">


    <link href="{{ env('PUBLIC_PATH') }}/css/worker-reports.css" rel="stylesheet" type="text/css" />
    <link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
    <style type="text/css">

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

   .select2{
    width: 100% !important;
   }

  /* .bottom-border-none {
    border-bottom: hidden;
   }*/

   .grid-of-images a {
	margin: 0 1px 1px 0;
   }
   .btn-info-show:hover
   {
       color:#fff;
   }
@media only screen and (min-width:280px) and (max-width:768px)
{
    .report_forms
    {
        position:relative;
    }
    .views-setting
    {
        position:relative;
        left:-109px;
    }
    .export
    {
        position:absolute;
        right:0;
        top:7px;
    }
    .view-export
    {
        margin-top:15px;
    }
    .hour_min_selector : {
        display: inline-block !important;
        width: 40px !important;
    }

}
  </style>

@endsection

@section('content')

<!--------------- model start ------------->

    <div class="modal fade create-image-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myExtraLargeModalLabel">Edit Image</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="image_edit">
                </div>
            </div>
        </div>
    </div>
    <!-- ---------edit ---------- -->

    <div class="modal fade create-hour-modal" role="dialog" aria-labelledby="myExtraLargeModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myExtraLargeModalLabel2">Edit Hours</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id='hour_edit'>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade edit-comment-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <form id="hour_comments_form" action="{{url('hours.comments')}}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <span class="modal-title" id="myExtraLargeModalLabel2">Comments</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea id="hour_comments" name="comments" rows="3" style="width: 100%"></textarea>
                            </div>
                        </div>
                        <input type="hidden" id="hour_id" name="id" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="saveComments" class="btn btn-primary waves-effect waves-light" data-bs-dismiss="modal">Update comments</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

	<!-- Export PDF -->
	<div class="modal fade" id="exportPDFModal" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<span class="modal-title" id="myExtraLargeModalLabel2">Export PDF</span>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<form id="exportPDFForm">
							<div class="row">
								<div class="col-md-12">
									<div class="card">
										<div class="card-body">
											<div class="row">
												<div class="col-md-6">
													<div class="form-check mb-3">
														<input class="form-check-input" type="checkbox" id="pdf_hide_worker_position" name="hide_worker_position" value="1" {{ (!empty($pdf_settings['hide_worker_position'])) ? 'checked' : '' }}>
														<label class="form-check-label" for="pdf_hide_worker_position">Hide worker position</label>
													</div>
													<div class="form-check mb-3">
														<input class="form-check-input" type="checkbox" id="pdf_hide_contractor" name="hide_contractor" value="1" {{ (!empty($pdf_settings['hide_contractor'])) ? 'checked' : '' }}>
														<label class="form-check-label" for="pdf_hide_contractor">Hide contractor</label>
													</div>
													<div class="form-check mb-3">
														<input class="form-check-input" type="checkbox" id="pdf_hide_breaks" name="hide_breaks" value="1" {{ (!empty($pdf_settings['hide_breaks'])) ? 'checked' : '' }}>
														<label class="form-check-label" for="pdf_hide_breaks">Hide breaks</label>
													</div>
													<div class="form-check mb-3">
														<input class="form-check-input" type="checkbox" id="pdf_hide_comments" name="hide_comments" value="1" {{ (!empty($pdf_settings['hide_comments'])) ? 'checked' : '' }}>
														<label class="form-check-label" for="pdf_hide_comments">Hide comments</label>
													</div>
													<div class="form-check mb-3">
														<input class="form-check-input" type="checkbox" id="pdf_hide_pictures" name="hide_pictures" value="1" {{ (!empty($pdf_settings['hide_pictures'])) ? 'checked' : '' }}>
														<label class="form-check-label" for="pdf_hide_pictures">Hide pictures</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<button type="submit" class="btn btn-info w-md">Generate PDF</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Export PDF -->

    @include('partial.view_settings_modal')
<!----------- model end ----------------->


<div class="container-fluid">
  <!-- Filters -->
  <div class="row">
     <div class="col-lg-12">
        <div class="card">
           <div class="card-body">
            <form class="report_forms">
              <div class="row">
                    <div class="col-md-1">
                      <div class=" sort_icon">
                        <h5 class="report_card_title  mb-0"><i class="bx bx-slider-alt"></i>Sort</h5>
                      </div>
                    </div>
                    <div class="col-md-11">
                      <div class="row mobile_spacing_row" >
                        <div class="col-lg-2 col-md-4">
                        <select class="form-select" name="project">
                           <option> <i class="bx bx-slider-alt">Select project</option>
                           @foreach($projects as $projRow)
                            <option value="{{$projRow->id}}" @if (isset($project) && $project->id == $projRow->id ) selected @endif>{{ $projRow->nameAndNumber() }}</option>
                            @endforeach
                          </select>
                            <svg class="field_icon" width="18" height="18" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M11.53 21.0003H3.11C1.95 21.0003 1 20.0703 1 18.9303V4.09035C1 1.47035 2.95 0.280349 5.34 1.45035L9.78 3.63035C10.74 4.10035 11.53 5.35035 11.53 6.41035V21.0003Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M20.9998 14.0604V17.8404C20.9998 20.0004 19.9998 21.0004 17.8398 21.0004H11.5298V9.42041L11.9998 9.52041L16.4998 10.5304L18.5298 10.9804C19.8498 11.2704 20.9298 11.9504 20.9898 13.8704C20.9998 13.9304 20.9998 13.9904 20.9998 14.0604Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.53027 8.00024H8.00027" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.53027 12.0002H8.00027" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M16.5 10.5303V13.7503C16.5 14.9903 15.49 16.0003 14.25 16.0003C13.01 16.0003 12 14.9903 12 13.7503V9.52026L16.5 10.5303Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M20.99 13.8703C20.93 15.0503 19.95 16.0003 18.75 16.0003C17.51 16.0003 16.5 14.9903 16.5 13.7503V10.5303L18.53 10.9803C19.85 11.2703 20.93 11.9503 20.99 13.8703Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <select class="form-select" name="worker">
                               <option>Select worker</option>
                               @foreach($workers as $workerRow)
                               <option value="{{$workerRow->id}}" @if (isset($worker) && $worker->id == $workerRow->id ) selected @endif >{{ $workerRow->fullname() }}</option>
                               @endforeach
                            </select>
                            <svg class="field_icon"  width="17" height="18" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M9.58984 11C12.3513 11 14.5898 8.76142 14.5898 6C14.5898 3.23858 12.3513 1 9.58984 1C6.82842 1 4.58984 3.23858 4.58984 6C4.58984 8.76142 6.82842 11 9.58984 11Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M16.7999 14.74L13.2599 18.2801C13.1199 18.4201 12.9899 18.68 12.9599 18.87L12.7699 20.22C12.6999 20.71 13.0399 21.05 13.5299 20.98L14.8799 20.79C15.0699 20.76 15.3399 20.63 15.4699 20.49L19.0099 16.95C19.6199 16.34 19.9099 15.63 19.0099 14.73C18.1199 13.84 17.4099 14.13 16.7999 14.74Z" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M16.29 15.25C16.59 16.33 17.43 17.17 18.51 17.47" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1 21C1 17.13 4.85003 14 9.59003 14C10.63 14 11.63 14.15 12.56 14.43" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="col-lg-2 col-md-4">
                          <select class="form-select" id="autoSizingSelect" name="date" onchange="status('dates', this)">
                            <option selected="">Select date</option>
                            <option value="Last week" @if (isset($date) && $date == "Last week" ) selected @endif >Last week</option>
                            <option value="This week" @if (isset($date) && $date == "This week" ) selected @endif >This week</option>
                            <option value="Last and this week" @if (isset($date) && $date == "Last and this week" ) selected @endif >Last and this week</option>
                            <option value="Previous two weeks" @if (isset($date) && $date == "Previous two weeks" ) selected @endif >Previous two weeks</option>
                            <option value="Last month" @if (isset($date) && $date == "Last month" ) selected @endif >Last month</option>
                            <option value="This month" @if (isset($date) && $date == "This month" ) selected @endif >This month</option>
                            <option value="Custom" @if (isset($date) && $date == "Custom" ) selected @endif >Custom</option>
                          </select>
                            <svg class="field_icon"  width="16" height="18" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M6 1V4" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M14 1V4" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M19 7.5V16C19 19 17.5 21 14 21H6C2.5 21 1 19 1 16V7.5C1 4.5 2.5 2.5 6 2.5H14C17.5 2.5 19 4.5 19 7.5Z" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6 10H14" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6 15H10" stroke="#667685" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="col-lg-2 col-md-4" id="dates" style='display:{{ (isset($date) && $date == "Custom" ) ? 'block' : 'none' }}'>
                          <div>
                              <input type="date" name='start_date' value="{{$start_date}}" class="form-control">
                          </div>
                          <div>
                            <input type="date" name='end_date' value="{{$end_date}}" class="form-control">
                          </div>
                        </div>

                        <div class="col-lg-2 col-md-4">
                          <select class="form-select-invoice" id="autoSizingSelect" name="stamp_invoice">
                              <option></option>
                              <option value="all" @if (isset($stamp_invoice) && $stamp_invoice == "all" ) selected @endif >All</option>
                              <option value="1" @if (isset($stamp_invoice) && $stamp_invoice == "1" ) selected @endif >Invoiced</option>
                              <option value="0" @if (isset($stamp_invoice) && $stamp_invoice == "0" ) selected @endif >Not invoiced</option>
                          </select>
                          <svg  class="field_icon" width="18" height="18" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 9V14C21 19 19 21 14 21H8C3 21 1 19 1 14V8C1 3 3 1 8 1H13" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 9H17C14 9 13 8 13 5V1L21 9Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 12H12" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 16H10" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                      </div>
                         <div class="col-lg-4 col-md-5">
                            <button type="submit" class="btn btn-info-show w-md">Show</button>
                            <button type="button" class="btn custom_rest_btn">
                              <a href="{{ url()->current() }}" class="">Reset</a>
                            </button>
                          </div>
                        </div>
                    </div>
                </div>
              </form>
           </div>
        </div>
     </div>
  </div>
<!-- Filters End-->

<!-- Report summry Worker -->
    @if($worker != null)
    <div class="row" id="worker_details">
        <div class="col-md-8 col-lg-8">
          <div class="card card-body card_summry">
              <h4 class="report_card_title mb-3">Worker details</h4>
                <div class="row">
                    <div class="col-md-6">
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                              <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                <svg width="16" height="18" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M6.15381 7C7.81066 7 9.15381 5.65685 9.15381 4C9.15381 2.34315 7.81066 1 6.15381 1C4.49695 1 3.15381 2.34315 3.15381 4C3.15381 5.65685 4.49695 7 6.15381 7Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M11.308 13C11.308 10.678 8.998 8.80005 6.154 8.80005C3.31 8.80005 1 10.678 1 13" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                              </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">{{ $worker->first_name}} {{ $worker->last_name}}</h5>
                        </div>

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                <svg width="16" height="18" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M4.64307 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.78564 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M1.75 6.12939H12.6786" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M13.0001 5.75017V11.2145C13.0001 13.143 12.0359 14.4287 9.78585 14.4287H4.643C2.393 14.4287 1.42871 13.143 1.42871 11.2145V5.75017C1.42871 3.8216 2.393 2.53589 4.643 2.53589H9.78585C12.0359 2.53589 13.0001 3.8216 13.0001 5.75017Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 9.09291H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 11.0214H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 9.09291H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 11.0214H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 9.09291H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 11.0216H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                            </span>
                            </div>
                             @if($stats['worker']['min_date'] && $stats['worker']['max_date'])
                            <h5 class="rep_heading mb-0">{{date("d-m-Y", strtotime($stats['worker']['min_date']))}} to {{date("d-m-Y", strtotime($stats['worker']['max_date']))}}</h5>
                            @else
                            <h5 class="rep_heading mb-0">- to -</h5>
                            @endif
                         </div>
                    </div>

                    <div class="col-md-6">

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                            <svg width="18" height="18" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M1.25 14.7432H15.0202" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M10.2075 14.7362L1.94537 14.75L1.93848 4.46363C1.93848 4.00233 2.17258 3.57543 2.55126 3.32068L5.3053 1.48237C5.7666 1.17254 6.37247 1.17254 6.83378 1.48237L9.58782 3.32068C9.97338 3.57543 10.2006 4.00233 10.2006 4.46363L10.2075 14.7362Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M13.6294 14.7499V11.989" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M13.6436 7.85791C12.8863 7.85791 12.2666 8.47757 12.2666 9.23493V10.612C12.2666 11.3693 12.8863 11.989 13.6436 11.989C14.401 11.989 15.0206 11.3693 15.0206 10.612V9.23493C15.0206 8.47757 14.401 7.85791 13.6436 7.85791Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.93848 9.23511H10.2006" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.06934 14.743V12.1611" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.06939 6.8253C6.63977 6.8253 7.10215 6.36291 7.10215 5.79253C7.10215 5.22215 6.63977 4.75977 6.06939 4.75977C5.49901 4.75977 5.03662 5.22215 5.03662 5.79253C5.03662 6.36291 5.49901 6.8253 6.06939 6.8253Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">{{!empty($stats['project']['members']) ? count($stats['project']['members']) : 0}}<span class="rep_heading"> Projects</span></h5>
                        </div>

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="17" height="18" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M4 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.7998 1V2.8" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.2998 5.25391H11.4998" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.8 4.8999V9.9999C11.8 11.7999 10.9 12.9999 8.8 12.9999H4C1.9 12.9999 1 11.7999 1 9.9999V4.8999C1 3.0999 1.9 1.8999 4 1.8999H8.8C10.9 1.8999 11.8 3.0999 11.8 4.8999Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 8.01997H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 9.82002H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 8.01997H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 9.82002H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 8.01997H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 9.82002H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">{{!empty($stats['worker']['work_days']) ? count($stats['worker']['work_days']) : 0 }}<span class="rep_heading"> Days</span></h5>
                         </div>
                    </div>
                </div>
          </div>
        </div>

        <div class="col-md-4 col-lg-4" style="min-width: 314px;">
          <div class="card card-body card_hilght">
            <h4 class="report_card_title_white">Hours details</h4>
                <div class="row cstm_sprtr" >
                  <div class="col-md-6">
                    <h1 class="text-white hilited_heading">{{$workerSubmittedHours}}</h1>
                    <span class="text-white font-size-13">Total hours</span>
                  </div>
                  <div class="col-md-6">
                    <h1 class="text-white hilited_heading">{{isset($worker->worker_salary) ? salary_format($worker->worker_salary*$workerSubmittedHours) : 'N/A'}},-</h1>
                    <span class="text-white font-size-13">Salary</span>
                  </div>
                  </div>
                <img src="{{ env('PUBLIC_PATH') }}/img/trap-logo.png" alt="" height="40" class="bg_logo">
          </div>
        </div>
    </div>
    @endif
    <!-- Report summry Worker End  -->

    <!-- Report summry Project 2 -->
    @if($project != null)
    <div class="row" id="project_details">
        <div class="col-md-8 col-lg-8">
          <div class="card card-body card_summry">
              <h4 class="report_card_title mb-2">Project details</h4>
                <div class="row">

                    <div class="col-md-4">

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                            <svg width="18" height="18" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M1.25 14.7432H15.0202" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M10.2075 14.7362L1.94537 14.75L1.93848 4.46363C1.93848 4.00233 2.17258 3.57543 2.55126 3.32068L5.3053 1.48237C5.7666 1.17254 6.37247 1.17254 6.83378 1.48237L9.58782 3.32068C9.97338 3.57543 10.2006 4.00233 10.2006 4.46363L10.2075 14.7362Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M13.6294 14.7499V11.989" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M13.6436 7.85791C12.8863 7.85791 12.2666 8.47757 12.2666 9.23493V10.612C12.2666 11.3693 12.8863 11.989 13.6436 11.989C14.401 11.989 15.0206 11.3693 15.0206 10.612V9.23493C15.0206 8.47757 14.401 7.85791 13.6436 7.85791Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.93848 9.23511H10.2006" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.06934 14.743V12.1611" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.06939 6.8253C6.63977 6.8253 7.10215 6.36291 7.10215 5.79253C7.10215 5.22215 6.63977 4.75977 6.06939 4.75977C5.49901 4.75977 5.03662 5.22215 5.03662 5.79253C5.03662 6.36291 5.49901 6.8253 6.06939 6.8253Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">{{ $project->nameAndNumber() }}</h5>
                        </div>

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                 <svg width="16" height="18" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M4.64307 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.78564 1.57153V3.5001" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M1.75 6.12939H12.6786" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M13.0001 5.75017V11.2145C13.0001 13.143 12.0359 14.4287 9.78585 14.4287H4.643C2.393 14.4287 1.42871 13.143 1.42871 11.2145V5.75017C1.42871 3.8216 2.393 2.53589 4.643 2.53589H9.78585C12.0359 2.53589 13.0001 3.8216 13.0001 5.75017Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 9.09291H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M9.58938 11.0214H9.59516" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 9.09291H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.21145 11.0214H7.21723" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 9.09291H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M4.83255 11.0216H4.83832" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>
                            </span>
                            </div>
                             @if($stats['project']['min_date'] && $stats['project']['max_date'])
                            <h5 class="rep_heading mb-0">{{date("d-m-Y", strtotime($stats['project']['min_date']))}} to {{date("d-m-Y", strtotime($stats['project']['max_date']))}}</h5>
                            @else
                            <h5 class="rep_heading mb-0">- to -</h5>
                            @endif
                         </div>
                    </div>

                    <div class="col-md-4">

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="16" height="18" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M6.97301 8.92039C8.08101 8.92039 8.97923 8.02218 8.97923 6.91418C8.97923 5.80617 8.08101 4.90796 6.97301 4.90796C5.86501 4.90796 4.9668 5.80617 4.9668 6.91418C4.9668 8.02218 5.86501 8.92039 6.97301 8.92039Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.58481 5.74399C2.85155 0.175454 11.1015 0.181884 12.3618 5.75042C13.1013 9.01695 11.0693 11.7819 9.28816 13.4924C7.99569 14.7398 5.9509 14.7398 4.652 13.4924C2.87727 11.7819 0.845337 9.01052 1.58481 5.74399Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0">{{$project->address}}<span class="client_sub"></span></h5>
                        </div>

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                             <svg width="16" height="18" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M6.15381 7C7.81066 7 9.15381 5.65685 9.15381 4C9.15381 2.34315 7.81066 1 6.15381 1C4.49695 1 3.15381 2.34315 3.15381 4C3.15381 5.65685 4.49695 7 6.15381 7Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.308 13C11.308 10.678 8.998 8.80005 6.154 8.80005C3.31 8.80005 1 10.678 1 13" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">{{!empty($stats['worker']['members']) ? count($stats['worker']['members']) : 0 }}<span class="rep_heading mb-0"> Members</span></h5>
                         </div>
                    </div>

                    <div class="col-md-4">

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="17" height="18" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M4 0.999023V2.79902" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.7998 0.999023V2.79902" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1.2998 5.25293H11.4998" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.8 4.89893V9.99893C11.8 11.7989 10.9 12.9989 8.8 12.9989H4C1.9 12.9989 1 11.7989 1 9.99893V4.89893C1 3.09893 1.9 1.89893 4 1.89893H8.8C10.9 1.89893 11.8 3.09893 11.8 4.89893Z" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 8.01899H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M8.61702 9.81904H8.62241" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 8.01899H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M6.39729 9.81904H6.40268" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 8.01899H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M4.17659 9.81904H4.18198" stroke="#FFA113" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">{{!empty($stats['project']['work_days']) ? count($stats['project']['work_days']) : 0 }}<span class="rep_heading mb-0"> Days</span></h5>
                        </div>
                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                           <svg width="18" height="18" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 6.99902C13 10.311 10.312 12.999 7 12.999C3.688 12.999 1 10.311 1 6.99902C1 3.68702 3.688 0.999023 7 0.999023C10.312 0.999023 13 3.68702 13 6.99902Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.22583 8.90693L7.36583 7.79693C7.04183 7.60493 6.77783 7.14293 6.77783 6.76493V4.30493" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 cstn_bold">
                            @if($project->payment_type == 'fixed')
                               {{$project->fixed_rate}}<span class="rep_heading mb-0">,-{{$project->payment_type}}</span>
                            @endif
                            @if($project->payment_type == 'hourly')
                               {{$project->hourly_rate}}<span class="rep_heading mb-0">,-/h</span>
                            @endif
                            @if($project->payment_type == 'mixed')
                               {{$project->hourly_rate}} <span class="rep_heading mb-0">Hourly</span>, {{$project->fixed_rate}} <span class="rep_heading mb-0">Fixed</span><span class="rep_heading mb-0"></span>
                            @endif
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-4" style="min-width: 314px;">
          <div class="card card-body card_hilght">
            <div class="row ">

              <div class="col-md-6"><h4 class="report_card_title_white">Hours details</h4></div>
              <div class="col-md-6">
                  <div class="">
                      @if($project->total_hours > 0)
                      <?php
                      $progress = round(($projectSubmittedHoursInTotal/$project->total_hours)*100);
                      ?>
                      <div class="progress custom_progress">
                          <div class="progress-bar bg-success" role="progressbar" style="width: {{$progress}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                              <span style="text-align: right; color: red; font-weight: bolder; font-size: 11%">{{$progress}}%</span>
                          </div>
                      </div>
                      @endif
                  </div>
              </div>
              </div>
                <div class="row cstm_sprtr">
                  <div class="col-md-6">
                    <h1 class="text-white hilited_heading">{{$projectSubmittedHoursInDate}}</h1>
                    <span class="text-white font-size-13">Total hours</span>
                  </div>
                  <div class="col-md-6">
                    <h1 class="text-white hilited_heading">{{$projectSubmittedHoursInTotal}}</h1>
                    <span class="text-white font-size-13">Total project hours</span>
                  </div>
                  </div>
                <img src="{{ env('PUBLIC_PATH') }}/img/trap-logo.png" alt="" height="40" class="bg_logo">
          </div>
        </div>
    </div>
    @endif

    <!-- Report summry Project 2 End  -->

    <!-- ---------------------------------------- -->
    @if($project != null)

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                    @foreach($projectIndividualHoursByWorker as $row)
                    <div class="col-md-4">
                      <div class="d-flex align-items-center mb-2">
                        <div class="avatar-xs me-3 report_smry_iconsize">
                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                             <svg width="16" height="18" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M6.15381 7C7.81066 7 9.15381 5.65685 9.15381 4C9.15381 2.34315 7.81066 1 6.15381 1C4.49695 1 3.15381 2.34315 3.15381 4C3.15381 5.65685 4.49695 7 6.15381 7Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M11.308 13C11.308 10.678 8.998 8.80005 6.154 8.80005C3.31 8.80005 1 10.678 1 13" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            </span>
                        </div>
                        <h5 class="rep_heading mb-0">{{$row['worker_name']}}:</h5>&nbsp;<h5 class="rep_heading mb-0 cstn_bold">{{$row['sum']}}h</h5>
                      </div>
                    </div>
                    @endforeach
                  </div>
               </div>
            </div>
        </div>
    </div>
    @endif

    <!-- -------------------------------------------- -->
    @if($worker != null)

    <div class="row">
         <div class="col-lg-12">
            <div class="card">
               <div class="card-body">

                  <div class="row">
                    @foreach($workerIndividualHoursByProject as $row)
                    <div class="col-md-4">
                      <div class="d-flex align-items-center mb-2">
                        <div class="avatar-xs me-3 report_smry_iconsize">
                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                        <svg width="18" height="18" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M1.25 14.7432H15.0202" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M10.2075 14.7362L1.94537 14.75L1.93848 4.46363C1.93848 4.00233 2.17258 3.57543 2.55126 3.32068L5.3053 1.48237C5.7666 1.17254 6.37247 1.17254 6.83378 1.48237L9.58782 3.32068C9.97338 3.57543 10.2006 4.00233 10.2006 4.46363L10.2075 14.7362Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M13.6294 14.7499V11.989" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M13.6436 7.85791C12.8863 7.85791 12.2666 8.47757 12.2666 9.23493V10.612C12.2666 11.3693 12.8863 11.989 13.6436 11.989C14.401 11.989 15.0206 11.3693 15.0206 10.612V9.23493C15.0206 8.47757 14.401 7.85791 13.6436 7.85791Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M1.93848 9.23511H10.2006" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M6.06934 14.743V12.1611" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M6.06939 6.8253C6.63977 6.8253 7.10215 6.36291 7.10215 5.79253C7.10215 5.22215 6.63977 4.75977 6.06939 4.75977C5.49901 4.75977 5.03662 5.22215 5.03662 5.79253C5.03662 6.36291 5.49901 6.8253 6.06939 6.8253Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        </span>
                        </div>
                        <h5 class="rep_heading mb-0">{{$row['project_name']}}:</h5>&nbsp;<h5 class="rep_heading mb-0 cstn_bold">  {{$row['sum']}}h</h5>
                      </div>
                    </div>
                    @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
    @endif

    <!--  listing start -->
    <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <div class="row">
                  <div class="col-md-5 mt-0">
                          <form class="row gy-2 gx-3 report_forms">
                              <div class="col-sm-auto w-50">
                                  <select class="form-control select2-search-disable" id="report_action" name="stamp_invoice">
                                     <option value="">Select option</option>
                                     <option value="Not Invoiced">Mark as Not invoiced</option>
                                     <option value="Invoiced">Mark as invoiced</option>
                                     <option value="Deleted" style="color:red">Delete</option>
                                  </select>
                                  <svg  class="field_icon" width="18" height="18" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 9V14C21 19 19 21 14 21H8C3 21 1 19 1 14V8C1 3 3 1 8 1H13" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21 9H17C14 9 13 8 13 5V1L21 9Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6 12H12" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6 16H10" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>
                            </div>

                            <!-- <div class="col-sm-auto">
                                <a class="apply_action" style="color: #fff;" >
                                  <button type="button" class="btn  btn-info custom_rest_btn ">
                                     Apply
                                  </button>
                                </a>
                            </div> -->

                            <div class="col-sm-auto w-50">
                              <a class="apply_action">
                                  <button type="button" class="btn  custom_rest_btn" style="color: #2F45C5;">
                                  <i class=""></i> Apply
                                </button>
                              </a>
                          </div>
                          <!-- <h4 class="report_card_title mb-0">Report</h4> -->
                        </form>
                    </div>
                    <div class="col-md-7 mb-2 view-export">
                      <form class="row gy-2 gx-3 float-end report_forms">
                        <div class="col-sm-auto  views-setting">
                          <button type="button" class="btn custom_rest_btn" id="viewSettings">
                            <i class="bx bxs-cog font-size-16 align-middle me-2"></i> View settings
                          </button>
                        </div>
                        <!-- <div class="col-sm-auto">
                          <select class="form-control select2-search-disable" id="" name="settings">
                            <option value="">View settings</option>
                              <optgroup label="Report settings">
                                 <!-- <option value="Client Details">Client details</option>
                                 <option value="Comments By Worker">Comments by worker</option> --
                                 <option value="Worker Position">Worker position</option>
                                 <option value="Pictures">Pictures</option>
                              </optgroup>
                              <optgroup label="PDF settings">
                                  <option value="CT">1</option>
                                  <option value="DE">2</option>
                                  <option value="FL">3</option>
                              </optgroup>
                          </select>
                          <svg class="field_icon" width="18" height="17" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M10.5351 12.8604C12.1149 12.8604 13.3956 11.5797 13.3956 9.99991C13.3956 8.42009 12.1149 7.1394 10.5351 7.1394C8.95525 7.1394 7.67456 8.42009 7.67456 9.99991C7.67456 11.5797 8.95525 12.8604 10.5351 12.8604Z" stroke="#001B34" stroke-width="1.4" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1 10.8391V9.16092C1 8.16928 1.81048 7.34927 2.81165 7.34927C4.53749 7.34927 5.24308 6.12879 4.37539 4.63179C3.87957 3.77364 4.17516 2.65804 5.04284 2.16222L6.6924 1.21826C7.44567 0.770112 8.41824 1.03709 8.86638 1.79036L8.97127 1.97152C9.82942 3.46852 11.2406 3.46852 12.1083 1.97152L12.2132 1.79036C12.6613 1.03709 13.6339 0.770112 14.3872 1.21826L16.0367 2.16222C16.9044 2.65804 17.2 3.77364 16.7042 4.63179C15.8365 6.12879 16.5421 7.34927 18.2679 7.34927C19.2595 7.34927 20.0796 8.15974 20.0796 9.16092V10.8391C20.0796 11.8307 19.2691 12.6507 18.2679 12.6507C16.5421 12.6507 15.8365 13.8712 16.7042 15.3682C17.2 16.2359 16.9044 17.342 16.0367 17.8378L14.3872 18.7817C13.6339 19.2299 12.6613 18.9629 12.2132 18.2096L12.1083 18.0285C11.2501 16.5315 9.83895 16.5315 8.97127 18.0285L8.86638 18.2096C8.41824 18.9629 7.44567 19.2299 6.6924 18.7817L5.04284 17.8378C4.17516 17.342 3.87957 16.2264 4.37539 15.3682C5.24308 13.8712 4.53749 12.6507 2.81165 12.6507C1.81048 12.6507 1 11.8307 1 10.8391Z" stroke="#001B34" stroke-width="1.4" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                        </div> -->

                              <!-- <select class="select2 form-control select2-multiple" id="autoSizingSelect" name="project">
                                 <option selected=""> <i class="bx bx-slider-alt">View settings</option>
                                 <option value="Client Details">Client details</option>
                                 <option value="Comments By Worker">Comments by worker</option>
                                 <option value="Worker Position">Worker position</option>
                                 <option value="Pictures">Pictures</option>
                             </select>
                             <svg class="field_icon" width="18" height="17" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M10.5351 12.8604C12.1149 12.8604 13.3956 11.5797 13.3956 9.99991C13.3956 8.42009 12.1149 7.1394 10.5351 7.1394C8.95525 7.1394 7.67456 8.42009 7.67456 9.99991C7.67456 11.5797 8.95525 12.8604 10.5351 12.8604Z" stroke="#001B34" stroke-width="1.4" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M1 10.8391V9.16092C1 8.16928 1.81048 7.34927 2.81165 7.34927C4.53749 7.34927 5.24308 6.12879 4.37539 4.63179C3.87957 3.77364 4.17516 2.65804 5.04284 2.16222L6.6924 1.21826C7.44567 0.770112 8.41824 1.03709 8.86638 1.79036L8.97127 1.97152C9.82942 3.46852 11.2406 3.46852 12.1083 1.97152L12.2132 1.79036C12.6613 1.03709 13.6339 0.770112 14.3872 1.21826L16.0367 2.16222C16.9044 2.65804 17.2 3.77364 16.7042 4.63179C15.8365 6.12879 16.5421 7.34927 18.2679 7.34927C19.2595 7.34927 20.0796 8.15974 20.0796 9.16092V10.8391C20.0796 11.8307 19.2691 12.6507 18.2679 12.6507C16.5421 12.6507 15.8365 13.8712 16.7042 15.3682C17.2 16.2359 16.9044 17.342 16.0367 17.8378L14.3872 18.7817C13.6339 19.2299 12.6613 18.9629 12.2132 18.2096L12.1083 18.0285C11.2501 16.5315 9.83895 16.5315 8.97127 18.0285L8.86638 18.2096C8.41824 18.9629 7.44567 19.2299 6.6924 18.7817L5.04284 17.8378C4.17516 17.342 3.87957 16.2264 4.37539 15.3682C5.24308 13.8712 4.53749 12.6507 2.81165 12.6507C1.81048 12.6507 1 11.8307 1 10.8391Z" stroke="#001B34" stroke-width="1.4" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                          </div> -->


                          <!-- <div class="col-sm-auto">
                              <button type="button" class="btn  custom_rest_btn">
                                 <a href="{{ url()->current() }}" class="">
                                  <i class="mdi mdi-clock-outline font-size-17  me-2"></i> Add hours</a>
                              </button>
                          </div> -->
                          <div class="col-sm-auto export">

                                <?php
                                $pdfUrl = route('generatepdf');
                                if (isset($_GET['project']) || isset($_GET['worker']) || isset($_GET['date']) || isset($_GET['start_date']) || isset($_GET['end_date']) || isset($_GET['stamp_invoice']))
                                {
                                    $pdfUrl .='?';
                                }
                                if (isset($_GET['project'])) {
                                    $pdfUrl = $pdfUrl . 'project='.$_GET['project'].'&';
                                }
                                if (isset($_GET['worker'])) {
                                    $pdfUrl = $pdfUrl . 'worker='.$_GET['worker'].'&';
                                }
                                if (isset($_GET['date'])) {
                                    $pdfUrl = $pdfUrl . 'date='.$_GET['date'].'&';
                                }
                                if (isset($_GET['start_date'])) {
                                    $pdfUrl = $pdfUrl . 'start_date='.$_GET['start_date'].'&';
                                }
                                if (isset($_GET['end_date'])) {
                                    $pdfUrl = $pdfUrl . 'end_date='.$_GET['end_date'].'&';
                                }
                                if (isset($_GET['stamp_invoice'])) {
                                    $pdfUrl = $pdfUrl . 'stamp_invoice='.$_GET['stamp_invoice'].'&';
                                }
                                ?>
                                <a href="javascript:void(0);" data-href="{{ $pdfUrl }}" rel="noopener noreferrer" class="text-white" id="exportPdfBtn" data-bs-toggle="modal" data-bs-target="#exportPDFModal"  style="width:100%; height:100%;">
                                    <button type="button" class="btn btn-info-show waves-effect waves-light float-end report_action">
                                	<i class="mdi mdi-file-pdf-outline font-size-17 me-2"></i> Export PDF
                                    </button>
                                </a>
<!--                                <a href="{{ $pdfUrl }}" target="_BLANK" class="text-white" style="width:100%; height:100%;">
                                <i class="mdi mdi-file-pdf-outline font-size-17 me-2"></i> Export PDF
                                </a>-->
                           </div>
                      </form>
                    </div>
                  </div>
                 <!--  <div class="row">
                  <form class="row gy-2 gx-3 float-end report_forms">
                        <div class="col-sm-auto">
                              <select class="form-select" id="report_action" name="stamp_invoice">
                                 <option value="">Apply</option>
                                 <option value="Not Invoiced">Mark as NOT invoiced</option>
                                 <option value="Invoiced">Mark as invoiced</option>
                                 <option value="Deleted" style="color:grey">Deleted</option>
                              </select>
                              <svg  class="field_icon" width="18" height="18" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 9V14C21 19 19 21 14 21H8C3 21 1 19 1 14V8C1 3 3 1 8 1H13" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21 9H17C14 9 13 8 13 5V1L21 9Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6 12H12" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6 16H10" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                              </svg>
                        </div>
                  </form>
                  </div> -->
                  <div class="table-responsive">
                  @php
                    $show_worker_positions = (empty($view_settings['hide_worker_positions'])) ? true : false;
                    $show_images_thumbnails = (!empty($view_settings['show_images_thumbnails'])) ? true : false;
                    $show_comments = (empty($view_settings['hide_comments'])) ? true : false;
                    $show_invoice_status = (empty($view_settings['hide_invoice_status'])) ? true : false;
                    $show_breaks = (empty($view_settings['hide_breaks'])) ? true : false;
                    $show_actions = (empty($view_settings['hide_actions'])) ? true : false;
                    $thumbnails_tr_colspan = 8;
                    if(!$show_invoice_status) { $thumbnails_tr_colspan--; }
                    if(!$show_breaks) { $thumbnails_tr_colspan--; }
                    if(!$show_actions) { $thumbnails_tr_colspan--; }
                  @endphp
                  <table id="datatable" class="table table-nowrap mb-50 align-middle  w-100">
                      <thead class=" hr_table">
                      <tr>
                          <th class="no-sort">
                            <div class="form-check font-size-16 align-middle">

                                <input class="form-check-input checkall" type="checkbox">
                                <label class="form-check-label" for="checkall"></label>
                             </div>
                           </th>
                          <th>Date</th>
                          <th>Worker</th>
                          <!-- <th>Project</th> -->
                          <th>Work Hours</th>
                          @if($show_breaks)
                            <th>Break</th>
                          @endif
                          <th>Project / Comments</th>
                          @if($show_invoice_status)
                            <th>Invoice</th>
                          @endif
                          @if($show_actions)
                            <th style="text-align:right;">Action</th>
                          @endif
                      </tr>
                      </thead>
                      <tbody>

                  {{ $hours->withQueryString()->links() }}
                      @foreach($hours as $key => $hour)
                      <tr class="{{ ($show_images_thumbnails && !empty($hour->images)) ? 'bottom-ds-none' : '' }}">
                          <td>
                            <div class="form-check font-size-16">
                                <input class="form-check-input checkbox checkboxData" value="{{$hour->id}}" type="checkbox" name="checkboxData">
                                <label class="form-check-label"></label>
                             </div>
                           </td>
                          <?php
                            $ddate = $hour->work_day;
                            $date = new DateTime($ddate);
                            $week = $date->format("W");
                            ?>
                          <td data-sort='<?= strtotime($hour->work_day) ?>'>
                            <p class="rep_heading">
                                <a data-default-date='{{date("Y-m-d", strtotime($hour->work_day))}}' id="inlineEditDate-{{$hour->id}}" style="text-decoration-style: dashed !important; color: inherit !important;" href="javascript: inlineEditDate({{$hour->id}});">{{date("d-m-Y", strtotime($hour->work_day))}} <i class="mdi mdi-pencil font-size-17 me-2"></i> </a>
                                <span style="display: none" style="" id="inlineEditDate-{{$hour->id}}-field">
                                    <input  class="" style="display: inline-block !important;" id="inlineEditDate-{{$hour->id}}-field-input" type="date" name="" value="" >
                                    <span class="editable-buttons" style="display: inline-block;">
                                        <button onclick="inlineUpdateDate({{$hour->id}})" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="mdi mdi-check"></i></button>
                                        <button onclick="inlineCancelDate({{$hour->id}})" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="mdi mdi-close"></i></button>
                                    </span>
                                </span>
                            </p>
                            <span class="line_down">{{date("l", strtotime($hour->work_day))}} - ({{ $week }})</span></td>
                          <td>
                            <p class="rep_heading">{{$hour['worker']->first_name}} {{$hour['worker']->last_name}} </p>
                            @if($show_worker_positions)
                              <span class="line_down"> {{$hour['worker']->allPositions->pluck('name')->join(', ', ', and ')}} </span>
                            @endif
                          </td>
                          <!---  <td><p class="rep_heading">{{$hour['project']->name}}</p></td>-->
                          <td>
                            <p class="rep_heading">
                                <a data-default-hour='{{ substr($hour->start_time, 0, -3)}}-{{ substr($hour->end_time, 0, -3)}}' id="inlineEditHour-{{$hour->id}}" style="text-decoration-style: dashed !important;  color: inherit !important; " href="javascript: inlineEditHour({{$hour->id}});">{{ substr($hour->start_time, 0, -3)}} to {{ substr($hour->end_time, 0, -3)}} <i class="mdi mdi-pencil font-size-17 me-2"></i> </a>
                                <span style="display: none" style="" id="inlineEditHour-{{$hour->id}}-field">
                                    <select  class="hour_min_selector" id="inlineEditHour-{{$hour->id}}-field-input11"  > </select> :
                                    <select  class="hour_min_selector" id="inlineEditHour-{{$hour->id}}-field-input12"  > </select> to
                                    <select  class="hour_min_selector" id="inlineEditHour-{{$hour->id}}-field-input21"  > </select> :
                                    <select  class="hour_min_selector" id="inlineEditHour-{{$hour->id}}-field-input22"  > </select>
                                    <span class="editable-buttons" style="display: inline-block;">
                                        <button onclick="inlineUpdateHour({{$hour->id}})" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="mdi mdi-check"></i></button>
                                        <button onclick="inlineCancelHour({{$hour->id}})" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="mdi mdi-close"></i></button>
                                    </span>
                                </span>

                            </p>

                            <span class="line_down">Total: {{$hour->working_hours}}h</span></td>
                          @if($show_breaks)
                            <td>
                                @if($hour->break_time == '0')
                                <p class="rep_heading">No</p>
                                @else
                                <p class="rep_heading">Yes</p><span class="line_down">{{ $hour->break_time}} Min</span>
                                @endif
                            </td>
                          @endif
                          <td>
                            <p class="rep_heading">{{$hour['project']->nameAndNumber()}}</p>
                            @if($show_comments)
                              <span class="line_down">
                                  @if(strlen($hour->comments) > 30)
                                    <span id="hourComment-{{$hour->id}}">{{substr($hour->comments,0,30)}}</span> <a data-bs-target=".edit-comment-modal" data-id="{{$hour->id}}" data-comments="{{$hour->comments}}" class="edit-hour-comment" data-bs-toggle="modal"> ...more</a>
                                  @elseif($hour->comments)
                                    <span id="hourComment-{{$hour->id}}">{{$hour->comments}}</span> <a data-bs-target=".edit-comment-modal" data-id="{{$hour->id}}" data-comments="{{$hour->comments}}" class="edit-hour-comment" data-bs-toggle="modal"> <i class="bx bx-pencil" ></i></a>
                                  @else
                                    {{$hour->comments}}
                                  @endif
                              </span>
                            @endif
                          </td>
                          @if($show_invoice_status)
                            @if($hour->stamp_invoice == '1')
                              <td><p class="rep_heading invoiced">Invoiced</p><span class="line_down">{{date("d-m-Y",strtotime($hour->updated_at))}}</span></td>
                            @else
                            <td><p class="rep_heading notinvoiced">Not invoiced</p></td>
                            @endif
                          @endif
                          @if($show_actions)
                            <td class="action align-middle" style="text-align:right;">
                              <div class="entries_action">

                                @if($hour->images)
                                <div class="avatar-xs me-3 report_smry_iconsize">
                                <a href="" data-bs-toggle="modal" data-bs-target=".create-image-modal" data-hour_id ="{{$hour->id}}" class="hour_images" >
                                      <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                                      <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.3 8.2001C8.29411 8.2001 9.1 7.39421 9.1 6.4001C9.1 5.40599 8.29411 4.6001 7.3 4.6001C6.30589 4.6001 5.5 5.40599 5.5 6.4001C5.5 7.39421 6.30589 8.2001 7.3 8.2001Z" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.9 1H7.3C2.8 1 1 2.8 1 7.3V12.7C1 17.2 2.8 19 7.3 19H12.7C17.2 19 19 17.2 19 12.7V8.2" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M15.3999 1V6.4L17.1999 4.6" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M15.4001 6.4001L13.6001 4.6001" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M1.60303 16.2551L6.04003 13.2761C6.75103 12.7991 7.77703 12.8531 8.41603 13.4021L8.71303 13.6631C9.41503 14.2661 10.549 14.2661 11.251 13.6631L14.995 10.4501C15.697 9.84705 16.831 9.84705 17.533 10.4501L19 11.7101" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                      </svg>
                                    </span>
                                </a>
                                </div>
                                @endif
                               @if($hour->stamp_invoice == 0)
                                <div class="avatar-xs me-3 report_smry_iconsize">
                                <a href="" id='create-hour-{{$hour->id}}' data-bs-target=".create-hour-modal" class="edit_hours" data-hour_id ="{{$hour->id}}" data-bs-toggle="modal" class="edit_hour">

                                    <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                      <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.1 1H7.3C2.8 1 1 2.8 1 7.3V12.7C1 17.2 2.8 19 7.3 19H12.7C17.2 19 19 17.2 19 12.7V10.9" stroke="#2F45C5" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14.2195 1.9785L8.15489 8.04398C7.92401 8.2749 7.69312 8.72904 7.64694 9.06003L7.31601 11.3769C7.19287 12.2159 7.78547 12.8009 8.62436 12.6855L10.9409 12.3545C11.2642 12.3083 11.7182 12.0774 11.9568 11.8465L18.0214 5.78097C19.0681 4.73414 19.5607 3.51797 18.0214 1.9785C16.4822 0.439043 15.2662 0.93167 14.2195 1.9785Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M13.6001 2.80005C14.0884 4.54175 15.4511 5.9045 17.2001 6.40005" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                      </svg>
                                    </span>
                                  </a>
                                </div>
                                @endif

                                <div class="avatar-xs me-3 report_smry_iconsize">
                                  <span class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                          <span class="avatar-title rounded-circle bg-white bg-soft text-warning font-size-15">
                                            <svg width="4" height="15" viewBox="0 0 4 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3.46154 13.2692C3.46154 13.7283 3.27919 14.1685 2.95461 14.4931C2.63003 14.8177 2.1898 15 1.73077 15C1.27174 15 0.831513 14.8177 0.50693 14.4931C0.182348 14.1685 0 13.7283 0 13.2692C0 12.8102 0.182348 12.37 0.50693 12.0454C0.831513 11.7208 1.27174 11.5385 1.73077 11.5385C2.1898 11.5385 2.63003 11.7208 2.95461 12.0454C3.27919 12.37 3.46154 12.8102 3.46154 13.2692ZM3.46154 7.5C3.46154 7.95903 3.27919 8.39926 2.95461 8.72384C2.63003 9.04842 2.1898 9.23077 1.73077 9.23077C1.27174 9.23077 0.831513 9.04842 0.50693 8.72384C0.182348 8.39926 0 7.95903 0 7.5C0 7.04097 0.182348 6.60074 0.50693 6.27616C0.831513 5.95158 1.27174 5.76923 1.73077 5.76923C2.1898 5.76923 2.63003 5.95158 2.95461 6.27616C3.27919 6.60074 3.46154 7.04097 3.46154 7.5ZM3.46154 1.73077C3.46154 2.1898 3.27919 2.63003 2.95461 2.95461C2.63003 3.27919 2.1898 3.46154 1.73077 3.46154C1.27174 3.46154 0.831513 3.27919 0.50693 2.95461C0.182348 2.63003 0 2.1898 0 1.73077C0 1.27174 0.182348 0.831513 0.50693 0.506931C0.831513 0.182348 1.27174 0 1.73077 0C2.1898 0 2.63003 0.182348 2.95461 0.506931C3.27919 0.831513 3.46154 1.27174 3.46154 1.73077Z" fill="#667685"/>
                                                </svg>
                                          </span>
                                          </a>
                                        <span class="dropdown-menu dropdown-menu-end">

                                        <a class="dropdown-item" href="{{ route('hours.update.invoice', ['hour_id' => $hour->id]) }}">Invoice</a>
                                        <a class="dropdown-item" href="{{ route('hours.update.notinvoice', ['hour_id' => $hour->id]) }}">Not Invoice</a>
                                        <a class="dropdown-item text-danger" data-method="delete" data-confirm="Are you sure?" href="{{ route('hours.destroy.report', ['hour_id' => $hour->id]) }}" >Delete</a>
                                        </span>
                                  </span>
                              </div>
                              </div>
                            </td>
                          @endif
                      </tr>
                        @if($show_images_thumbnails && !empty($hour->images))
                          <tr>
                            <td colspan="{{ $thumbnails_tr_colspan }}">
                                <div class="popup-gallery d-flex flex-wrap grid-of-images">
                                  @foreach((array)$hour->images as $image_nr => $image)
                                    <a href="{{ Storage::url($image) }}" title="{{$hour['worker']->first_name}} {{$hour['worker']->last_name}}" class="">
                                      <div class="thbnls_bg" style="background-image: url('{{ Storage::url($image) }}'); width: 150px;height: 150px;background-position: center;background-size: cover;">
                                      </div>
                                    </a>
                                	@endforeach
                              	</div>
                            </td>
                          </tr>
                        @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>
                  {{ $hours->withQueryString()->links() }}
              </div>
          </div>
      </div>
    </div>
</div>

@endsection

@section('scripts')
    @parent
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <!-- <script src="{{ env('PUBLIC_PATH') }}/skote/assets/js/pages/form-advanced.init.js"></script> -->
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/js/pages/lightbox.init.js"></script>

<script>
    try {
      $('#datatable').dataTable({
          paging: false,
          columnDefs: [
              { targets: 'no-sort', orderable: false }
          ],
        order: [
            [ 1, "desc"]
        ],
      });
    } catch(err) {

    }

    function status(divId, element)
    {
        document.getElementById(divId).style.display = element.value == "Custom" ? 'block' : 'none';
    }
    // $("#report_action").on("change", function(){

    $(".apply_action").on("click", function(){
        let action = $("#report_action").val();
        let hour_ids = [];
        $(".checkboxData:checked").each(function(){
            hour_ids.push($(this).val());
        });

        //alert(action);
        //alert(hour_ids);
        if (hour_ids.length > 0 && action != '') {
            $.ajax({
                url: "{{ route('hours.update.report') }}",
                method: "POST",
                data: {'hour_ids': hour_ids, 'action': action},
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                },
                success: function (data) {
                    showMessage(data.success, 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }
            });
        }
    });

    $(document).ready(function(){

        $(".edit-hour-comment").on('click', function(){
           $("#hour_comments").val($(this).attr("data-comments"));
           $("#hour_id").val($(this).data("id"));
        });

        $("#saveComments").on('click', function(){
            var hour_id = $("#hour_id").val();
            var hour_comments = $("#hour_comments").val();
            $.ajax({
                url: '/hours/comments',
                data: $("#hour_comments_form").serialize(),
                type: "POST",
                success: function (data) {
                    // $(".page-content").prepend('<div class="alert alert-success" style="position: absolute; top: 75px; left: 26px; right: 26px; background-color: #34c38f; color: #fff; text-align: center; border: 0; z-index: 1; border-right: 10px; min-height: 30px; display: flex; justify-content: center; align-content: center; flex-direction: column;"> '+data.success+' </div>');
                    var comments = hour_comments;
                    if(hour_comments.length > 30) {
                      comments = hour_comments.slice(0, 30);
                      $('#hourComment-' + hour_id).siblings('.edit-hour-comment').html(' ...more');
                    } else {
                      $('#hourComment-' + hour_id).siblings('.edit-hour-comment').html(' <i class="bx bx-pencil" ></i>');
                    }
                    $('#hourComment-' + hour_id).text(comments);
                    $('#hourComment-' + hour_id).siblings('.edit-hour-comment').attr('data-comments', hour_comments);
                    showMessage(data.success, 'success');
                    // setTimeout(function(){
                    //     window.location.href = data.redirect;
                    // }, 1000);
                }
            }).fail(function(jqXHR, textStatus) {
                var errMsg = $.parseJSON(jqXHR.responseText);
                errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                showMessage(errMsg, 'danger');
            });
        })

        // Check or Uncheck All checkboxes
        $(".checkall").click(function(){
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
            $("#checkall").prop("checked", true);
          } else {
            $("#checkall").prop("checked", false);
          }
        });

        // Initialize Select2
        $('#sel_users').select2();

        // Set option selected onchange
        $('#user_selected').change(function(){
          var value = $(this).val();

          // Set selected
          $('#sel_users').val(value);
          $('#sel_users').select2().trigger('change');

        });
    });
</script>
<script>
$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});

    $(".hour_images").on('click', function(){
     let hour_id = $(this).data('hour_id');
    $.ajax({
            url: '/hours/images/'+hour_id,
            type: 'GET',
            success: function(data) {
              // alert(data);

               $("#image_edit").html(data);
               $(".create-image-modal").modal('show');

            },
            error: function(err) {
                console.log(err);
                alert('error');
            }
        });
    });
// for edit hour----------

  $(".edit_hours").on('click', function(){
      let hour_id = $(this).data('hour_id');

      $.ajax({
              url: '/hours/'+hour_id+'/edit',
              type: 'GET',
              success: function(data) {
                $("#hour_edit").html(data);
                $(".create-hour-modal").modal('show');
              },
              error: function(err) {
                  console.log(err);
                  alert('error');
              }
          });
      });

  $("#viewSettings").on('click', function(){
    viewSettings('hours');
  });

  // Export PDF
  $(document).on('submit', '#exportPDFForm', function(e) {
    e.preventDefault();
    var formData = $('#exportPDFForm').serialize();
    var pdfURL = $('#exportPdfBtn').attr('data-href');
    var lastChar = pdfURL[pdfURL.length -1];
    pdfURL = pdfURL + ((lastChar == '&') ? '' : '?') + formData;
    $('#exportPDFModal').modal('hide');
    setTimeout(function() { window.open(pdfURL, '_blank'); }, 300);
  });
  // Export PDF

  let editInfo = {
    id: null,
    defaultDate: null,
  }
  function inlineUpdateDate(id){
    const newValue = $(`#inlineEditDate-${id}-field-input`).val();
    jQuery.ajax({
      url: `/hours/update-inline/${id}`,
      method: 'put',
      data: {
         type: 'date',
         date: newValue
      },
      success: function(result){
        var work_day = result.data.work_day;
        var newdate = work_day.split("-").reverse().join("-");
         $(`#inlineEditDate-${id}`).html(newdate+' <i class="mdi mdi-pencil font-size-17 me-2"></i>');
      }
    });
    inlineCancelDate(id);
  }
  function inlineCancelDate(id){
    $(`#inlineEditDate-${id}`).show();
    $(`#inlineEditDate-${id}-field`).hide();
  }
  function inlineEditDate(id){
    if(id != editInfo.id){
        // close the update field
        inlineCancelDate(editInfo.id)
    }
    const defaultDate = $(`#inlineEditDate-${id}`).data('default-date');
    editInfo = {
        id,
        defaultDate
    }
    $(`#inlineEditDate-${id}`).hide();
    $(`#inlineEditDate-${id}-field`).show();
    $(`#inlineEditDate-${id}-field-input`).val(defaultDate);
  }


  let editHourInfo = {
    id: null,
    defaultHour: null,
  }
  function inlineUpdateHour(id){
    // getting start and end time values in h:m format
    const from = $(`#inlineEditHour-${id}-field-input11`).val()+":"+$(`#inlineEditHour-${id}-field-input12`).val();
    const to = $(`#inlineEditHour-${id}-field-input21`).val()+":"+$(`#inlineEditHour-${id}-field-input22`).val();
    jQuery.ajax({
      url: `/hours/update-inline/${id}`,
      method: 'PUT',
      data: {
         type: 'hour',
         from: from,
         to: to
      },
      success: function(result){
        const newContent = `${result.data.start_time} to ${result.data.end_time}`;
         $(`#inlineEditHour-${id}`).html(newContent+' <i class="mdi mdi-pencil font-size-17 me-2"></i>');
      }
    });
    inlineCancelHour(id);
  }
  function inlineCancelHour(id){
    $(`#inlineEditHour-${id}`).show();
    $(`#inlineEditHour-${id}-field`).hide();
  }
  function inlineEditHour(id){
    if(id != editHourInfo.id){
        // close the update field
        inlineCancelHour(editHourInfo.id);
    }
    const defaultHour = $(`#inlineEditHour-${id}`).data('default-hour');
    editHourInfo = {
        id,
        defaultHour
    }
    const [from, to] = defaultHour.split('-');
    $(`#inlineEditHour-${id}`).hide();
    $(`#inlineEditHour-${id}-field`).show();
    let b = "";
    for (var i = 0; i < 60; i++) {
        b+=`<option>${String(i).padStart(2,'0')}</option>`;
    }
    $(`#inlineEditHour-${id}-field-input11`).html(b);
    $(`#inlineEditHour-${id}-field-input12`).html(b);
    $(`#inlineEditHour-${id}-field-input21`).html(b);
    $(`#inlineEditHour-${id}-field-input22`).html(b);

    $(`#inlineEditHour-${id}-field-input11`).val(String( from.split(':')[0]).padStart(2,'0'));
    $(`#inlineEditHour-${id}-field-input12`).val(String( from.split(':')[1]).padStart(2,'0'));
    $(`#inlineEditHour-${id}-field-input21`).val(String( to.split(':')[0]).padStart(2,'0'));
    $(`#inlineEditHour-${id}-field-input22`).val(String( to.split(':')[1]).padStart(2,'0'));
  }


</script>
@endsection
