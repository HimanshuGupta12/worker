@extends('layouts.worker')

@section('header_content')
    @parent

    <link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row page_headingapp">
            <div class="col-1" style="text-align: left;">
                <a href="{{ url()->previous() }}" class="trgr_ovrly">
                    <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
            <div class="col-10">
                <h4 class="mb-0 page_heading">{{ __("Scan to storage") }}</h4>
            </div>
            <div class="col-1" style="text-align: right;"></div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                            
                            <div class="card-title mb-4">{{ __("Choose storage") }}</div>
                            <form action="{{ route('worker.tools.scan-to-storage1') }}" method="post">
                                @csrf
                                <div class="row">
                                    
                                    <div class="col-12">
                                        <label class="form-label custom_form_label">{{ __("Storage") }}</label>
                                        @foreach ($storages as $storage)
                                        <div class="form-check mb-3">
                                            <input id="{{ $storage->id }}" class="form-check-input" type="radio" name="storage_id"  value="{{ $storage->id }}">
                                            <label for="{{ $storage->id }}" class="form-check-label">{{ $storage->name }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <button class="btn btn-primary mt-3 mx-auto d-block js-disable">{{ __("Choose") }}</button>
                            </form>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div style="height: 20px;"></div>
@endsection
