<!doctype html>
<html lang="en">
<head>
<!-- PWA  -->
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ url('/img/custom_icon72x72.png') }}">
    <link rel="manifest" href="{{$manifest_url}}">
     <link href="{{ env('PUBLIC_PATH') }}/css/worker.hoursapp.css" rel="stylesheet" type="text/css" />
       <link rel="icon" href="favicon.ico" type="image/x-icon" />
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <meta name="theme-color" content="white"/>
	  <meta name="apple-mobile-web-app-capable" content="yes">
	  <meta name="apple-mobile-web-app-status-bar-style" content="black">
	  <meta name="apple-mobile-web-app-status-bar" content="#FFFFFF">
	  <meta name="apple-mobile-web-app-title" content="Worker App">
	  <meta name="msapplication-TileImage" content="/img/custom_icon72x72.png">
	  <meta name="msapplication-TileColor" content="#FFFFFF">
    <script
        src="https://browser.sentry-cdn.com/6.19.7/bundle.tracing.min.js"
        integrity="sha384-lEBvyPG01tsv1WahtwAQevkCCh3NjqUNlz93IATGFmFuVbVyx6dReoQ035OOjNsM"
        crossorigin="anonymous"
    ></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 

    <title>PWA App</title> 
    <style>
        body {
            background-color: #f7faff;
        }

 #ios-page {


  color: #ffffff;
  background-color: #2ecc71;
  bottom: 0;
  position: absolute;
  border: none;
  border-radius: 6px;


  box-shadow: 2px 7px 96px -2px rgba(154,171,237,1);
  -webkit-box-shadow: 2px 7px 96px -2px rgba(154,171,237,1);
  -moz-box-shadow: 2px 7px 96px -2px rgba(154,171,237,1);
 }
	#install {
            align-items: center;
            text-align: center;
  color: #ffffff;
  background-color: #2ecc71;
  border: none;
  border-radius: 6px;
  padding: 12px 20px;
  font-size: 20px;
  font-weight: 500;
  line-height: 22px;
  display: flex;
  margin-left: auto;
  margin-right: auto;
  cursor: pointer;

  box-shadow: 2px 7px 96px -2px rgba(154,171,237,1);
  -webkit-box-shadow: 2px 7px 96px -2px rgba(154,171,237,1);
  -moz-box-shadow: 2px 7px 96px -2px rgba(154,171,237,1);
}
</style>
 
</head>
<body>

<div>
	<br/>
	<br/>
	<br/>
    @if($android)
	<button id="install" class="installer">Install The App</button>
    @elseif($ios)
    <div style="align-items: center; text-align: center;">
        <div id="ios-page">
            <h1 class="">Install</h1>
            <p>Save your booking for quick and easy access on the go.</p>
            <p>Tap 
<svg style="height: 20px;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
<metadata> Svg Vector Icons : http://www.onlinewebfonts.com/icon </metadata>
<g><path d="M780,290H640v35h140c19.3,0,35,15.7,35,35v560c0,19.3-15.7,35-35,35H220c-19.2,0-35-15.7-35-35V360c0-19.2,15.7-35,35-35h140v-35H220c-38.7,0-70,31.3-70,70v560c0,38.7,31.3,70,70,70h560c38.7,0,70-31.3,70-70V360C850,321.3,818.7,290,780,290z M372.5,180l110-110.2v552.7c0,9.6,7.9,17.5,17.5,17.5c9.6,0,17.5-7.9,17.5-17.5V69.8l110,110c3.5,3.5,7.9,5,12.5,5s9-1.7,12.5-5c6.8-6.8,6.8-17.9,0-24.7l-140-140c-6.8-6.8-17.9-6.8-24.7,0l-140,140c-6.8,6.8-6.8,17.9,0,24.7C354.5,186.8,365.5,186.8,372.5,180z"/></g>
</svg> then 'Add to Home Screen'</p>
        </div>
    </div>
    @endif



</div>

<script type="text/javascript" src="{{url('/workers.js')}}"></script>
</body>

</html>