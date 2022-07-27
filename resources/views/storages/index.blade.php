@extends('layouts.user')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-primary pull-right" href="{{ route('storages.create') }}">Add storage</a>
                <div class="clearfix"></div>
                <br>
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Storages</div>
                        <div class="table-responsive">
                            <table class="table table-stripped table-hover">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Tools/Not balanced</th>
                                    <th>Address</th>
                                    <th></th>
                                </tr>
                                @foreach ($storages as $storage)
                                    <tr>
                                        <td>{{ $storage->id }}</td>
                                        <td>{{ $storage->name }}</td>
                                        <td>{{ $storage->tools_count }}/{{ $storage->tools_need_inventorization_count }}</td>
                                        <td>{!! nl2br(e($storage->address)) !!}</td>
                                        <td>{!! button('delete', route('storages.destroy', $storage->id), 'delete', 'class="btn btn-xs btn-danger"', true) !!}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                {{ $storages->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
