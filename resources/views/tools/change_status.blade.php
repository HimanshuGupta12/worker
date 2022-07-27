@extends('layouts.user')

<style>

@media only screen and (min-width: 280px) and (max-width: 768px)
{
.save-mb {
    font-size: 17px;
}
}
</style>
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">Change status</div>
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label>Status</label>
                                <select class="form-select" name="status_id">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}" @if ($tool->status_id === $status->id) selected @endif >{{ ucfirst($status->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea class="form-control" name="description" style="height: 100px;">{{ $tool->status_description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label>Photo</label>
                                <input class="form-control" type="file" name="photo" accept="image/jpeg,image/png">
                            </div>
                            <button class="btn btn-primary mt-3 js-disable save-mb">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
