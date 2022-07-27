<meta name="_token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <form id="viewSettingsForm" action="{{ route('settings.saveViewSettings') }}" method="POST">
        @csrf
        <input type="hidden" name="module_name" value="{{ $module_name }}" />

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="show_images_thumbnails" name="show_images_thumbnails" value="1" {{ (!empty($settings['show_images_thumbnails'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_images_thumbnails">Show images thumbnails</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="hide_worker_positions" name="hide_worker_positions" value="1" {{ (!empty($settings['hide_worker_positions'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hide_worker_positions">Hide worker positions</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="hide_comments" name="hide_comments" value="1" {{ (!empty($settings['hide_comments'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hide_comments">Hide comments</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="hide_invoice_status" name="hide_invoice_status" value="1" {{ (!empty($settings['hide_invoice_status'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hide_invoice_status">Hide invoice status</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="hide_breaks" name="hide_breaks" value="1" {{ (!empty($settings['hide_breaks'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hide_breaks">Hide breaks</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hide_actions" name="hide_actions" value="1" {{ (!empty($settings['hide_actions'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hide_actions">Hide actions</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>  
        </div>
        
        <button type="submit" class="btn btn-info w-md">Save</button>
    </form>
</div>