<table class="table table-bordernone">
    <thead>
        <tr>
            <th colspan="4" style="font-size: 14px; font-weight: bold;">Data Refund Request</th>
        </tr>
        <tr>
            <th>User</th>
            <th>Order ID</th>
            <th>Description</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($refundRequest as $request)
            <tr>
                <td>{{ optional($request->user)->name }}</td>
                <td>{{ $request->order_id }}</td>
                <td>{{ $request->reason }}</td>
                <td>{{ $request->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>