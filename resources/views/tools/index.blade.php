@extends( Auth::check() ? 'layouts.user' : 'layouts.worker')

@section('head')
    @parent

<!--<link href="{{ URL::asset('css/worker-reports.css') }}" rel="stylesheet" type="text/css" />-->
<link href="{{ env('PUBLIC_PATH') }}/css/worker-reports.css" rel="stylesheet" type="text/css" />
<link href="/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />


    <style type="text/css">

    img.worker-image {
        border-radius: 50%;
        width: 60px;
        height: 60px;
    }
    ul.list-group.custom_icon_list li.list-group-item {
        padding: 0.4rem 0rem;
    }

    .custom_icon_list .media .avatar-xs.me-3 {
        height: 25px;
        width: 25px;
    }
    div#datatable_filter {
    display: none;
    }

    div#datatable_length {
    display: none;
    }
.table-light-color
{
    color: #495057;
    border-color: #eff2f7;
    background-color: #fbfbfb;
}

    .tool {
      position:absolute;
      top:50% !important;
      left:25%;
      transform: translate(0, -50%) !important;
      -ms-transform: translate(0, -50%) !important;
      -webkit-transform: translate(0, -50%) !important;
      margin:auto 5%;
      width:50%;
      height:50%;
    }
    div#datatable_filter {
    display: none;
    }

    </style>
@endsection

@section('content')
<div class="container-fluid">
     
<!-- Report summry Worker -->

    <div class="modal fade transfer-tool-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myExtraLargeModalLabel">{{__("Tool")}}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{__('Close')}}"></button>
                </div>
                <div class="modal-body" id="transfer-tool-content">
                    
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade tool-history-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myExtraLargeModalLabel">Tool history</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="tool-history-content">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
<!-- Report summry Worker -->
    <div class="row">
    <div class="col-md-9 col-lg-9">
          <div class="card card-body card_summry">
              <h4 class="report_card_title mb-4">{{__('Tool details')}}</h4>
                <div class="row ">
                    
                    <div class="col-md-3">

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                            <svg width="18" height="17" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.2299 7.23163C10.0024 7.45371 9.87238 7.7733 9.90488 8.11455C9.95363 8.69955 10.4899 9.12746 11.0749 9.12746H12.104V9.77204C12.104 10.8933 11.1886 11.8087 10.0674 11.8087H4.59113C4.75905 11.6679 4.90529 11.4945 5.01904 11.2995C5.21946 10.9745 5.33321 10.59 5.33321 10.1837C5.33321 8.98663 4.36363 8.01705 3.16654 8.01705C2.65738 8.01705 2.18613 8.1958 1.81238 8.49372V6.12664C1.81238 5.00539 2.72779 4.08997 3.84904 4.08997H10.0674C11.1886 4.08997 12.104 5.00539 12.104 6.12664V6.90664H11.0099C10.7065 6.90664 10.4303 7.02579 10.2299 7.23163Z" stroke="#2F45C5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1.81244 6.61405V4.13866C1.81244 3.49408 2.20786 2.91989 2.80911 2.69239L7.10994 1.06739C7.7816 0.812803 8.50202 1.31115 8.50202 2.03157V4.0899" stroke="#2F45C5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12.6776 7.45909V8.57496C12.6776 8.87288 12.4393 9.1166 12.136 9.12744H11.0743C10.4893 9.12744 9.95305 8.69952 9.9043 8.11452C9.8718 7.77327 10.0018 7.45369 10.2293 7.2316C10.4297 7.02577 10.706 6.90662 11.0093 6.90662H12.136C12.4393 6.91745 12.6776 7.16118 12.6776 7.45909Z" stroke="#2F45C5" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4.24994 6.39197H8.04161" stroke="#2F45C5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.33327 10.1836C5.33327 10.5899 5.21952 10.9745 5.0191 11.2995C4.90535 11.4945 4.75911 11.6678 4.59119 11.8086C4.21202 12.1499 3.71369 12.3503 3.16661 12.3503C2.37577 12.3503 1.68786 11.9278 1.31411 11.2995C1.11369 10.9745 0.999939 10.5899 0.999939 10.1836C0.999939 9.50113 1.31411 8.88905 1.81244 8.49364C2.18619 8.19572 2.65744 8.01697 3.16661 8.01697C4.36369 8.01697 5.33327 8.98655 5.33327 10.1836Z" stroke="#2F45C5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3.7462 10.7469L2.59787 9.604" stroke="#2F45C5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3.73537 9.62036L2.58704 10.7633" stroke="#2F45C5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 ">{{__('Not balanced in storage')}}: <span class="cstn_bold">{{$storageToolsUnbalanced}}</span></h5>
                        </div>

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                                <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                    <svg width="18" height="17" viewBox="0 0 15 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.0001 4.09523V7.19047C14.0001 9.04761 12.762 10.2857 10.9048 10.2857H5.10435C5.29625 10.1248 5.46338 9.92666 5.59338 9.7038C5.82243 9.33237 5.95244 8.89285 5.95244 8.42856C5.95244 7.06047 4.84435 5.95238 3.47625 5.95238C2.73339 5.95238 2.07101 6.28046 1.61911 6.79427V4.09523C1.61911 2.41143 2.63435 1.23524 4.21292 1.03714C4.37387 1.01238 4.54101 1 4.71435 1H10.9048C11.0658 1 11.2205 1.00618 11.3691 1.03094C12.9662 1.21666 14.0001 2.39905 14.0001 4.09523Z" stroke="#2697FF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14 4.40479H12.1428C11.4619 4.40479 10.9047 4.96193 10.9047 5.64288C10.9047 6.32383 11.4619 6.88097 12.1428 6.88097H14" stroke="#2697FF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M5.95238 8.42834C5.95238 8.89262 5.82237 9.33214 5.59332 9.70357C5.46332 9.92643 5.29619 10.1245 5.10429 10.2855C4.67095 10.6755 4.10143 10.9045 3.47619 10.9045C2.57238 10.9045 1.7862 10.4217 1.35906 9.70357C1.13001 9.33214 1 8.89262 1 8.42834C1 7.8031 1.23524 7.23357 1.61905 6.79404C2.07095 6.28023 2.73333 5.95215 3.47619 5.95215C4.84428 5.95215 5.95238 7.06024 5.95238 8.42834Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M4.1394 9.07205L2.83322 7.76587" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M4.1199 7.78491L2.81372 9.09109" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                            <h5 class="rep_heading  mb-0 ">{{__('Not balanced at workers')}}: <span class="cstn_bold">{{$workerToolsUnbalanced}}</span></h5>
                         </div>

                    </div>
                    <div class="col-md-3">

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                            <svg width="18" height="17" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.2299 7.23163C10.0024 7.45371 9.87238 7.7733 9.90488 8.11455C9.95363 8.69955 10.4899 9.12746 11.0749 9.12746H12.104V9.77204C12.104 10.8933 11.1886 11.8087 10.0674 11.8087H4.59113C4.75905 11.6679 4.90529 11.4945 5.01904 11.2995C5.21946 10.9745 5.33321 10.59 5.33321 10.1837C5.33321 8.98663 4.36363 8.01705 3.16654 8.01705C2.65738 8.01705 2.18613 8.1958 1.81238 8.49372V6.12664C1.81238 5.00539 2.72779 4.08997 3.84904 4.08997H10.0674C11.1886 4.08997 12.104 5.00539 12.104 6.12664V6.90664H11.0099C10.7065 6.90664 10.4303 7.02579 10.2299 7.23163Z" stroke="#2F45C5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1.81244 6.61405V4.13866C1.81244 3.49408 2.20786 2.91989 2.80911 2.69239L7.10994 1.06739C7.7816 0.812803 8.50202 1.31115 8.50202 2.03157V4.0899" stroke="#2F45C5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12.6776 7.45909V8.57496C12.6776 8.87288 12.4393 9.1166 12.136 9.12744H11.0743C10.4893 9.12744 9.95305 8.69952 9.9043 8.11452C9.8718 7.77327 10.0018 7.45369 10.2293 7.2316C10.4297 7.02577 10.706 6.90662 11.0093 6.90662H12.136C12.4393 6.91745 12.6776 7.16118 12.6776 7.45909Z" stroke="#2F45C5" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4.24994 6.39197H8.04161" stroke="#2F45C5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.33327 10.1836C5.33327 10.5899 5.21952 10.9745 5.0191 11.2995C4.90535 11.4945 4.75911 11.6678 4.59119 11.8086C4.21202 12.1499 3.71369 12.3503 3.16661 12.3503C2.37577 12.3503 1.68786 11.9278 1.31411 11.2995C1.11369 10.9745 0.999939 10.5899 0.999939 10.1836C0.999939 9.50113 1.31411 8.88905 1.81244 8.49364C2.18619 8.19572 2.65744 8.01697 3.16661 8.01697C4.36369 8.01697 5.33327 8.98655 5.33327 10.1836Z" stroke="#2F45C5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3.7462 10.7469L2.59787 9.604" stroke="#2F45C5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3.73537 9.62036L2.58704 10.7633" stroke="#2F45C5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 ">{{__('Lost tools')}}: <span class="cstn_bold">{{$totalLostTools}}</span></h5>
                        </div>

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                            <svg width="18" height="17" viewBox="0 0 15 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.0001 4.09523V7.19047C14.0001 9.04761 12.762 10.2857 10.9048 10.2857H5.10435C5.29625 10.1248 5.46338 9.92666 5.59338 9.7038C5.82243 9.33237 5.95244 8.89285 5.95244 8.42856C5.95244 7.06047 4.84435 5.95238 3.47625 5.95238C2.73339 5.95238 2.07101 6.28046 1.61911 6.79427V4.09523C1.61911 2.41143 2.63435 1.23524 4.21292 1.03714C4.37387 1.01238 4.54101 1 4.71435 1H10.9048C11.0658 1 11.2205 1.00618 11.3691 1.03094C12.9662 1.21666 14.0001 2.39905 14.0001 4.09523Z" stroke="#2697FF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 4.40479H12.1428C11.4619 4.40479 10.9047 4.96193 10.9047 5.64288C10.9047 6.32383 11.4619 6.88097 12.1428 6.88097H14" stroke="#2697FF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.95238 8.42834C5.95238 8.89262 5.82237 9.33214 5.59332 9.70357C5.46332 9.92643 5.29619 10.1245 5.10429 10.2855C4.67095 10.6755 4.10143 10.9045 3.47619 10.9045C2.57238 10.9045 1.7862 10.4217 1.35906 9.70357C1.13001 9.33214 1 8.89262 1 8.42834C1 7.8031 1.23524 7.23357 1.61905 6.79404C2.07095 6.28023 2.73333 5.95215 3.47619 5.95215C4.84428 5.95215 5.95238 7.06024 5.95238 8.42834Z" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4.1394 9.07205L2.83322 7.76587" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4.1199 7.78491L2.81372 9.09109" stroke="#2697FF" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading  mb-0 ">{{__('Lost tools amount')}}: <span class="cstn_bold">{{ number_format($lostToolsvalue, 0, ',', '.') }},-</span></h5>
                         </div>

                    </div>
                    
                    <div class="col-md-3">

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                            <svg width="18" height="18" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.79167 11.8333H8.04167C10.75 11.8333 11.8333 10.75 11.8333 8.04167V4.79167C11.8333 2.08333 10.75 1 8.04167 1H4.79167C2.08333 1 1 2.08333 1 4.79167V8.04167C1 10.75 2.08333 11.8333 4.79167 11.8333Z" stroke="#2697FF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3.70824 4.73205C5.41449 3.88163 7.41866 3.88163 9.12491 4.73205" stroke="#2697FF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.41662 8.74591V4.21216" stroke="#2697FF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 ">{{__('Tools in storage')}}: <span class="cstn_bold">{{$toolsInStorage}}</span></h5>
                        </div>

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                            <svg width="18" height="18" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.33333 8.66671H2.94125C1.70083 8.66671 1.08333 8.04921 1.08333 6.80879V2.94129C1.08333 1.70087 1.70083 1.08337 2.94125 1.08337H5.41667C6.65708 1.08337 7.27458 1.70087 7.27458 2.94129" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.0588 11.9167H7.58333C6.34292 11.9167 5.72542 11.2992 5.72542 10.0588V6.19129C5.72542 4.95087 6.34292 4.33337 7.58333 4.33337H10.0588C11.2992 4.33337 11.9167 4.95087 11.9167 6.19129V10.0588C11.9167 11.2992 11.2992 11.9167 10.0588 11.9167Z" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.05465 8.125H9.82048" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.9375 9.0079V7.24207" stroke="#FFA113" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 ">{{__('Tools at worker')}}: <span class="cstn_bold">{{$toolsAtWorker}}</span></h5>
                         </div>

                    </div>

                    <div class="col-md-3">

                         <div class="d-flex align-items-center mb-2">
                            <div class="avatar-xs me-3 report_smry_iconsize">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                            <svg width="18" height="18" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.79167 11.8333H8.04167C10.75 11.8333 11.8333 10.75 11.8333 8.04167V4.79167C11.8333 2.08333 10.75 1 8.04167 1H4.79167C2.08333 1 1 2.08333 1 4.79167V8.04167C1 10.75 2.08333 11.8333 4.79167 11.8333Z" stroke="#2697FF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3.70824 4.73205C5.41449 3.88163 7.41866 3.88163 9.12491 4.73205" stroke="#2697FF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.41662 8.74591V4.21216" stroke="#2697FF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            </span>
                            </div>
                            <h5 class="rep_heading mb-0 ">{{__('Tools in service')}}: <span class="cstn_bold">{{$totalInServiceTools}}</span></h5>
                        </div>



                    </div>

                </div>
          </div>

        </div>
        <div class="col-md-3 col-lg-3" style="min-width: 238px;">
          <div class="card card-body card_hilght">
            <h4 class="report_card_title_white">{{__('Tool details')}}</h4>
                <div class="row cstm_sprtr">
                  <div class="col-md-6">
                    <h1 class="text-white hilited_heading">{{ $totalTools }}</h1>
                    <span class="text-white font-size-13">{{__('Total tools')}}</span>
                  </div>
                  <div class="col-md-6">
                    <h1 class="text-white hilited_heading">{{ number_format($value, 0, ',', '.') }},-</h1>
                    <span class="text-white font-size-13">{{__('Total value')}}</span>
                  </div>
                  </div>
                <img src="{{ env('PUBLIC_PATH') }}/img/trap-logo.png" alt="" height="40" class="bg_logo"> 
          </div>
        </div>
    </div>

<!-- Report summry Worker End  -->
     
     <!-- Filters -->
                <div class="row row-flex">
                   <div class="col-lg-12">
                      <div class="card">
                         <div class="card-body">
                            <div class="row">
                                <div class="col-lg-1">
                                    <h5 class="font-size-18 mb-0 report_card_title"><i class="bx bx-slider-alt"></i>  {{__('Sort')}}</h5>
                                </div>

                                <div class="col-lg-11">
                                   <form action="" method="get" class="report_forms">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <input class="form-control" type="text" name="q" placeholder="number, name, model" value="{{$q}}">
                                                        <svg class="field_icon" width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9.58317 17.4998C13.9554 17.4998 17.4998 13.9554 17.4998 9.58317C17.4998 5.21092 13.9554 1.6665 9.58317 1.6665C5.21092 1.6665 1.6665 5.21092 1.6665 9.58317C1.6665 13.9554 5.21092 17.4998 9.58317 17.4998Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M18.3332 18.3332L16.6665 16.6665" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <select class="form-select" name="worker_id" style="width: 100%;">
                                                            <option value="">{{__('Worker')}}</option>
                                                            @foreach ($workers as $worker)
                                                                <option value="{{ $worker->id }}" {{ (isset($toolWorkerId) && $toolWorkerId == $worker->id) ? 'selected="selected"' : '' }}>{{ $worker->fullName() }}</option>
                                                            @endforeach
                                                        </select>
                                                        <svg class="field_icon" width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10 11C12.7615 11 15 8.76142 15 6C15 3.23858 12.7615 1 10 1C7.23861 1 5.00003 3.23858 5.00003 6C5.00003 8.76142 7.23861 11 10 11Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M18.59 21C18.59 17.13 14.74 14 10 14C5.26003 14 1.41003 17.13 1.41003 21" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <select class="form-select" name="storage_id" style="width: 100%;">
                                                            <option value="">{{__('Storage')}}</option>
                                                            @foreach ($storages as $storage)
                                                                <option value="{{ $storage->id }}" {{ (isset($toolStorageId) && $toolStorageId == $storage->id) ? 'selected="selected"' : '' }}>{{ $storage->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <svg class="field_icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15.0333 11.2917C14.6833 11.6334 14.4833 12.125 14.5333 12.65C14.6083 13.55 15.4333 14.2084 16.3333 14.2084H17.9167V15.2C17.9167 16.925 16.5083 18.3334 14.7833 18.3334H5.21667C3.49167 18.3334 2.08333 16.925 2.08333 15.2V9.59172C2.08333 7.86672 3.49167 6.45837 5.21667 6.45837H14.7833C16.5083 6.45837 17.9167 7.86672 17.9167 9.59172V10.7917H16.2333C15.7667 10.7917 15.3417 10.975 15.0333 11.2917Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M2.08333 10.3417V6.53342C2.08333 5.54175 2.69167 4.65838 3.61667 4.30838L10.2333 1.80838C11.2667 1.41672 12.375 2.18341 12.375 3.29174V6.4584" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M18.799 11.6418V13.3586C18.799 13.8169 18.4323 14.1918 17.9657 14.2085H16.3323C15.4323 14.2085 14.6073 13.5502 14.5323 12.6502C14.4823 12.1252 14.6823 11.6335 15.0323 11.2918C15.3407 10.9752 15.7657 10.7919 16.2323 10.7919H17.9657C18.4323 10.8085 18.799 11.1835 18.799 11.6418Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M5.83333 10H11.6667" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>


                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <select class="form-select" name="category_id" style="width: 100%;">
                                                            <option value="">{{__('Category')}}</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}" {{ (isset($toolCategoryId) && $toolCategoryId == $category->id) ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <svg class="field_icon" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M19.2042 13.3375L13.3467 19.1951C12.0634 20.4784 9.95504 20.4784 8.66254 19.1951L2.80503 13.3375C1.5217 12.0542 1.5217 9.94589 2.80503 8.65339L8.66254 2.79587C9.94588 1.51254 12.0542 1.51254 13.3467 2.79587L19.2042 8.65339C20.4875 9.94589 20.4875 12.0542 19.2042 13.3375Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M5.72919 5.72913L16.2709 16.2708" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16.2709 5.72913L5.72919 16.2708" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <select class="form-select" name="status_id" style="width: 100%;">
                                                            <option value="all">All</option>
                                                            @foreach ($statuses as $status)
                                                                <option value="{{ $status->id }}" {{ (isset($toolStatusId) && $toolStatusId == $status->id) ? 'selected="selected"' : '' }} >{{ ucfirst($status->name) }}</option>
                                                            @endforeach
                                                        </select>
                                                        <svg class="field_icon" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.32641 15.5956C5.97557 14.899 6.96516 14.9544 7.53516 15.7144L8.33474 16.7831C8.97599 17.6302 10.0131 17.6302 10.6543 16.7831L11.4539 15.7144C12.0239 14.9544 13.0135 14.899 13.6627 15.5956C15.0718 17.0998 16.2197 16.601 16.2197 14.4952V5.57313C16.2197 2.38271 15.4756 1.58313 12.4831 1.58313H6.49808C3.50558 1.58313 2.76141 2.38271 2.76141 5.57313V14.4873C2.76933 16.601 3.92516 17.0919 5.32641 15.5956Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M7.32306 7.91687H11.6772" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <select class="form-select-inventorization" name="need_inventorization" style="width: 100%;">
                                                            <option></option>
                                                            <option value="all"@if (isset($toolNeedInventorization) && $toolNeedInventorization == "all" ) selected @endif>{{__('All')}}</option>
                                                            <option value="0" {{ (isset($toolNeedInventorization) && $toolNeedInventorization == "0") ? 'selected="selected"' : '' }}>{{__('Balanced')}}</option>
                                                            <option value="1" {{ (isset($toolNeedInventorization) && $toolNeedInventorization == "1") ? 'selected="selected"' : '' }}>{{__('Not balanced')}}</option>
                                                        </select>
                                                        <svg class="field_icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15.0333 11.2917C14.6833 11.6334 14.4833 12.125 14.5333 12.65C14.6083 13.55 15.4333 14.2084 16.3333 14.2084H17.9167V15.2C17.9167 16.925 16.5083 18.3334 14.7833 18.3334H5.21667C3.49167 18.3334 2.08333 16.925 2.08333 15.2V9.59172C2.08333 7.86672 3.49167 6.45837 5.21667 6.45837H14.7833C16.5083 6.45837 17.9167 7.86672 17.9167 9.59172V10.7917H16.2333C15.7667 10.7917 15.3417 10.975 15.0333 11.2917Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M2.08333 10.3417V6.53342C2.08333 5.54175 2.69167 4.65838 3.61667 4.30838L10.2333 1.80838C11.2667 1.41672 12.375 2.18341 12.375 3.29174V6.4584" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M18.799 11.6418V13.3586C18.799 13.8169 18.4323 14.1918 17.9657 14.2085H16.3323C15.4323 14.2085 14.6073 13.5502 14.5323 12.6502C14.4823 12.1252 14.6823 11.6335 15.0323 11.2918C15.3407 10.9752 15.7657 10.7919 16.2323 10.7919H17.9657C18.4323 10.8085 18.799 11.1835 18.799 11.6418Z" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M5.83333 10H11.6667" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-info-show w-md">{{__('Search')}}</button>
                                                    <button type="button" class="btn custom_rest_btn">
                                                       <a href="{{ url()->current() }}" class="">{{__('Reset')}}</a>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                   </div>
                                 </div>
                              </div>
                            </div>
                        </div>
    <!-- Filters End-->

     <!-- Entries --> 
                <div class="row">
                   <div class="col-lg-12">
                      <div class="card">
                         <div class="card-body">
                           
                            <div class="row">
                                <div class="col-md-6">
                                  <h4 class="report_card_title">{{__('Tool report')}}</h4>
                                </div>
                                <div class="col-md-6">

                                  <form class="row gy-2 gx-3 float-end report_forms ">
                                      <div class="col-sm-auto mb-3">
                                           <div class="btn-group" style="float: right;">
                                                <button type="button" class="btn btn-info-show dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">+ {{__('Add tool')}} <i class="mdi mdi-chevron-down"></i></button>
                                                <div class="dropdown-menu" style="">
                                                    <a class="dropdown-item" href="{{ route('tools.create') }}">{{__('Add New Tool')}}</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="{{ route('scan', ['redirect' => route('tools.create')]) }}">{{__('Scan QR Code')}}</a>
                                                </div>
                                            </div>
                                      </div>
                                  </form>

                                </div>  
                          </div>

                            <div class="table-responsive">
                               <table id="datatable" class="table table-nowrap mb-50  align-middle  w-100">
                                  <thead class="table-light-color"> 
                                     <tr>
                                        <!-- <th style="width: 20px;">
                                           <div class="form-check font-size-16 align-middle">
                                              <input class="form-check-input" type="checkbox" id="transactionCheck01">
                                              <label class="form-check-label" for="transactionCheck01"></label>
                                           </div>
                                        </th> -->
                                        <th >{{__('Id')}}</th>
                                        <th >{{__('Picture')}}</th>
                                        <th >{{__('Name & Model')}}</th>
                                        <th >{{__('Tool location')}}</th>
                                        <th >{{__('Price')}}</th>
                                        <th >{{__('Date added')}}</th>
                                        <th >{{__('Last activity')}}</th>
                                        <th style="text-align: right;">{{__('Action')}}</th>
                                     </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($tools as $tool)
                                     <tr>
                                        <!-- <td>
                                           <div class="form-check font-size-16">
                                              <input class="form-check-input" type="checkbox" id="transactionCheck02">
                                              <label class="form-check-label" for="transactionCheck02"></label>
                                           </div>
                                        </td> -->
                                        <td><p class="rep_heading">{{ $tool->company_tool_id }}</p></td>
                                        <td>
                                            @if ($tool->images)
                                            <a class="image-popup-no-margins" href="{{ Storage::url($tool->images[0]) }}">
                                                <img src="{{ Storage::url($tool->images[0]) }}" class="worker-image">
                                            </a>
                                            @else
                                                 <img src="/img/tool-default.jpg" class="worker-image">
                                            @endif 
                                        </td>

                                        <td><p class="rep_heading">{{ $tool->name }}</p><span class="line_down">{{ $tool->model }}</span></td>
                                        <td>@if ($tool->possessor)
                                    @if ($tool->possessor::class === \App\Models\Worker::class)
                                        <p class="rep_heading">
                                            <span class="bx bx-user"></span>
                                    {{ $tool->possessor->fullName() }}
                                        </p>
                                    @elseif ($tool->possessor::class === \App\Models\Storage::class)
                                        <p class="rep_heading">
                                            <svg class="me-1" width="19" height="19" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.4583 21.9167H4.125C2.04167 21.9167 1 20.875 1 18.7917V10.4584C1 8.37504 2.04167 7.33337 4.125 7.33337H9.33333V18.7917C9.33333 20.875 10.375 21.9167 12.4583 21.9167Z" stroke="#001B34" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M9.4479 3.16675C9.36457 3.47925 9.33333 3.823 9.33333 4.20841V7.33341H4.125V5.25008C4.125 4.10425 5.0625 3.16675 6.20833 3.16675H9.4479Z" stroke="#001B34" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M13.5 7.33337V12.5417" stroke="#001B34" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M17.6667 7.33337V12.5417" stroke="#001B34" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16.625 16.7084H14.5417C13.9687 16.7084 13.5 17.1771 13.5 17.75V21.9167H17.6667V17.75C17.6667 17.1771 17.1979 16.7084 16.625 16.7084Z" stroke="#001B34" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M5.16667 12.5417V16.7084" stroke="#001B34" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M9.33333 18.7917V4.20837C9.33333 2.12504 10.375 1.08337 12.4583 1.08337H18.7083C20.7917 1.08337 21.8333 2.12504 21.8333 4.20837V18.7917C21.8333 20.875 20.7917 21.9167 18.7083 21.9167H12.4583C10.375 21.9167 9.33333 20.875 9.33333 18.7917Z" stroke="#001B34" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>{{ $tool->possessor->name }}
                                        </p>
                                    @else
                                        <?php throw new \Exception(); ?>
                                    @endif

                                    @if ($tool->showUnbalanced())
                                        <span class="badge badge-soft-danger custom_bdg_notbalanced font-size-11">{{__('Not balanced')}}</span>
                                    @elseif ($tool->showBalanced())
                                        <span class="badge badge-soft-success custom_bdg_balanced font-size-11">{{__('Balanced')}}</span>
                                    @endif

                                    @if ($tool->status->name !== 'operational')
                                        <?php
                                        $colors = [
                                            'operational' => 'success',
                                            'in service' => 'warning',
                                            'broken' => 'danger',
                                            'lost' => 'warning',
                                            'decommissioned' => 'danger',
                                        ];
                                        ?>
                                        <span class="badge badge-soft-{{ $colors[$tool->status->name] }} font-size-11 status_bagde">{{ $tool->status->name }}</span>
                                    @endif
                                    @endif
                                    </td>
                                        <td><p class="rep_heading">{{ $tool->price }}</p></td>
                                        <td>
                                            <p class="rep_heading">
                                                @if ($tool->purchased_at)
                                                {{ $tool->purchased_at->format(dateFormat()) }}<br>
                                                @endif
                                            </p>
                                        </td>
                                        <td><p class="rep_heading">{{ $tool->updated_at->format(dateFormat()) }}</p></td>

                                        <td class="action align-middle" style="text-align:right;"> 
                                            <div class="entries_action">
                                              <!-- <div class="avatar-xs me-3 report_smry_iconsize">
                                                   <span class=" font-size-15" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg-{{ $tool->company_tool_id }}">
                                                        <span class="avatar-title rounded-circle bg-white bg-soft text-dark font-size-15">
                                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9.15981 10.87C9.05981 10.86 8.93981 10.86 8.82981 10.87C6.44981 10.79 4.55981 8.84 4.55981 6.44C4.55981 3.99 6.53981 2 8.99981 2C11.4498 2 13.4398 3.99 13.4398 6.44C13.4298 8.84 11.5398 10.79 9.15981 10.87Z" stroke="#FFA113" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M16.4098 4C18.3498 4 19.9098 5.57 19.9098 7.5C19.9098 9.39 18.4098 10.93 16.5398 11C16.4598 10.99 16.3698 10.99 16.2798 11" stroke="#FFA113" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M4.15991 14.56C1.73991 16.18 1.73991 18.82 4.15991 20.43C6.90991 22.27 11.4199 22.27 14.1699 20.43C16.5899 18.81 16.5899 16.17 14.1699 14.56C11.4299 12.73 6.91991 12.73 4.15991 14.56Z" stroke="#FFA113" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M18.34 20C19.06 19.85 19.74 19.56 20.3 19.13C21.86 17.96 21.86 16.03 20.3 14.86C19.75 14.44 19.08 14.16 18.37 14" stroke="#FFA113" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        </span>
                                                    </span>
                                              </div> -->
                                              <div class="avatar-xs me-3 report_smry_iconsize">
                                                 <a target="_blank" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg-{{ $tool->company_tool_id }}"  title="info">
                                                    <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-15">
                                                        <i class="fas fa-info"></i>
                                                    </span>
                                                 </a>
                                              </div>
                                              <div class="avatar-xs me-3 report_smry_iconsize">
                                                 <a target="_blank" data-bs-toggle="modal" data-bs-target=".transfer-tool-modal" data-id="{{$tool->publicId()}}" title="transfer" class="transfer">
                                                    <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-15">
                                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9.15981 10.87C9.05981 10.86 8.93981 10.86 8.82981 10.87C6.44981 10.79 4.55981 8.84 4.55981 6.44C4.55981 3.99 6.53981 2 8.99981 2C11.4498 2 13.4398 3.99 13.4398 6.44C13.4298 8.84 11.5398 10.79 9.15981 10.87Z" stroke="#FFA113" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M16.4098 4C18.3498 4 19.9098 5.57 19.9098 7.5C19.9098 9.39 18.4098 10.93 16.5398 11C16.4598 10.99 16.3698 10.99 16.2798 11" stroke="#FFA113" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M4.15991 14.56C1.73991 16.18 1.73991 18.82 4.15991 20.43C6.90991 22.27 11.4199 22.27 14.1699 20.43C16.5899 18.81 16.5899 16.17 14.1699 14.56C11.4299 12.73 6.91991 12.73 4.15991 14.56Z" stroke="#FFA113" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M18.34 20C19.06 19.85 19.74 19.56 20.3 19.13C21.86 17.96 21.86 16.03 20.3 14.86C19.75 14.44 19.08 14.16 18.37 14" stroke="#FFA113" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </span>
                                                 </a>
                                              </div>
                                             <!-- <div class="avatar-xs me-3 report_smry_iconsize">
                                                 <a href="{{ route('tools.duplicate', $tool->publicId()) }}" title="duplicate">
                                                    <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M16 12.9V17.1C16 20.6 14.6 22 11.1 22H6.9C3.4 22 2 20.6 2 17.1V12.9C2 9.4 3.4 8 6.9 8H11.1C14.6 8 16 9.4 16 12.9Z" stroke="#2F45C5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M22 6.9V11.1C22 14.6 20.6 16 17.1 16H16V12.9C16 9.4 14.6 8 11.1 8H8V6.9C8 3.4 9.4 2 12.9 2H17.1C20.6 2 22 3.4 22 6.9Z" stroke="#2F45C5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>

                                                    </span>
                                                 </a>
                                              </div> -->
                                              <div class="avatar-xs me-3 report_smry_iconsize">
                                                <a href="{{ route('tools.edit', $tool->publicId()) }}" title="edit">
                                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-15">
                                                    <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.1 1H7.3C2.8 1 1 2.8 1 7.3V12.7C1 17.2 2.8 19 7.3 19H12.7C17.2 19 19 17.2 19 12.7V10.9" stroke="#2F45C5" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M14.2195 1.9785L8.15489 8.04398C7.92401 8.2749 7.69312 8.72904 7.64694 9.06003L7.31601 11.3769C7.19287 12.2159 7.78547 12.8009 8.62436 12.6855L10.9409 12.3545C11.2642 12.3083 11.7182 12.0774 11.9568 11.8465L18.0214 5.78097C19.0681 4.73414 19.5607 3.51797 18.0214 1.9785C16.4822 0.439043 15.2662 0.93167 14.2195 1.9785Z" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M13.6001 2.80005C14.0884 4.54175 15.4511 5.9045 17.2001 6.40005" stroke="#2F45C5" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                                </a>
                                            </div>
                                             
                                              <!-- <div class="avatar-xs me-3 report_smry_iconsize" style="cursor: pointer;">
                                                    <span class=" font-size-15" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg-{{ $tool->company_tool_id }}">
                                                        <span class="avatar-title rounded-circle bg-white bg-soft text-dark font-size-15">
                                                            <i class="bx bx-chevron-down "></i>
                                                        </span>
                                                    </span>
                                              </div> -->

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
                                                        <a class="dropdown-item tool-history-link" data-url="{{ route('tools.histories.index', $tool->publicId()) }}">{{__('History')}}</a>
                                                        <a class="dropdown-item" href="{{ route('tools.change-status', $tool->publicId()) }}">{{__('Change status')}}</a>
                                                        <a class="dropdown-item" href="{{ $tool->qrLink() }}">{{__('QR code')}}</a>
                                                        <!-- <a class="dropdown-item" href="{{ route('tools.edit', $tool->publicId()) }}">Edit</a> -->
                                                        <a class="dropdown-item" href="{{ route('tools.duplicate', $tool->publicId()) }}">{{__('Duplicate')}}</a>
                                                        <a class="dropdown-item" data-method="delete" data-confirm="{{__('Are you sure?')}}" href="{{ route('tools.destroy', $tool->publicId()) }}">{{__('Delete')}}</a>
                                                    </span>
                                                  </span>
                                              </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <div class="modal fade bs-example-modal-lg-{{ $tool->company_tool_id }}" tabindex="-1" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <span class="modal-title" id="myExtraLargeModalLabel2">{{__('Tool details')}}</span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{__('Close')}}"></button>
                                                </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-sm-auto">
                                                            @if(isset($tool->category))
                                                                <p class="rep_heading">{{__('Cateory')}}: {{ $tool->category->name }}</p>
                                                            @endif
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                        <div class="col-sm-auto">
                                                                <p class="rep_heading">{{__('Inventoried')}}: {{ $tool->inventoried_at }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                        <div class="col-sm-auto">
                                                                <p class="rep_heading">{{__('Serial number')}}: {{ $tool->serial }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                        <div class="col-sm-auto">
                                                            <p class="rep_heading">{{__('Next inventorization')}}: {{ $tool->next_inventorization_at }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                        <div class="col-sm-auto">
                                                                <p class="rep_heading">{{__('Last changed')}}: {{ $tool->updated_at }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     @endforeach
                                  </tbody>
                               </table>
                                {{ $tools->withQueryString()->links() }}
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
    <!-- Entries End -->
    </div>
@endsection

@section('scripts')
    @parent
    <script src="/skote/assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="/skote/assets/js/pages/lightbox.init.js"></script>



    <script>
        $('#datatable').dataTable({
   paging: false,
   aaSorting: []
});
     
    $(".transfer").on('click', function(){
        $.ajax({
                url: '/transfer/'+$(this).data('id'),
                type: 'GET',
                success: function(data) {
                // alert(data);

                $("#transfer-tool-content").html(data);

                },
                error: function(err) {
                    console.log(err);
                    alert('error');
                }
            });    
        });
        
        $(".tool-history-link").on('click', function(){
            let url = $(this).data('url');

            $.ajax({
              url: url,
              type: 'GET',
              success: function(data) {
                $("#tool-history-content").html(data);
                $(".tool-history-modal").modal('show');
              },
              error: function(err) {
                  console.log(err);
                  alert('error');
                }
            });      
        });
    </script>
@endsection