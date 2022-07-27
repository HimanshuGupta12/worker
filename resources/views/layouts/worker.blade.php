<!doctype html>
<html lang="en">
<head>
<!-- PWA  -->
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="green">
<meta name="apple-mobile-web-app-title" content="worker">
<link rel="apple-touch-icon" href="{{ env('PUBLIC_PATH') }}/img/custom_icon72x72.png" sizes="72x72">
<link rel="apple-touch-icon" href="{{ env('PUBLIC_PATH') }}/img/custom_icon96x96.png" sizes="96x96">
<link rel="apple-touch-icon" href="{{ env('PUBLIC_PATH') }}/img/custom_icon128x128.png" sizes="128x128">
<link rel="apple-touch-icon" href="{{ env('PUBLIC_PATH') }}/img/custom_icon144x144.png" sizes="144x144">
<link rel="apple-touch-icon" href="{{ env('PUBLIC_PATH') }}/img/custom_icon152x152.png" sizes="152x152">
<link rel="apple-touch-icon" href="{{ env('PUBLIC_PATH') }}/img/custom_icon192x192.png" sizes="192x192">
<link rel="apple-touch-icon" href="{{ env('PUBLIC_PATH') }}/img/custom_icon384x384.png" sizes="384x384">
<link rel="apple-touch-icon" href="{{ env('PUBLIC_PATH') }}/img/custom_icon512x512.png" sizes="512x512">

<meta name="msapplication-TileImage" content="{{ env('PUBLIC_PATH') }}/img/custom_icon512x512.png"  sizes="512x512">
<meta name="msapplication-TileColor" content="green">

     <link href="{{ env('PUBLIC_PATH') }}/css/worker.hoursapp.css" rel="stylesheet" type="text/css" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.partial.head')
    <?php
            if(App\Models\Worker::isLoggedIn())
            {
                $title = 'Worker-'. worker()->company->name ;
            }else {
                $title = 'Worker';
            }
    ?>


    <title>@yield('title', $title )</title>
    @yield('head')
    <style>
        body {
            background-color: #f7faff;
        }
        .page-content {
            padding-top: 0;
        }
        select.lang_appdrpdwn {
            padding: 5px 19px 5px 27px;
            border-radius: 15px;
            border: 1px solid #F2F3F4;
            color: #667685;
            background: none;
        }

        .icon_lng_fld {
            position: absolute;
            top: 5px;
            left: 6px;
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
<body>
  <div class="" id="ovrly_wpr"></div>
  <div class="spinner-border text-light cstm_spineer" role="status" style="display: none;">
  </div>

    @yield('header', \View::make('layouts.partial.worker_header'))
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
<script type="text/javascript">
    if ('caches' in window) {
        caches.keys()
          .then(function(keyList) {
              return Promise.all(keyList.map(function(key) {
                  return caches.delete(key);
              }));
          })
      }
    $(document).ready(function(){
        setTimeout(function() {
            $('#successMessage').fadeOut('fast');
        }, 5000);
    });

    $(document).ready(function(){
        setTimeout(function() {
            $('#deletemessage').fadeOut('fast');
        }, 5000);
    });
</script>
</body>
</html>
