@extends( Auth::check() ? 'layouts.user' : 'layouts.worker')

@section('head')
    @parent
    <link href="/skote/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    <style>
            span.select2 {
        width: 100% !important;
    }
            .datepicker table tr td.disabled {
            color: #adb5bd;
            opacity: .6;
        }
        #datatable tbody tr.highlight
        {
            font-weight: bold;
            text-shadow: 1px 1px 0px #EEE;
            color: #111;
            background-color: whitesmoke !important;
        }
        @media only screen and (min-width: 280px) and (max-width: 768px)
        {
.save-mb {
    font-size: 17px;
}
}
    </style>
    <?php
        $tools = isset($company_tools) ? json_encode($company_tools->toArray()) : json_encode([]);
    ?>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">{{__('Tool')}}
                        @if($page === 'create' && !empty($code))
                            <button type="button" class="btn btn-primary waves-effect waves-light" style="float:right" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">{{__('Duplicate Tool')}}</button>

                            <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <span class="modal-title" id="myExtraLargeModalLabel">{{__('Tool List')}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{__('Close')}}"></button>
                                        </div>
                                        <div class="modal-body">
                                        <table id="datatable-tool-list" class="table table-bordered dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th style="min-width: 50px;">{{__('ID')}}</th>
                                                    <th>{{__('Image')}}</th>
                                                    <th>{{__('Name')}}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($company_tools as $company_tool)
                                                <tr>
                                                    <td style="min-width: 50px;">
                                                        <span class="badge" style="font-size: 12px; background: #dadbdc; color: #303030; padding-left: 10px; padding-right: 10px;">{{ $company_tool->company_tool_id }}</span>
                                                    </td>
                                                    <td style="">
                                                        @if ($company_tool->images)
                                                            <a class="image-popup-no-margins" href="{{ Storage::url($company_tool->images[0]) }}">
                                                                <img class="avatar-md rounded-circle" alt="" src="{{ Storage::url($company_tool->images[0]) }}">
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td style="max-width: 200px;">
                                                        <h5 class="font-size-14 text-dark" style="white-space: initial;">{{ $company_tool->name }}</h5>
                                                        <p class="font-size-12 text-truncate text-muted mb-0">{{ $company_tool->model }}</p>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <button type="button" tool_id="{{ $company_tool->id }}" data-bs-dismiss="modal" class="btn btn-light duplicate_tool">{{__('Duplicate')}}</button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        @endif
                        </div>
                        @if ($tool->id)
                            <div>
                                <table>
                                    <tr>
                                        @foreach ((array)$tool->images as $image_nr => $image)
                                            <td style="padding: 0 2px;">
                                                <img src="{{ Storage::url($image) }}" style="width: 100px;">
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach ((array)$tool->images as $image_nr => $image)
                                            <td style="text-align: center; padding-top: 5px;">
                                                {!! button('delete', route('tools.delete-photo', [$tool->publicId(), $image_nr]), 'delete', 'class="btn btn-xs btn-danger"', true) !!}
                                            </td>
                                        @endforeach
                                    </tr>
                                </table>
                                <br>
                            </div>
                        @endif
                        @if ($page === 'duplicate')
                            <div>
                                @foreach ((array)$tool->images as $image_nr => $image)
                                    <img src="{{ Storage::url($image) }}" style="width: 100px;">
                                @endforeach
                                <br>
                            </div>
                            <br>
                        @endif
                        <form action="{{ $url }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="qr_duplicate_tool_id" id="qr_duplicate_tool_id" value="" />
                            @if ($tool->id)
                                <input type="hidden" name="tool_code" value="{{ $tool->tool_code }}" />
                            @elseif($page === 'duplicate')
                                <input type="hidden" name="tool_code" value="" />
                            @else
                                <input type="hidden" name="tool_code" value="{{ $code }}" />
                            @endif
{{--                            <div class="mb-3">--}}
{{--                                <label>Tool</label>--}}
{{--                                <input class="form-control" type="text" name="id" value="{{ request('code') }}" @if (request('code')) disabled @endif>--}}
{{--                            </div>--}}
                            <div class="mb-3">
                                <label>{{__('Name')}}*</label>
                                <input class="form-control tool_name @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $tool->name) }}">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>{{__('Images')}}</label>
                                <input class="form-control tool_image @error('images') is-invalid @enderror" type="file" name="images[]" multiple accept="image/jpeg,image/png">
                                @error('images')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>{{__('Model')}}</label>
                                <input class="form-control tool_model @error('model') is-invalid @enderror" type="text" name="model" value="{{ old('model', $tool->model) }}">
                                @error('model')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>{{__('Serial number')}}</label>
                                <input class="form-control tool_serial  @error('serial') is-invalid @enderror" type="text" name="serial" value="{{ old('serial', $tool->serial) }}">
                                @error('serial')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>{{__('Price')}}</label>
                                <input class="form-control tool_price @error('price') is-invalid @enderror" type="number" step="0.01" name="price" value="{{ old('price', $tool->price) }}">
                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>{{__('Purchased at')}}</label>
                                <div class="input-group" id="datepicker1">
                                    <input autocomplete="off" name="purchased_at" data-date-end-date="0d" value="{{ old('purchased_at', $tool->purchased_at?->format(dateFormat())) }}" type="text" class="form-control tool_purchased_at  @error('purchased_at') is-invalid @enderror" data-date-autoclose="true" placeholder="dd.mm.yyyy" data-date-format="dd.mm.yyyy" data-date-container='#datepicker1' data-provide="datepicker">
                                    @error('purchased_at')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div><!-- input-group -->
                            </div>
                            @if ($storages->count())
                                @if ($page === 'create' || $page === 'duplicate')

                                <div class="mb-3">
                                    <div class="row arrange_checkbox">
                                        <div class="mb-1">

                                            <label class="form-label col-md-6 custom_form_label">Tool Assign tool for: </label>
                                        </div>
                                        <div class="form-check col-md-3" style="padding-left: 30px;">
                                            <div class="form-check form-switch form-switch-lg " dir="ltr">
                                                <input type="hidden" id="possessor_type" name="possessor_type" @if( old('possessor_type') =='App\Models\Worker' || $tool->possessor_type == 'App\Models\Worker') value="App\Models\Worker" @else value="App\Models\Storage" @endif>
                                                <input class="form-check-input" id="possessor_type_switch" type="checkbox" @if( old('possessor_type') =='App\Models\Worker' || $tool->possessor_type == 'App\Models\Worker') checked @endif>
                                                <span id="possessor_type_label">
                                                    @if( old('possessor_type') =='App\Models\Worker' || $tool->possessor_type == 'App\Models\Worker')
                                                    Worker
                                                    @else
                                                    Storage
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <!-- <div class="form-check col-md-3" style="padding-left: 30px;">
                                           <input id="App-Models-Storage" class="form-check-input possessor_type possessor_type_storage" type="radio" name="possessor_type" value="App\Models\Storage" checked >
                                           <label for="App-Models-Storage" class="form-check-label">Storage</label>
                                        </div>
                                        <div class="form-check col-md-3" style="padding-left: 30px;">
                                           <input id="App-Models-Worker" class="form-check-input possessor_type possessor_type_worker" type="radio" name="possessor_type" value="App\Models\Worker" @if( old('possessor_type') =='App\Models\Worker' || $tool->possessor_type == 'App\Models\Worker') checked @endif>
                                           <label for="App-Models-Worker" class="form-check-label">Worker</label>
                                        </div> -->
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-6" id="storagePossessor">
                                            <label>{{__('Storage')}}</label>
                                                <select class="form-select tool_storage @error('storage_id') is-invalid @enderror" name="storage_id">
                                                    @if ($storages->count() > 1)
                                                        <option value="">&nbsp;</option>
                                                    @endif
                                                    @foreach ($storages as $storage)
                                                        <?php
                                                        $is_selected = false;
                                                        // edit
                                                        if ($tool->possessor?->is($storage)) {
                                                            $is_selected = true;
                                                        }
                                                        // create
                                                        if (old('storage_id') == $storage->id) {
                                                            $is_selected = true;
                                                        }
                                                        ?>
                                                        <option value="{{ $storage->id }}"  @if ($is_selected) selected @endif>{{ $storage->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('storage_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6" id="workerPossessor" >
                                            <label>{{__('Worker')}}</label>
                                            <select class="form-select worker_list" name="worker_id" >
                                                @if ($workers->count() > 1)
                                                    <option value="">&nbsp;</option>
                                                @endif
                                                @foreach ($workers as $worker)
                                                    <?php
                                                        $is_selected = false;
                                                        // edit
                                                        if ($tool->possessor?->is($worker)) {
                                                            $is_selected = true;
                                                        }
                                                        // create
                                                        if (old('worker_id') == $worker->id) {
                                                            $is_selected = true;
                                                        }
                                                        ?>
                                                    <option value="{{ $worker->id }}" @if ($is_selected) selected @endif > {{ $worker->first_name }} {{ $worker->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endif
                            @if ( $categories->count())
                                <div class="mb-3">
                                    <label for="tool_category_id">{{__('Category')}}</label>
                                    <select id="tool_category_id" class="form-select tool_category_id @error('tool_category_id') is-invalid @enderror" name="tool_category_id">
                                        <option value="">&nbsp;</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if ($category->id == old('tool_category_id', $tool->tool_category_id)) selected @endif >{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('tool_category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endif
                            <button class="btn btn-primary mt-3 js-disable save-mb">{{__('Save')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $('#datatable-tool-list').dataTable( {
            "ordering": false,
            "lengthChange": false
        });

        var tools = <?php echo $tools ?>;
        $('#datatable-tool-list tbody').on( 'click', 'tr', function () {
            var table = $('#datatable-tool-list').DataTable();
            if ( $(this).hasClass('highlight') ) {
                $(this).removeClass('highlight');
            }
            else {
                table.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });

        $('#datatable-tool-list tbody').on('click', 'tr td button.duplicate_tool', function(){
           var tool_id = $(this).attr('tool_id');
           for (let i = 0; i < tools.length; i++) {
               if (tool_id == tools[i]['id']) {
                   populateTool (tools[i]);
                   break;
               }
           }
        });

        function populateTool (tool_data)
        {
            if (typeof tool_data['purchased_at'] != 'undefined') {
                let temp_date = new Date(tool_data['purchased_at']);
                let purchased_at = temp_date.getDate().toString() + '.'+ (temp_date.getMonth()+1).toString() + '.' + temp_date.getFullYear().toString();
                $(".tool_purchased_at").val(purchased_at);
            }
            $(".tool_name").val(tool_data['name']);
            $(".tool_model").val(tool_data['model']);
            $(".tool_serial").val(tool_data['serial']);
            $(".tool_price").val(tool_data['price']);

            $('select.tool_category_id option[value="' + tool_data['tool_category_id'] + '"]').prop('selected', true);
            if (tool_data['possessor_type'] !== null && tool_data['possessor_type'].includes("Storage")) {
                $('select.tool_storage option[value="' + tool_data['possessor_id'] + '"]').prop('selected', true);
            } else {
                $('select.tool_storage').find('option:selected').remove();
            }
            $(".form-select").select2();
            $("#qr_duplicate_tool_id").val(tool_data['id']);
            let storagePath = "{!! storage_path() !!}";

            let imageHtml = '';
            if (tool_data['images'] != null) {
                tool_data['images'].forEach(function (image) {
                    imageHtml+='<img src="'+storagePath+'/'+image+'" style="width: 100px;">';
                });
            }
            var imageComponent = '<div><table><tr>'+ imageHtml + '</tr></table><br></div>';


            $(imageComponent).insertBefore("form");
        }


        const defaultPossessorType = $('#possessor_type').val()
        if( defaultPossessorType  == "App\\Models\\Worker" ){
            $('#storagePossessor').hide()
            $('#workerPossessor').show()
        }else{
            $('#workerPossessor').hide()
            $('#storagePossessor').show()
        }
        $(".possessor_type").on("change", function () {
            let possessor_type = $(this).val();
            if (possessor_type.includes("Storage")) {
                $('#workerPossessor').hide()
                $('#storagePossessor').show()

                $('.tool_storage').removeAttr("disabled");
                $('.worker_list').prop('disabled', true).val(null).trigger('change');
            }else if (possessor_type.includes("Worker")) {
                $('#workerPossessor').show()
                $('#storagePossessor').hide()

                $('.worker_list').removeAttr("disabled");
                $('.tool_storage').prop('disabled', true).val(null).trigger('change');
            }
        });

        $("#possessor_type_switch").on("change", function(e){
            const isWorker = e.target.checked;
            if(isWorker){
                $('#possessor_type_label').text("Worker")
                $('#possessor_type').val("App\\Models\\Worker")
                $('#workerPossessor').show()
                $('#storagePossessor').hide()

                $('.worker_list').removeAttr("disabled");
                $('.tool_storage').prop('disabled', true).val(null).trigger('change');
            }else{
                $('#possessor_type_label').text("Storage")
                $('#possessor_type').val("App\\Models\\Storage")
                $('#workerPossessor').hide()
                $('#storagePossessor').show()
                $('.tool_storage').removeAttr("disabled");
                $('.worker_list').prop('disabled', true).val(null).trigger('change');
            }
        })

    });
    </script>
@endsection
