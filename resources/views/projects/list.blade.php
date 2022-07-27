<table id="datatable-project-popup" class="table align-middle table-nowrap mb-0">
    <thead class="table-light-color">
    <tr>
        <th class="align-middle">ID</th>
        <th class="align-middle">Project name</th>
    </tr>
    </thead>
    <tbody>
        @foreach($projects as $k => $project)
        <tr>
        <td>{{$project['company_project_id']}}</td>
        <td>{{$project['name'];}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script type="text/javascript">
    $('#datatable-project-popup').dataTable({
        searching: false, paging: false, info: false,
        order: [
            [ 0, "desc"]
        ],
    });
        
</script>