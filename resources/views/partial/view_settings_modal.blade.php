<div class="modal fade" id="viewSettingsModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="myExtraLargeModalLabel2">View Settings</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<script>
    function viewSettings(module_name) {
        if(module_name) {
            $.ajax({
                url: "{{ route('settings.getViewSettings') }}",
                type: 'GET',
                data: { module_name: module_name }
            }).done(function(response) {
                if(response.status == 'success') {
                    $("#viewSettingsModal .modal-body").html(response.view);
                    $("#viewSettingsModal").modal('show');
                }
            }).fail(function(jqXHR, textStatus) {
                var errMsg = $.parseJSON(jqXHR.responseText);
                errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                showMessage(errMsg, 'danger');
            });;
        }
    }
</script>