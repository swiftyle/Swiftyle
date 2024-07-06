<!-- File: resources/views/orders/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Order Table - Print</title>
    <!-- Include CSS file for styling (if any) -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>
<body>
    <h1>Data Order</h1>

    <table>
        <thead>
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
                            {{ $order->shipping->shipping_address }}
                        @else
                            No shipping address found
                        @endif
                    </td>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- JavaScript for printing the page -->
    <script>
        window.print();
    </script>
</body>
</html>
