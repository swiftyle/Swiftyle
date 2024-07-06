<table class="table table-bordernone">
    <thead>
        <tr>
            <th colspan="4" style="font-size: 14px; font-weight: bold;">Data Order</th>
        </tr>
        <tr>
            <th>Transaction ID</th>
            <th>User</th>
            <th>Shipping</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td>
                    @if ($order->shipping)
                        <p>{{ $order->shipping->shipping_address }}</p>
                    @else
                        <p>No shipping address found</p>
                    @endif
                </td>
                <td>{{ ucfirst($order->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
