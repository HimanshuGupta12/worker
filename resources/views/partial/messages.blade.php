@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endif
@if (session()->has('success'))
    <div id="successMessage" class="alert alert-success ak" style="position: absolute; padding: 28px; top: 15px; left: 49%; background-color: #34c38f;
     color: #fff; text-align: center; border: 0; z-index: 99999; border-right: 10px; min-height: 30px; display: flex; justify-content: center;
      align-content: center; flex-direction: column; width: 20%;">
        {{ session()->get('success') }}
    </div>
@endif
@if (session()->has('danger'))
    <div id="deletemessage" class="alert alert-success" style="position: absolute; padding: 28px; top: 15px; left: 49%;  background-color: #d9534f;
     color: #fff; text-align: center; border: 0; z-index: 99999; border-right: 10px; min-height: 30px; display: flex; justify-content: center;
      align-content: center; flex-direction: column ; width: 20%;">
        {{ session()->get('danger') }}
    </div>
@endif

@push('scriptsStack')
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
@endpush