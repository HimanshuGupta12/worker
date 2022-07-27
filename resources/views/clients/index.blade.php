@extends('layouts.user')

@section('head')
<meta name="_token" content="{{ csrf_token() }}">
    @parent
    <link href="/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
    
    <!--<link href="{{ URL::asset('css/worker-reports.css') }}" rel="stylesheet" type="text/css" />-->  
    <link href="{{ env('PUBLIC_PATH') }}/css/worker-reports.css" rel="stylesheet" type="text/css" />
    <style type="text/css">

 
    input , textarea , select {
        width: 100% !important;
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

   <!-- Client sort -->

	    <div class="row">
	        <div class="col-md-12 col-lg-12">
	          <div class="card card-body card_summry">
	            <h5 class="font-size-18 mb-3 report_card_title"><i class="bx bx-slider-alt"></i>  Sort</h5>
	                <form class="report_forms">
	                    <div class="row">
	                        <div class="col-md-4 col-lg-4 col-sm-4">
	                            <div class="mb-3">
	                                <select class="form-select" name="client_id" style="width: 100%;">
                                                <option value="">&nbsp;</option>
                                                @foreach ($search_clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                @endforeach
                                     </select>
	                                  <svg class="field_icon" width="16" height="18" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
	                                        <path d="M10 11C12.7615 11 15 8.76142 15 6C15 3.23858 12.7615 1 10 1C7.23861 1 5.00003 3.23858 5.00003 6C5.00003 8.76142 7.23861 11 10 11Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
	                                        <path d="M18.59 21C18.59 17.13 14.74 14 10 14C5.26003 14 1.41003 17.13 1.41003 21" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
	                                  </svg>
	                            </div>
	                        </div>
	     
	                        <div class="col-md-4">
	                            <div class=" row mb-3 row-cols-lg-auto row-cols-sm-auto">
	                                <div class="col-6">
	                                	<button type="submit" class="btn btn-info-show w-md">Search</button>
	                                </div>

	                                <div class="col-6">
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

	<!--Client sort End  -->

        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-body">
		                    <div class="row">
			                        <div class="col-md-6 mt-2">
			                          <h4 class="report_card_title">Clients details</h4>
			                        </div>

			                        <div class="col-md-6">
			                            <form class="row gy-2 gx-3 float-end report_forms ">
			                              <div class="col-sm-auto mb-3">
			                                    <a href="{{ route('clients.create') }}" class="text-white">
			                                      <button type="button" class="btn btn-info-show waves-effect waves-light float-end ">
			                                        + Add clients
			                                      </button>
                                                            </a>
			                               </div>
			                            </form>
			                        </div>
		                    </div>
                        	<div class="table-responsive">
                                <table class="table align-middle table-nowrap mb-0">
                                	<thead class="table-light-color">
	                                    <tr>
	                                        <th>Name</th>
	                                        <th>Email</th>
	                                        <th>Phone</th>
	                                        <th>Company</th>
	                                        <th>Additional note</th>
	                                        <th>Action</th>
	                                    </tr>
                                    </thead>
                                    <tbody>
	                                @foreach($clients as $client)
	                                    <tr>
	                                        <td><p class="rep_heading">{{ $client->name }}</p><span class="line_down" style="text-transform: capitalize;">{{ $client->type }}</span></td>
	                                        <td>{{ $client->email }}</td>
	                                        <td>{{ $client->phone_country.$client->phone_number }}</td>
	                                        <td><strong>{{ $client->company_name}}</strong><br/>{{$client->company_org_no }}</td>
	                                        <td>{{ (strlen($client->additional_note) > 300) ? substr($client->additional_note,0,300).' ...' : $client->additional_note }}</td>
	                                        <td class="action align-middle" style="text-align:right;">
	                                         <div class="entries_action" >
			                                    <div class="avatar-xs me-3 report_smry_iconsize">
			                                         <a href="{{ route('clients.edit', $client->id) }}">
			                                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
			                                               <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
			                                                    <path d="M9.1 1H7.3C2.8 1 1 2.8 1 7.3V12.7C1 17.2 2.8 19 7.3 19H12.7C17.2 19 19 17.2 19 12.7V10.9" stroke="#2F45C5" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
			                                                    <path d="M14.2197 1.9785L8.15508 8.04398C7.92419 8.2749 7.6933 8.72904 7.64713 9.06003L7.31619 11.3769C7.19305 12.2159 7.78566 12.8009 8.62455 12.6855L10.9411 12.3545C11.2643 12.3083 11.7184 12.0774 11.957 11.8465L18.0216 5.78097C19.0683 4.73414 19.5609 3.51797 18.0216 1.9785C16.4824 0.439043 15.2664 0.93167 14.2197 1.9785Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
			                                                    <path d="M13.6 2.80005C14.0882 4.54175 15.451 5.9045 17.2 6.40005" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
			                                                </svg>

			                                            </span>
			                                        </a>
			                                   </div> 
			                                   <div class="avatar-xs me-3 report_smry_iconsize" style="text-align: right;">
		                                        <span class="dropdown">
		                                              <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown"   aria-expanded="false">
		                                                <span class="avatar-title rounded-circle bg-white bg-soft text-warning font-size-15">
		                                                  <svg width="4" height="15" viewBox="0 0 4 15" fill="none" xmlns="http://www.w3.org/2000/svg">
		                                                      <path d="M3.46154 13.2692C3.46154 13.7283 3.27919 14.1685 2.95461 14.4931C2.63003 14.8177 2.1898 15 1.73077 15C1.27174 15 0.831513 14.8177 0.50693 14.4931C0.182348 14.1685 0 13.7283 0 13.2692C0 12.8102 0.182348 12.37 0.50693 12.0454C0.831513 11.7208 1.27174 11.5385 1.73077 11.5385C2.1898 11.5385 2.63003 11.7208 2.95461 12.0454C3.27919 12.37 3.46154 12.8102 3.46154 13.2692ZM3.46154 7.5C3.46154 7.95903 3.27919 8.39926 2.95461 8.72384C2.63003 9.04842 2.1898 9.23077 1.73077 9.23077C1.27174 9.23077 0.831513 9.04842 0.50693 8.72384C0.182348 8.39926 0 7.95903 0 7.5C0 7.04097 0.182348 6.60074 0.50693 6.27616C0.831513 5.95158 1.27174 5.76923 1.73077 5.76923C2.1898 5.76923 2.63003 5.95158 2.95461 6.27616C3.27919 6.60074 3.46154 7.04097 3.46154 7.5ZM3.46154 1.73077C3.46154 2.1898 3.27919 2.63003 2.95461 2.95461C2.63003 3.27919 2.1898 3.46154 1.73077 3.46154C1.27174 3.46154 0.831513 3.27919 0.50693 2.95461C0.182348 2.63003 0 2.1898 0 1.73077C0 1.27174 0.182348 0.831513 0.50693 0.506931C0.831513 0.182348 1.27174 0 1.73077 0C2.1898 0 2.63003 0.182348 2.95461 0.506931C3.27919 0.831513 3.46154 1.27174 3.46154 1.73077Z" fill="#667685"/>
		                                                      </svg>
		                                                  </span>
		                                                </a>
		                                               <span class="dropdown-menu dropdown-menu-end">
		                                                  <a class="dropdown-item delete-project" data-url="{{ route('clients.destroy', $client->id) }}" >Delete</a>
		                                              </span>
		                                         	</span>
		                                    	</div>
			                              		</div>
	                                        </td>
	                                    </tr>
	                                @endforeach
                                   </tbody>
                                </table>
                        </div>
                    </div>
                </div>
                {{ $clients->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
