@extends('layouts.user')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Tool</div>
                        <div class="table-responsive">
                            <table class="table table-stripped table-hover">
                                <tr>
                                    <th>Item details</th>
                                    <th>Currently with</th>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Name:</b> {{ $tool->name }}<br>
                                        <b>Category:</b> {{ $tool->category?->name }}<br>
                                        <b>Price:</b> {{ $tool->price }}<br>
                                    </td>
                                    <td>
                                        {{ $tool->possessor?->possessorName() }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Transfer tool</div>
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('transfer', $tool->publicId()) }}" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Worker</label>
                                        <input type="hidden" name="to" value="worker">
                                        <select class="form-select" name="worker_id">
                                            <option value="">&nbsp;</option>
                                            @foreach ($workers as $worker)
                                                <option value="{{ $worker->id }}">{{ $worker->fullName() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="btn btn-primary">Transfer to worker</button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('transfer', $tool->publicId()) }}" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Storage</label>
                                        <input type="hidden" name="to" value="storage">
                                        <select class="form-select" name="storage_id">
                                            <option value="">&nbsp;</option>
                                            @foreach ($storages as $storage)
                                                <option value="{{ $storage->id }}">{{ $storage->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="btn btn-primary">Transfer to storage</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
