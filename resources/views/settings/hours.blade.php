@extends('layouts.user')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Custom worker positions</h4>
                        <p class="card-title-desc"></p>

                        <div class="table-responsive">
                            <table id="theTable" class="table table-editable table-nowrap align-middle table-edits">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($customWorkerPositions as $k=>$customPosition)
                                    <tr id="row-{{$customPosition->id}}">
                                        <td style="width: 80px">{{$k+1}}</td>
                                        <td >
                                            <span id="name-{{$customPosition->id}}">{{$customPosition->name}}</span>
                                            <span style="display:none" id="update-{{$customPosition->id}}">
                                                <input id="input-{{$customPosition->id}}" value="{{$customPosition->name}}" class="col-4 input" >
                                                <button class="btn btn-primary btn-sm submitBtn" id="submit-{{$customPosition->id}}" >Update</button>
                                            </span>
                                        </td>
                                        <td style="width: 100px">
                                            <span id="actions-{{$customPosition->id}}">
                                                <a id="editBtn-{{$customPosition->id}}" class="editBtn btn btn-outline-secondary btn-sm edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a id="deleteBtn-{{$customPosition->id}}" class="deleteBtn btn btn-outline-secondary btn-sm delete" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                @if(sizeof($customWorkerPositions) == 0)

                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->




        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Late submissions</div>
                        <form action="{{route('settings.latesubmission.update')}}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Maximum day limit*</label>
                                <input class="form-control" type="number" name="maxDay" value="{{$late['maxDay'] ?? null}}" placeholder="1-28">
                            </div>
                            <div class="mb-3">
                                <label>Late submission notification for manager*</label>
                                <div class="col-md-12">
                                    <div class="mb-3 form-check form-switch form-switch-md">
                                        <input class="form-check-input" type="checkbox" name="disableNotifications" id="latesubmission-toggle"  @if($late['disableNotifications'] ?? false) checked @endif>
                                        <label for="latesubmission-toggle" class="form-check-label">Disable notifications</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Text to show for late submissions*</label>
                                <p id="showValidationError" style="color: red; display: none">Max character limit is 90</p>
                                <textarea id="latesubmission-text" class="form-control" name="message" style="height: 100px;" >{{$late['message'] ?? ""}}</textarea>
                            </div>
                            <button id="submitFormBtn" type="submit" class="btn btn-primary mt-3 js-disable">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection


@section('scripts')
    @parent
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/js/pages/sweet-alerts.init.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let editBtns = document.getElementsByClassName('editBtn')
        editBtns.forEach(d => {
            d.addEventListener('click', function(){
                let id =  (this.id).replace('editBtn-', '')
                $('#name-'+id).hide()
                $('#actions-'+id).hide()
                $('#update-'+id).show()
            })
        })

        let deleteBtns = document.getElementsByClassName('deleteBtn')
        deleteBtns.forEach(d => {
            d.addEventListener('click', function(){
                let id =  (this.id).replace('deleteBtn-', '')
                Swal.fire({
                    icon: 'error',
                    title: 'Are you sure?',
                    confirmButtonText: '<i class="fa fa-thumbs-up"></i> Proceed!',
                    text: 'You are going to delete a custom worker position!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type:'DELETE',
                            url:"{{ route('settings.workerposition.delete') }}",
                            data:{id},
                            success:function(data){
                                $('#row-'+id).hide()
                                Swal.fire('Successfully Deleted!', '', 'success')
                            },
                            error: function(data){
                                Swal.fire('Error occurred!', '', 'error')
                            }
                        });
                    }
                })
            })
        })



        let submitBtns = document.getElementsByClassName('submitBtn')
        submitBtns.forEach(d => {
            d.addEventListener('click', function(){
                let id =  (this.id).replace('submit-', '')
                let v = document.getElementById('input-'+id)
                updateCustomPosition(id, v.value)
                $('#name-'+id).show()
                $('#actions-'+id).show()
                $('#update-'+id).hide()

            })
        })

        $('#latesubmission-text').bind('input propertychange', function() {
            var updateBtn = $('#submitFormBtn')[0]
            var showError = $('#showValidationError')[0]
            if(this.value.length <=90){
                updateBtn.style.display = 'block'
                showError.style.display = 'none'
            }else{
                updateBtn.style.display = 'none'
                showError.style.display = 'block'
            }
        });

        function updateCustomPosition(id, value) {

            $.ajax({
                type:'POST',
                url:"{{ route('settings.workerposition.update') }}",
                data:{id, name: value},
                success:function(data){
                    const d = document.getElementById('name-'+id)
                    d.innerHTML = value
                }
            });
        }

    </script>
@endsection
