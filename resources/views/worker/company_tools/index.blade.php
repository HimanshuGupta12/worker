@extends('layouts.worker')

@section('head')
    @parent
        <link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />

    <link href="/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container-fluid">
            <div class="row page_headingapp">
                <div class="col-1">
                    <a href="{{ url()->previous() }}" class="trgr_ovrly">
                        <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <div class="col-10">
                    <h4 class="mb-0 page_heading">Company's tools</h4> 
                </div>
                <div class="col-1"></div>
            </div>

        <div class="row">
            <div class="col-md-12">
                <div class="mb-3 mt-3" style="text-align: center;">
                    <button class="btn btn-secondary" data-bs-toggle="collapse" href="#search"><i class="mdi mdi-magnify"></i> {{ __('Search') }}</button>
                    <a class="btn btn-primary" href="{{ route('scan', ['redirect' => route('worker.company-tools.index')]) }}" title="{{ __('Scan QR to add tool to your list or to find a tool in your list')}} ">{{ __('Scan QR') }}</a>
                </div>

                <div class="card collapse" id="search">
                    <div class="card-body">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label>{{ __('Search') }}</label>
                                        <input class="form-control" type="text" name="q" placeholder="number, name, model" value="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label>{{ __('Worker') }}</label>
                                        <select class="form-select" name="worker_id" style="width: 100%;">
                                            <option value="">&nbsp;</option>
                                            @foreach ($workers as $worker)
                                                <option value="{{ $worker->id }}">{{ $worker->fullName() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label>{{ __('Storage') }}</label>
                                        <select class="form-select" name="storage_id" style="width: 100%;">
                                            <option value="">&nbsp;</option>
                                            @foreach ($storages as $storage)
                                                <option value="{{ $storage->id }}">{{ $storage->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label>{{ __('Category') }}</label>
                                        <select class="form-select" name="category_id" style="width: 100%;">
                                            <option value="">&nbsp;</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label>{{ __('Balancing') }}</label>
                                        <select class="form-select" name="need_inventorization" style="width: 100%;">
                                            <option value="">&nbsp;</option>
                                            <option value="0" >{{ __('Balanced') }}</option>
                                            <option value="1">{{ __('Not balanced') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button class="btn btn-primary">{{ __('Filter') }}</button>
                                        <a href="{{ url()->current() }}" class="btn btn-secondary">{{ __('Reset') }}</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>



                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">{{ __("Company's tools") }}</div>
                        <div class="table-responsive">
                            <table class="table table-stripped table-hover">
                                <tr>
                                    <th></th>
                                    <th>Info</th>
                                    <th></th>
                                </tr>
                                @foreach ($tools as $tool)
                                    <tr>
                                        <td>
                                            @if ($tool->images)
                                                <a class="image-popup-no-margins" href="{{ Storage::url($tool->images[0]) }}">
                                                    <img class="avatar-md" alt="" src="{{ Storage::url($tool->images[0]) }}">
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @include('tools.partial.info', compact('tool'))
                                        </td>
                                        <td style="text-align: right;">
                                            <span class="dropdown">
                                                <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-horizontal font-size-24"></i>
                                                </a>
                                                <span class="dropdown-menu dropdown-menu-end">
                                                    @if (worker()->change_tool_status)
                                                        <a class="dropdown-item" href="{{ route('worker.company-tools.change-status', $tool->publicId()) }}">{{ __("Change status") }}</a>
                                                    @endif
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                {{ $tools->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="/skote/assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="/skote/assets/js/pages/lightbox.init.js"></script>
@endsection
