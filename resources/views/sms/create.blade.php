@extends('layouts.user')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Send workers profile link again</div>
                        Worker name: {{ $worker->fullName() }}<br>
                        Worker phone: {{ $worker->phone() }}<br><br>
                        <form action="{{ route('sms.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="worker_id" value="{{ $worker->id }}">
                            <div class="mb-3">
                                <label>Custom message</label>
                                <textarea class="form-control js-textarea" name="text" maxlength="320" style="height: 200px;"></textarea>
                            </div>
                            <button class="btn btn-primary mt-3">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
