<style>
    .select2-container {
        width: 100% !important;
    }
</style>
    <div class="container-fluid">
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
                                <form id="worker_transfer_form" action="{{ route('transfer', $tool->publicId()) }}" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Worker</label>
                                        <input type="hidden" name="to" value="worker">
                                        <select class="form-select-transfer-worker" id="worker_id" name="worker_id">
                                            <option value="">&nbsp;</option>
                                            @foreach ($workers as $worker)
                                                <option value="{{ $worker->id }}">{{ $worker->fullName() }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback worker-error" role="alert" style="display: none">
                                            <strong>Please choose worker to transfer tool</strong>
                                        </span>
                                    </div>
                                    <button type="button" class="btn btn-primary transfer-to-worker">Transfer to worker</button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form id="storage_transfer_form" action="{{ route('transfer', $tool->publicId()) }}" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Storage</label>
                                        <select class="form-select" id="storage_id" name="storage_id">
                                            <option value="">&nbsp;</option>
                                            @foreach ($storages as $storage)
                                            <option value="{{ $storage->id }}">{{ $storage->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback storage-error" role="alert" style="display: none">
                                            <strong>Please choose storage to transfer tool</strong>
                                        </span>                                    
                                    </div>
                                    <input type="hidden" name="to" value="storage">
                                    <button type="button" class="btn btn-primary transfer-to-storage">Transfer to storage</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(".form-select-transfer-worker").select2({
        dropdownParent: $(".transfer-tool-modal")
    });
    $(document).ready(function(){
        $(".transfer-to-storage").on('click', function(){
            if ($("#storage_id").val() == null || $("#storage_id").val() == '') {
                $(".storage-error").show();
            } else {
                $(".storage-error").hide();
                $("#storage_transfer_form").submit();
            }
        });
        
        $(".transfer-to-worker").on('click', function(){
            if ($("#worker_id").val() == null || $("#worker_id").val() == '') {
                $(".worker-error").show();
            } else {
                $(".worker-error").hide();
                $("#worker_transfer_form").submit();
            }
        });
    })
</script>
