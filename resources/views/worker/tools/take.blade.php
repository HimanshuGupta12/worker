@extends('layouts.worker')

@section('header_content')
    @parent

      <style type="text/css">
        
        .take-tool-tbl tr{

            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #F2F3F4
        }

        .text-truncate.take-tool-headelemnet {
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
            color: #667685;
            font-size: 14px;
        }


    </style>

    <link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />



    <?php
    $title = $tool->name;
    if ($tool->model) {
        $title .= ' // ' . $tool->model;
    }
    ?>
    

@endsection

@section('content')


<div class="container-fluid">

    <div class="row page_headingapp">
        <div class="col-4" style="text-align: left;">
            <a href="{{ url()->previous() }}">
                <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
        <div class="col-4">
            <h4 class="mb-0 page_heading">{{ __("Take tools") }}</h4> 
        </div>
        <div class="col-4" style="text-align: right;">@include('layouts.partial.worker_header_elements.button', ['url' => route('scan', ['redirect' => route('worker.tools.take')]), 'text' => __('Scan again')])</div>
    </div>

    @include('layouts.partial.worker_header_elements.single_line_text', ['text' => $title, 'tool_id' => $tool->company_tool_id])
    <div class="row">
        <div class="col-12">
            <table class="table table-sm table-nowrap align-middle table-borderless take-tool-tbl">
                <tr>
                    <td>
                        @if ($tool->images)
                            <a class="image-popup-no-margins" href="{{ Storage::url($tool->images[0]) }}">
                                <img class="avatar-md" alt="" src="{{ Storage::url($tool->images[0]) }}" style="border-radius: 8px;">
                            </a>
                        @endif
                    </td>
                    <td style="width: 15px;">
                        <div style="width: 15px;"></div>
                    </td>
                    <td>
                        <i class="mdi mdi-home-account" style="font-size: 16px;"></i> {{ __("Current owner") }}<br>
                        @if ($tool->possessor)
                            @if ($tool->possessor::class === \App\Models\Worker::class)
                                <span class="text-truncate" style="font-weight: bold;">{{ $tool->possessor->fullName() }}</span>
                            @elseif ($tool->possessor::class === \App\Models\Storage::class)
                                <span class="text-truncate" style="font-weight: bold;">{{ $tool->possessor->name }}</span>
                            @else
                                <?php throw new \Exception(); ?>
                            @endif
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="container-fluid mt-5" >
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('worker.tools.take-post') }}" method="post">
                @csrf
                <input class="form-control" type="hidden" name="tool_id" value="{{ $tool->publicId() }}">
                <button style="padding: 6px 0; background: #e82a57; border: none; color: #fff; width: 100%; border-radius: 18px; font-size: 16px;">{{ __("Take tool") }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
