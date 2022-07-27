@extends('layouts.worker')

@section('head')
    @parent
    <link href="/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />

    <style type="text/css">

        .project-list-table tr{

            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #F2F3F4
        }
    </style>
@endsection

@section('header_content')
    @parent

@endsection

@section('content')
    <?php
    $inventorization_url = route('worker.scan.inventory', ['redirect' => route('worker.tools.inventorize')]);
    ?>
    <div class="container-fluid">
             <div class="row page_headingapp">
                <div class="col-1">
                    <a href="{{ url('/worker?worker='.worker()->login) }}" class="trgr_ovrly">
                        <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <div class="col-10">
                    <h4 class="mb-0 page_heading">{{ __("My tools") }}</h4>
                </div>
                <div class="col-1"></div>
             </div>
                 <div style=" text-align: center;">
   <!--      <div class="font-size-20" style="color: #fff;">My tools</div> -->
        <div class="" style="color: #001B34;">{{ __("You have") }}: {{ $tools->count() }} {{ __("items") }} // {{ __("Value") }}: {{ number_format($value, 0, ',', '.') }},-</div>
    </div>

        <div class="row">
            <div class="col-md-12">

                <div class="mb-3 mt-4" style="text-align: center;">
                    <button class="btn btn-secondary apphot_links" data-bs-toggle="collapse" href="#search"><i class="mdi mdi-magnify"></i> {{ __("Search") }}</button>
                    <a class="btn btn-primary apphot_links" href="{{ route('scan', ['redirect' => route('worker.tools.take')]) }}"><i class="mdi mdi-barcode-scan"></i> {{ __("Take tool") }}</a>

                    @if ($show_balance)
                    <a class="btn btn-primary balance_hr apphot_links" style="background-color: #f46a6a !important; border-color: #f46a6a !important;" href="{{ $inventorization_url }}"><i class="fas fa-qrcode"></i> {{ __("Balance tools") }}</a>
                    @endif

                </div>

                <div class="collapse" id="search">
                    <div class="card">
                        <div class="card-body">
                            {{--                            <div class="card-title mb-4">Filter</div>--}}
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="">
                                            <label>{{ __("Search") }}</label>
                                            <input class="form-control" type="text" name="q" placeholder="number, name, model" value="">
                                        </div>
                                    </div>
                                    {{--                                    <div class="col-md-2">--}}
                                    {{--                                        <div class="mb-3">--}}
                                    {{--                                            <label>Status</label>--}}
                                    {{--                                            <select class="form-select" name="status" style="width: 100%">--}}
                                    {{--                                                <option value="">&nbsp;</option>--}}
                                    {{--                                                @foreach ($statuses as $status)--}}
                                    {{--                                                    <option value="{{ $status->id }}" @if (request('status_id') == $status->id) selected @endif>{{ ucfirst($status->name) }}</option>--}}
                                    {{--                                                @endforeach--}}
                                    {{--                                            </select>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <div class="col-md-2">--}}
                                    {{--                                        <div class="mb-3">--}}
                                    {{--                                            <label>Balancing</label>--}}
                                    {{--                                            <select class="form-select" name="need_inventorization" style="width: 100%">--}}
                                    {{--                                                <option value="">&nbsp;</option>--}}
                                    {{--                                                <option value="0" @if (request('need_inventorization') === '0') selected @endif>Balanced</option>--}}
                                    {{--                                                <option value="1" @if (request('need_inventorization') === '1') selected @endif>Not balanced</option>--}}
                                    {{--                                            </select>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    <div class="col-md-2">
                                        <div class="">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button class="btn btn-outline-primary"><span class="fas fa-search"></span> {{ __("Search") }}</button>
                                                <a href="{{ url()->current() }}" class="btn btn-outline-light">{{ __("Reset") }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <table class="table table-sm project-list-table table-nowrap align-middle table-borderless my_tool_wrkr_table">
                    <tbody>
                    @if ($tools->isEmpty())
                        <div class="mt-4" style="text-align: center;">
                            <h1>{{ __("No results") }}</h1>
                        </div>
                    @endif
                    @foreach ($tools as $tool)
                        <tr>
                            <td>
                                @if ($tool->images)
                                    <a class="image-popup-no-margins" href="{{ Storage::url($tool->images[0]) }}">
                                        <img class="avatar-md" alt="" src="{{ Storage::url($tool->images[0]) }}">
                                    </a>
                                @endif
                            </td>
                            <td class="text-truncate" style="max-width: 150px;">
                                <div>
                                    <span class="font-size-15" style="white-space: initial;">
                                        <span class="badge bg-secondary">{{ /*isset($tool->tool_code) ? explode('-', $tool->tool_code)[1] : $tool->company_tool_id*/ $tool->company_tool_id }}</span>
                                        <b>{{ $tool->name }}</b>
                                    </span>
                                </div>
                                <div>{{ $tool->model }}</div>
                                <div>{{ $tool->category?->name }}</div>
                                @if ($tool->price)
                                    <div>
                                        {{ __("Value") }}: {{ $tool->price }},-
                                    </div>
                                @endif
                                @if ($tool->next_inventorization_at)
                                    <div>{{ __("Next balancing") }}: {{ $tool->next_inventorization_at->format(dateFormat()) }}</div>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <span class="dropdown">
                                    <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal font-size-24"></i>
                                    </a>
                                    <span class="dropdown-menu dropdown-menu-end">
                                        @if ($tool->canWorkerReport())
                                            <a class="dropdown-item" href="{{ route('worker.tools.change-status', $tool->publicId()) }}">{{ __("Change status") }}</a>
                                        @endif
                                    </span>
                                </span>
                                <div style="height: 30px;"></div>

                                @if ($tool->showUnbalanced())
                                    <a class="badge badge-soft-danger">{{ __("Not balanced") }}</a>
                                @elseif ($tool->showBalanced())
                                    <span class="badge badge-soft-success">{{ __("Balanced") }}</span>
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
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
