@extends('layouts.worker')

@section('content')
<link href="{{ env('PUBLIC_PATH') }}/css/worker-form.css" rel="stylesheet" type="text/css" />

    <div class="container-fluid">
        <div class="row page_headingapp">
            <div class="col-1">
                <a href="{{ url()->previous() }}">
                    <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
            <div class="col-10">
                <h4 class="mb-0 page_heading">{{ __("Change status") }}</h4>
            </div>
            <div class="col-1" style="text-align: right;"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">{{ __("Report a problem") }}</div>
                        <form action="{{ route('worker.tools.change-status', $tool->publicId()) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label>{{ __("What happened?") }}</label>
                                <select class="form-select" name="problem_type">
                                    <option value="">&nbsp;</option>
                                    @foreach (['broken', 'lost', 'in service'] as $type)
                                        <option value="{{ $type }}" @if (old('problem_type') === $type) selected @endif>{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>{{ __("Description") }}</label>
                                <textarea class="form-control" name="problem_description" style="height: 100px;" maxlength="255" placeholder="short description">{{ old('problem_description') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label>{{ __("Photo") }}</label>
                                <input class="form-control" type="file" name="photo" accept="image/jpeg,image/png">
                            </div>
                            <button class="btn btn-primary mt-3 js-disable">{{ __("Report") }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
