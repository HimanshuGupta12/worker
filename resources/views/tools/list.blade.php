<table id="datatable-tool-popup" class="table align-middle table-nowrap mb-0">
    <thead class="table-light-color">
    <tr>
        <th class="align-middle">ID</th>
        <th class="align-middle">Tool name</th>
        <th class="align-middle">Model</th>
        <th class="align-middle">Last balanced date</th>
        <th class="align-middle">Possessor</th>
    </tr>
    </thead>
    <tbody>
        @foreach($tools as $k => $tool)
        <tr>
        <td>{{$tool->company_tool_id}}</td>
        <td>{{$tool->name}}</td>
        <td>{{$tool->model}}</td>
        <td>{{!empty($tool->inventoried_at) ? date('d-m-Y', strtotime($tool->inventoried_at)) : 'Nil' }}</td>
        <td>{{ !empty($tool->possessor) ? $tool->possessor->possessorName() : ''}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script type="text/javascript">
    $('#datatable-tool-popup').dataTable({
        searching: false, paging: false, info: false
    });
        
</script>