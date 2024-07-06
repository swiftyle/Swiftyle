<!-- File: resources/views/order-histories/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Print</title>
    <!-- Include CSS file for styling (if any) -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>
<body>
    <h1>Order History</h1>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderHistories as $orderHistory)
                <tr>
                    <td>{{ $orderHistory->order_id }}</td>
                    <td>{{ $orderHistory->description }}</td>
                    <td>{{ $orderHistory->status }}</td>
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
