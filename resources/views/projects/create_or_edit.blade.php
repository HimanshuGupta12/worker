@extends('layouts.user')


@section('content')

    @php

        $workerInfoSubmitUrl = $url;
    @endphp
    @include('projects.the_form')

@endsection






