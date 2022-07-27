@extends( Auth::check() ? 'layouts.user' : 'layouts.worker')

@section('head')
    @parent
    <link href="/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <h4 class="mb-0 font-size-18" style="font-weight: 600;">TOOL LIST</h4>
                <div class="mt-1">Total: <b>{{ $tools->total() }}</b>, Total value: <b>{{ number_format($value, 0, ',', '.') }}</b>,-</div>
            </div>
            <div class="col-12 mb-4">
                <button class="btn btn-secondary" data-bs-toggle="collapse" href="#search"><i class="mdi mdi-magnify"></i> Search</button>
                <div class="btn-group" style="float: right;">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">+ Add tool <i class="mdi mdi-chevron-down"></i></button>
                    <div class="dropdown-menu" style="">
                        <a class="dropdown-item" href="{{ route('tools.create') }}">Add New Tool</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('scan', ['redirect' => route('tools.create')]) }}">Scan QR Code</a>
                    </div>
                </div>
<!--                <a href="{{ route('tools.create') }}" class="btn btn-primary" style="float: right;">+ Add tool</a>-->
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="collapse" id="search">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label>Search</label>
                                            <input class="form-control" type="text" name="q" placeholder="number, name, model" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label>Worker</label>
                                            <select class="form-select" name="worker_id" style="width: 100%;">
                                                <option value="">&nbsp;</option>
                                                @foreach ($workers as $worker)
                                                    <option value="{{ $worker->id }}">{{ $worker->fullName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label>Storage</label>
                                            <select class="form-select" name="storage_id" style="width: 100%;">
                                                <option value="">&nbsp;</option>
                                                @foreach ($storages as $storage)
                                                    <option value="{{ $storage->id }}">{{ $storage->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label>Category</label>
                                            <select class="form-select" name="category_id" style="width: 100%;">
                                                <option value="">&nbsp;</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label>Status</label>
                                            <select class="form-select" name="status_id" style="width: 100%;">
                                                <!-- <option value="">&nbsp;</option> -->
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status->id }}" {{ $loop->first ? 'selected="selected"' : '' }} >{{ ucfirst($status->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label>Balancing</label>
                                            <select class="form-select" name="need_inventorization" style="width: 100%;">
                                                <option value="">&nbsp;</option>
                                                <option value="0">Balanced</option>
                                                <option value="1">Not balanced</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-primary"><span class="fas fa-search"></span> Search</button>
                                        <a href="{{ url()->current() }}" class="btn btn-outline-light">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                    @foreach ($tools as $tool)
                        <tr>
                            <td>
                                <span class="badge" style="font-size: 12px; background: #dadbdc; color: #303030; padding-left: 10px; padding-right: 10px;">{{ $tool->company_tool_id }}</span>
                            </td>
                            <td>
                                @if ($tool->images)
                                    <a class="image-popup-no-margins" href="{{ Storage::url($tool->images[0]) }}">
                                        <img class="avatar-md rounded-circle" alt="" src="{{ Storage::url($tool->images[0]) }}">
                                    </a>
                                @endif
                            </td>
                            <td style="max-width: 200px;">
                                <h5 class="font-size-14 text-dark" style="white-space: initial;">{{ $tool->name }}</h5>
                                <p class="text-truncate text-muted mb-0">{{ $tool->model }}</p>
                            </td>
                            <td>
                                @if ($tool->possessor)
                                    @if ($tool->possessor::class === \App\Models\Worker::class)
                                        <h5 class="text-truncate font-size-14 text-dark">
                                            <span class="fas fa-user-alt"></span> {{ $tool->possessor->fullName() }}
                                        </h5>
                                    @elseif ($tool->possessor::class === \App\Models\Storage::class)
                                        <h5 class="text-truncate font-size-14 text-dark">
                                            <i class="fas fa-warehouse"></i> {{ $tool->possessor->name }}
                                        </h5>
                                    @else
                                        <?php throw new \Exception(); ?>
                                    @endif

                                    @if ($tool->showUnbalanced())
                                        <span class="badge badge-soft-danger">Not balanced</span>
                                    @elseif ($tool->showBalanced())
                                        <span class="badge badge-soft-success">Balanced</span>
                                    @endif

                                    @if ($tool->status->name !== 'operational')
                                        <?php
                                        $colors = [
                                            'operational' => 'success',
                                            'in service' => 'warning',
                                            'broken' => 'warning',
                                            'lost' => 'warning',
                                            'decommissioned' => 'danger',
                                        ];
                                        ?>
                                        <span class="badge bg-{{ $colors[$tool->status->name] }}">{{ $tool->status->name }}</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                {{ $tool->price }}<br>
                                @if ($tool->purchased_at)
                                    {{ $tool->purchased_at->format(dateFormat()) }}<br>
                                @endif
                                {{ $tool->updated_at->format(dateFormat()) }}
                            </td>
                            <td>
                                <a target="_blank" href="{{ $tool->qrLink() }}" title="QR code"><i class="mdi mdi-qrcode font-size-24 card-drop"></i></a>
                                <a target="_blank" href="{{ route('transfer', $tool->publicId()) }}" title="transfer"><i class="mdi mdi-account-switch-outline font-size-24 card-drop"></i></a>
                                <a href="{{ route('tools.duplicate', $tool->publicId()) }}" title="duplicate"><i class="mdi mdi-content-copy font-size-24 card-drop"></i></a>
{{--                                    <a href="#"><i class="mdi mdi-chevron-down font-size-24 card-drop"></i></a>--}}
                                <span class="dropdown">
                                    <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal font-size-24"></i>
                                    </a>
                                    <span class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="{{ route('tools.histories.index', $tool->publicId()) }}">History</a>
                                        <a class="dropdown-item" href="{{ route('tools.change-status', $tool->publicId()) }}">Change status</a>
                                        <a class="dropdown-item" href="{{ route('tools.edit', $tool->publicId()) }}">Edit</a>
                                        <a class="dropdown-item" data-method="delete" data-confirm="Are you sure?" href="{{ route('tools.destroy', $tool->publicId()) }}">Delete</a>
                                    </span>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $tools->withQueryString()->links() }}

                <?php
                /*
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Tools</div>
                        <div class="table-responsive">
                            <table class="table table-stripped table-hover">
                                <tr>
                                    <th>Info</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                @foreach ($tools as $tool)
                                    <tr>
                                        <td>
                                            @if ($tool->images)
                                                <img src="{{ Storage::url($tool->images[0]) }}" style="width: 150px;">
                                            @endif
                                        </td>
                                        <td>
                                            @include('tools.partial.info', compact('tool'))
                                        </td>
                                        <td><a target="_blank" class="btn btn-xs btn-secondary" href="{{ $tool->qrLink() }}">QR</a></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    more <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                                <div class="dropdown-menu" >
                                                    <a class="dropdown-item" href="{{ route('transfer', $tool->publicId()) }}">Transfer</a>
                                                    <a class="dropdown-item" href="{{ route('tools.histories.index', $tool->publicId()) }}">History</a>
                                                    <a class="dropdown-item" href="{{ route('tools.duplicate', $tool->publicId()) }}">Duplicate</a>
                                                    <a class="dropdown-item" href="{{ route('tools.change-status', $tool->publicId()) }}">Change status</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a class="btn btn-xs btn-secondary" href="{{ route('tools.edit', $tool->publicId()) }}">edit</a></td>
                                        <td>{!! button('delete', route('tools.destroy', $tool->publicId()), 'delete', 'class="btn btn-xs btn-danger"', true) !!}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                */
                ?>


            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="/skote/assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="/skote/assets/js/pages/lightbox.init.js"></script>
@endsection
