@extends('layouts.user')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Monthly worker inventorization settings</div>
                        <form action="{{ route('inventorization.save') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Day of the month on which workers will need to scan tools*</label>
                                <input class="form-control" type="number" name="month_day" value="{{ $month_day }}" placeholder="1-28">
                            </div>
                             <div class="mb-3">
                                 <label>SMS message to workers*</label>
                                 <textarea class="form-control" name="sms_message" style="height: 100px;" placeholder="Hi, please balance your tools.">{{ $sms_message }}</textarea>
                             </div>
                            @if ($page === 'create')
                                <button class="btn btn-primary mt-3 js-disable">Enable monthly inventorization</button>
                            @else
                                <div style="text-align: center;">
                                    <button class="btn btn-primary mt-3 js-disable" style="display: inline-block;">Update</button> <a href="{{ route('inventorization.disable') }}" class="btn btn-danger mt-3" style="display: inline-block;">Disable inventorization</a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Ask worker to inventory his tools</div>
                        <form action="{{ route('inventorization.worker') }}" method="post">
                            @csrf
                            <div class="form-check mb-3">
                                <input id="worker_all" class="form-check-input js-all" type="checkbox">
                                <label for="worker_all" class="form-check-label">All workers</label>
                            </div>
                            <div class="row mb-3">
                                @foreach ($workers->splitIn(3) as $chunked_workerse)
                                    <div class="col-4">
                                        @foreach ($chunked_workerse as $worker)
                                            <div class="form-check">
                                                <input id="worker{{ $worker->id }}" class="form-check-input js-all-select @error('worker_ids') is-invalid @enderror" type="checkbox" name="worker_ids[]" value="{{ $worker->id }}">
                                                <label for="worker{{ $worker->id }}" class="form-check-label">{{ $worker->fullName() }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                        @error('worker_ids')
                                            <span class="invalid-feedback" role="alert" style="display: block">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                            </div>
                            <div class="mb-3">
                                 <div class="form-group">
                                     <label>SMS message text*</label>
                                     <textarea class="form-control @error('sms_text') is-invalid @enderror" name="sms_text" maxlength="320" style="height: 100px;" placeholder="Please balance your tools"></textarea>
                                     @error('sms_text')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                 </div>
                            </div>
                            <button class="btn btn-primary mt-3 js-disable">Ask to inventory tools</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Request storage tools to be inventoried</div>
                        <form action="{{ route('inventorization.storage') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Storage</label>
                                <select class="form-select" name="storage_id">
                                    @foreach ($storages as $storage)
                                        <option value="{{ $storage->id }}">{{ $storage->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-primary mt-3 js-disable">Request storage inventory</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function () {
            $('.js-all').change(function () {
                var isSelected = this.checked;
                $('.js-all-select').prop('checked', isSelected);
            });
        });
    </script>
@endsection
