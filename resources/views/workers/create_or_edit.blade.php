@extends('layouts.user')


@section('content')

    @php

        $workerInfoSubmitUrl = $url;
    @endphp
    @include('workers.the_form', [
        'doNotIncludeScripts' => true
    ])

@endsection



