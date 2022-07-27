@extends('layouts.user')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Add category</div>
                        <form action="{{ route('tool-categories.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button class="btn btn-primary mt-3 js-disable">Save</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Tool categories</div>
                        <div class="table-responsive">
                            <table class="table table-stripped table-hover">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th></th>
                                </tr>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{!! button('delete', route('tool-categories.destroy', $category->id), 'delete', 'class="btn btn-xs btn-danger"', true) !!}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                {{ $categories->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
