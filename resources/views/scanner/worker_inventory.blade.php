@extends('layouts.worker')

@section('head')
<link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />
@endsection

@section('header_content')
    @parent
    @include('layouts.partial.worker_header_elements.single_line_text', ['text' => __('Balance tool')])
    @include('layouts.partial.worker_header_elements.button', ['url' => route('worker.tools.index', ['need_inventorization' => 1]), 'text' => __('Unbalanced tools')])
@endsection

@section('content')
    @include('scanner.partial', ['translations' => $translations])
@endsection
