<table class="table table-bordernone">
    <thead>
        <tr>
            <th colspan="4" style="font-size: 14px; font-weight: bold;">Data Complaint</th>
        </tr>
        <tr>
            <th>User</th>
            <th>Order ID</th>
            <th>Description</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($complaints as $complaint)
            <tr>
                <td>{{ optional($complaint->user)->name }}</td>
                <td>{{ $complaint->order_id }} </td>
                <td>{{ $complaint->description }}</td>
                <td>{{ $complaint->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>