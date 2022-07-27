@extends( Auth::check() ? 'layouts.user' : 'layouts.worker')

@section('head')
<link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />
@endsection

@section('header_content')
    @parent
    @include('layouts.partial.worker_header_elements.scan_tool_header', ['text' => __('Scan tool code')])
@endsection

@section('content')
    @include('scanner.partial', ['translations' => $translations])
@endsection
