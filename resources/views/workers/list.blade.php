<table id="datatable-worker-popup" class="table align-middle table-nowrap mb-0">
    <thead class="table-light-color">
    <tr>
        <th class="align-middle">Serial</th>
        <th class="align-middle">Worker name</th>
    </tr>
    </thead>
    <tbody>
        @foreach($workers as $k => $worker)
        <tr>
        <td>{{++$k}}</td>
        <td>{{$worker->first_name.' '.$worker->last_name}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script type="text/javascript">
    $('#datatable-worker-popup').dataTable({
        searching: false, paging: false, info: false
    });
        
</script>