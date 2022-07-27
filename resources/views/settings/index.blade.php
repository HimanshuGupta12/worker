@extends('layouts.user')
@section('content')

<style>
    
     @media only screen and (min-width: 280px) and (max-width: 768px)
        {
.save-mb {
    font-size: 17px;
}
}
    
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="">
                <div class="card-body">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs" role="tablist" id="myTab">

                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#home-1" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">Inventorization settings</span>    
                            </a>
                        </li>



                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#profile-1" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block">Tool Categories</span>    
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#company-setting-1" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block">Storages</span>    
                            </a>
                        </li>

                        </ul>
                    </div>
                    <div class="tab-content pt-3 text-muted">
                        <div class="tab-pane fade show active" id="home-1" role="tabpanel">
                        <div class="">
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
                                <div style="text-align: left;">
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
                        </div>
                        <div class="tab-pane fade" id="profile-1" role="tabpanel">
                        <div class="">
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
                                                <button class="btn btn-primary mt-3 js-disable save-mb">Save</button>
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
                        </div>    
                        <div class="tab-pane fade" id="company-setting-1" role="tabpanel">
                        <div class="">
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
                                                    @foreach ($toolstorages as $storage)
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
                                    {{ $toolstorages->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                        </div> 
                    </div>
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
    <script>
        $(document).ready(function(){
            $('a[data-bs-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if(activeTab){
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }
        });
    </script>
@endsection
