@extends('layouts.user')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Edit storage</div>
                        <form action="{{ route('storages.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Name</label>
                                <input class="form-control  @error('name') is-invalid @enderror" type="text" name="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Address</label>
                                <textarea class="form-control" name="address" style="height: 100px;"></textarea>
                            </div>
                            <button class="btn btn-primary mt-3 js-disable">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
