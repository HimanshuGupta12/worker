@extends('layouts.worker')

@section('content')
    <div class="container-fluid">
        <div class="row page_headingapp">
            <div class="col-1" id="normal-back">
                <a href="{{ url()->previous() }}">
                <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                </a>
            </div>
            <div class="col-1" id="steps-back" style="display: none;">
                <a id="previous-steps" role="menuitem">
                <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                </a>
            </div>
            <div class="col-10">
                <h4 class="mb-0 submisn_heading step_heading">{{ __('Balance storage') }}</h4> 
            </div>
            <div class="col-1"></div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">{{ __("Inventory storage") }}</div>
                        <form action="{{ route('scan') }}" method="get">
                            <input type="hidden" name="redirect" value="/worker/tools/inventorize/" id="redirect">
                            <input type="hidden" name="back" value="{{ route('worker') }}">
                            <div class="mb-3">
                                <label>{{ __("Storage") }}</label>
                                <select class="form-select" id="storage_id">
                                    @foreach ($storages as $storage)
                                        <option value="{{ $storage->id }}">{{ $storage->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="btn btn-primary mt-3 mx-auto d-block js-disable" id="choose">{{ __("Choose") }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            /*$('#choose').click(function (e) {
                var storage_id = $('#storage_id').val();
                var redirect = $('#redirect').val();
                $('#redirect').val(redirect + storage_id);
            });*/
        });
    </script>
@endsection
