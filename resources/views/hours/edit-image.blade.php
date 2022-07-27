<style>
    .mfp-wrap, .mfp-bg{
            z-index: 9999999 !important;
    }
    @media only screen and (min-width:280px) and (max-width:768px)
    {
      .save-mb
    {
        font-size:17px;
    } 
    }
    
</style>


  <!-- Lightbox css -->
  <link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />

<div lass="container">
    <div class="row image-delete-success" style="display: none;">
        <div class="alert alert-success" style="width: 96%; margin: auto; background-color: #34c38f;
         color: #fff; text-align: center; border: 0; z-index: 1; border-right: 10px; min-height: 30px; display: flex; justify-content: center;
          align-content: center; flex-direction: column;">
            {{ session()->get('success') }}
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="w-card_title">Images</h4>
                    <div class="row">
                        @foreach((array)$hour->images as $image_nr => $image)
                        <div class="col-4" id="hour_image_{{$hour->id}}">
                            <div class="row lightbox">
                                <div class="col-12" style="text-align: center;">
                                    <a class="image-popup-no-margins" href="{{ Storage::url($image) }}">
                                    <div class="img-fluid">
                                    <!-- <img src="{{ Storage::url($image) }}" style="width: 150px;"> -->
                                    <!--<img src="{{ env('PUBLIC_PATH') }}/img/worker-default.png" style="width: 160px;">-->

                                        <img src="{{ Storage::url($image) }}" class="img-fluid" style="width: 160px;">
                                    </div>
                                    </a>
                                </div>
                                <div class="col-12" style="text-align: center; margin-top: 15px;">
                                    <button type="delete" class="btn custom_rest_btn delete-image" data-hour_id="{{$hour->id}}" data-image_nr="{{$image_nr}}">
                                        <span style="color: red; text-decoration: underline !important;">Delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div> 
            </div>
        </div>    

    <div class="row">
        <form action="{{route('updateImages', $hour->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="w-card_title">upload image</h4>
                        <div class="row">
                        <input type="file" name="images[]" multiple>
                        </div>
                    </div> 
                </div>
                <button class="btn btn-primary mt-3 js-disable save-mb">Save</button>
            </div> 
        </form>   
    </div>
</div>

<!-- lightbox init js-->
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/js/pages/lightbox.init.js"></script>
<script type="text/javascript">
    $(document).on('click', '.delete-image', function() {
        let hour_id = $(this).data('hour_id');
        let image_nr = $(this).data('image_nr');
        if (confirm('Are you sure to delete..?')) {
            $.ajax({
                url: '/hour/'+hour_id+'/'+image_nr,
                type: 'DELETE',
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                },
                success: function(result) {
                    $(".image-delete-success").show();
                    $(".image-delete-success .alert-success").text(result.message);
                    $("#hour_image_"+hour_id).remove();
                }
            });
        }
    });
</script>


<script>
    $(document).ready(function() {
        $('.img-fluid').magnificPopup({
            gallery : {
                enabled :true
            }
        });
    });
</script>