<!-- resources/views/admin/users/tableuser.blade.php -->

<table class="table table-bordernone">
    <thead>
        <tr>
            <th colspan="9" style="font-size: 14px; font-weight: bold;">Data User</th> <!-- Adjust the colspan as needed -->
        </tr>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Account Created</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td>{{ $user->gender }}</td>
            <td>{{ $user->address }}</td>
            <td>{{ $user->created_at->format('Y-m-d') }}</td>
            <td>{{ $user->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
