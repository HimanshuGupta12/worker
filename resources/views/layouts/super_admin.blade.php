<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.partial.head')
    <title>@yield('title', 'Worker')</title>
    @yield('head')
</head>
<body data-sidebar="dark">
    <div id="layout-wrapper">
        @if (Auth::guard('super_admin')->check())
            @include('layouts.partial.super_admin_top_menu')
            @include('layouts.partial.super_admin_left_menu')
        @endif        
        <div class="main-content">
            <div class="page-content">
                @include('partial.messages')
                @yield('content')
            </div>
        </div>
    </div>
    @include('layouts.partial.scripts')
    @yield('scripts')
</body>
</html>
