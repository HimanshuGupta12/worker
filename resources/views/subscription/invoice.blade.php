@extends('layouts.user')

@section('head')
    <meta name="_token" content="{{ csrf_token() }}">
    @parent
    
    <!--<link href="{{ URL::asset('css/worker-reports.css') }}" rel="stylesheet" type="text/css" />-->  
    <link href="{{ env('PUBLIC_PATH') }}/css/worker-reports.css" rel="stylesheet" type="text/css" />
    <style type="text/css">

 
    input , textarea , select {
        width: 100% !important;
    }
    
    </style>
@endsection


@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <h4 class="report_card_title">Invoices</h4>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Billing reason</th>
                                        <th class="text-center" width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(!empty($invoices))
                                    @foreach($invoices as $s => $invoice)
                                        @if($invoice->billing_reason != 'subscription_create')
                                            <tr>
                                                <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                                                <td>{{ $invoice->total() }}</td>
                                                <td>{{ strtoupper($invoice->status) }}</td>
                                                <td>{{ ucwords(str_replace('_', ' ', $invoice->billing_reason)) }}</td>
                                                <td class="text-center">
                                                    <a target="_blank" href="{{ $invoice->hosted_invoice_url }}" class="btn btn-outline-info btn-sm" title="View Details">
                                                        <i class="bx bx-show-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
