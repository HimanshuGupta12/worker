<table class="table table-stripped table-hover">
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th></th>
    </tr>
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <a href="{{ route('admin.login', $user->id) }}">login</a>
            </td>
        </tr>
    @endforeach
</table>
