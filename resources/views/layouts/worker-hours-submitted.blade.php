<!doctype html>
<html lang="en" style="background: linear-gradient(167.38deg, #0CD1FD -0.88%, #3AE9B4 72.89%);">
<head>
    <link href="{{ env('PUBLIC_PATH') }}/css/worker.hoursapp.css" rel="stylesheet" type="text/css" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.partial.head')
    <title>@yield('title', 'Worker')</title>
    @yield('head')
    <style>
        .page-content {
            padding-top: 0;
        }
                .overlay {
          position: fixed;
          width: 100%;
          height: 100%;
          background: rgba(0,0,0,0.5);
          z-index: 999999;
      }
      .spinner-border.text-light.cstm_spineer {
        position: absolute;
        top: 50%;
        left: 46%;
        z-index: 999999999;
    }
    </style>
</head>
<body style="background: linear-gradient(167.38deg, #0CD1FD -0.88%, #3AE9B4 72.89%);">
<div class="" id="ovrly_wpr"></div>
  <div class="spinner-border text-light cstm_spineer" role="status" style="display: none;">
  </div>
    <!-- @yield('header', \View::make('layouts.partial.worker_header')) -->
    @yield('content')
    @include('layouts.partial.scripts')
    @yield('scripts')

    <script type="text/javascript">

    jQuery(document).ready(function() { 

        jQuery("#ovrly_wpr").removeClass("overlay");
        jQuery(".cstm_spineer").css("display","none");

        jQuery(".trgr_ovrly").click(function() {
        
            jQuery("#ovrly_wpr").addClass("overlay");
            jQuery(".cstm_spineer").css("display","block");
             setTimeout(function () {
                jQuery("#ovrly_wpr").removeClass("overlay");
                jQuery(".cstm_spineer").css("display","none");
            }, 1000);
        });
    });


    </script>
</body>
</html>
