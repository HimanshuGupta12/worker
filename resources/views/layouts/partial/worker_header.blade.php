
<div class="header" style="position: relative;">

    @yield('header_content')

<style>
@media only screen and (max-width:768px){
    .custom_message{
        
        width:75%;
    }
    
}

@media only screen and (min-width:768px){
    .custom_message{
        
        width:16%;
    }
    
}
</style>
    @if ($errors->any())
        <div class="alert alert-danger" style="position: absolute; top: 75px; left: 26px; right: 26px; background-color: #c74444; color: #fff; text-align: center; border: 0; z-index: 1;">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
    @if (session()->has('success'))
        <div id="successMessage" class="alert alert-success custom_message" style="position: absolute; padding: 28px; background-color: #34c38f;
     color: #fff; text-align: center; border: 0; z-index: 99999; flex-direction: column; top: 50%; left: 50%; transform: translate(-50%, 27%);">
            <i class="fas fa-check-circle"></i>  {{ session()->get('success') }}
        </div>
    @endif
    @if (session()->has('danger'))
        <div id="deletemessage" class="alert alert-danger custom_message" style="position: absolute; padding: 28px; background-color: #d9534f;
     color: #fff; text-align: center; border: 0; z-index: 99999; flex-direction: column ; top: 50%; left: 50%; transform: translate(-50%, 27%);">
            <i class="fas fa-check-circle"></i> {{ session()->get('danger') }}
        </div>
    @endif
</div>

@section('scripts')
@parent
<script>
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
@endsection
