@extends('layouts.worker')
@section('head')
<meta name="_token" content="{{ csrf_token() }}">
@parent
<link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />

<link href="{{ env('PUBLIC_PATH') }}/css/worker-reports.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<style>
    .header{display:none;}
    
    /*LIGHTBOX CSS*/
    
body {
  font-family: Verdana, sans-serif;
  margin: 0;
}

* {
  box-sizing: border-box;
}

.row > .column {
  padding: 0 8px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

.column {
  float: left;
  width: 25%;
}

/* The Modal (background) */
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  padding-top: 100px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: black;
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  width: 90%;
  max-width: 1200px;
}

/* The Close Button */
.close {
  color: white;
  position: absolute;
  top: 10px;
  right: 25px;
  font-size: 35px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #999;
  text-decoration: none;
  cursor: pointer;
}

.mySlides {
  display: none;
}

.cursor {
  cursor: pointer;
}

/* Next & previous buttons */
.prev,
.next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -50px;
  color: white;
  font-weight: bold;
  font-size: 20px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
  -webkit-user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover,
.next:hover {
  background-color: rgba(0, 0, 0, 0.8);
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

img {
  margin-bottom: -4px;
}

.caption-container {
  text-align: center;
  background-color: black;
  padding: 2px 16px;
  color: white;
}

.demo {
  opacity: 0.6;
}

.active,
.demo:hover {
  opacity: 1;
}

img.hover-shadow {
  transition: 0.3s;
}

.hover-shadow:hover {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}


</style>
<link href="{{ env('PUBLIC_PATH') }}/css/worker-app.css" rel="stylesheet" type="text/css" />
<link href="{{ env('PUBLIC_PATH') }}/css/worker.hoursapp.css" rel="stylesheet" type="text/css" />

<div lass="container-fluid">
<div class="row page_headingapp">
        <div class="col-1">
            
        </div>
        <div class="col">
            <h4 class="mb-0 submisn_heading step_heading">Images</h4>
            
        </div>
        <div class="col-1"></div>
    </div>

    <div class="row image-delete-success" style="display: none;">
        <div class="alert alert-success" style="width: 96%; margin: auto; background-color: #34c38f;
         color: #fff; text-align: center; border: 0; z-index: 1; border-right: 10px; min-height: 30px; display: flex; justify-content: center;
          align-content: center; flex-direction: column;">
            {{ session()->get('success') }}
        </div>
    </div>
    
    <!--LIGHTBOX HTML-->
    
    
<!-- <div class="row">-->
<!--  <div class="column">-->
<!--    <img src="img_nature.jpg" style="width:100%" onclick="openModal();currentSlide(1)" class="hover-shadow cursor">-->
<!--  </div>-->
<!--  <div class="column">-->
<!--    <img src="img_snow.jpg" style="width:100%" onclick="openModal();currentSlide(2)" class="hover-shadow cursor">-->
<!--  </div>-->
<!--  <div class="column">-->
<!--    <img src="img_mountains.jpg" style="width:100%" onclick="openModal();currentSlide(3)" class="hover-shadow cursor">-->
<!--  </div>-->
<!--  <div class="column">-->
<!--    <img src="img_lights.jpg" style="width:100%" onclick="openModal();currentSlide(4)" class="hover-shadow cursor">-->
<!--  </div>-->
<!--</div>-->

<!--<div id="myModal" class="modal">-->
<!--  <span class="close cursor" onclick="closeModal()">&times;</span>-->
<!--  <div class="modal-content">-->

<!--    <div class="mySlides">-->
<!--      <div class="numbertext">1 / 4</div>-->
<!--      <img src="img_nature_wide.jpg" style="width:100%">-->
<!--    </div>-->

<!--    <div class="mySlides">-->
<!--      <div class="numbertext">2 / 4</div>-->
<!--      <img src="img_snow_wide.jpg" style="width:100%">-->
<!--    </div>-->

<!--    <div class="mySlides">-->
<!--      <div class="numbertext">3 / 4</div>-->
<!--      <img src="img_mountains_wide.jpg" style="width:100%">-->
<!--    </div>-->
    
<!--    <div class="mySlides">-->
<!--      <div class="numbertext">4 / 4</div>-->
<!--      <img src="img_lights_wide.jpg" style="width:100%">-->
<!--    </div>-->
    
<!--    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>-->
<!--    <a class="next" onclick="plusSlides(1)">&#10095;</a>-->

<!--    <div class="caption-container">-->
<!--      <p id="caption"></p>-->
<!--    </div>-->


<!--    <div class="column">-->
<!--      <img class="demo cursor" src="img_nature_wide.jpg" style="width:100%" onclick="currentSlide(1)" alt="Nature and sunrise">-->
<!--    </div>-->
<!--    <div class="column">-->
<!--      <img class="demo cursor" src="img_snow_wide.jpg" style="width:100%" onclick="currentSlide(2)" alt="Snow">-->
<!--    </div>-->
<!--    <div class="column">-->
<!--      <img class="demo cursor" src="img_mountains_wide.jpg" style="width:100%" onclick="currentSlide(3)" alt="Mountains and fjords">-->
<!--    </div>-->
<!--    <div class="column">-->
<!--      <img class="demo cursor" src="img_lights_wide.jpg" style="width:100%" onclick="currentSlide(4)" alt="Northern Lights">-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->
 
    <div class="row">
        <div class="col-md-6 m-auto text-center">
            <div class="card">
                <div class="card-body">
                    <!-- <h4 class="w-card_title">Images</h4> -->
                    <div class="row" style="text-align:center">
                        @foreach((array)$hour->images as $image_nr => $image)
                        <div class="col-4" id="hour_image_{{$hour->id}}">
                            <div class="row popup-gallery d-flex flex-wrap">
                                <a class="image-popup-no-margins" href="{{ Storage::url($image) }}">
                                <!-- <a class="image-popup-no-margins" href="{{ env('PUBLIC_PATH') }}/img/worker-default.png"> -->
                                    <!-- <div class="img-fluid"> -->
                                        <!-- <img src="{{ Storage::url($image) }}" style="width: 150px;">  -->
                                        <!-- <img src="{{ env('PUBLIC_PATH') }}/img/worker-default.png" style="width: 150px;"> -->
                                    <!-- </div> -->
                                    <div class="card w-100">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                  <div class="img-fluid">
                                                      <img src="{{ Storage::url($image) }}" style="width: 150px;"> 
                                                    <!-- <img src="{{ env('PUBLIC_PATH') }}/img/worker-default.png" style="width: 150px;"> -->
                                                  </div> 
                                               </div>
                                          </div>
                                        </div>
                                        
                                    </div>
                                    
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div> 
            </div>
        </div>    
</div>
  
@endsection
@section('scripts')
    @parent

<!-- lightbox init js-->
  <script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/js/pages/lightbox.init.js"></script>
    
    <!--LIGHTBOX SCRIPT-->
    
    <script>
function openModal() {
  document.getElementById("myModal").style.display = "block";
}

function closeModal() {
  document.getElementById("myModal").style.display = "none";
}

/*
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  captionText.innerHTML = dots[slideIndex-1].alt;
}

*/
</script>
    
@endsection

