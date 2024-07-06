<table class="table table-bordernone">
    <thead>
        <tr>
            <th colspan="2" style="font-size: 14px; font-weight: bold;">Data Preference</th>
        </tr>
        <tr>
            <th>User</th>
            <th>Style</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($preferences as $preference)
            <tr>
                <td>{{ $preference->user->name }}</td>
                <td>{{ $preference->style->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
