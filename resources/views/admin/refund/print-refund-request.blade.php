<!-- File: resources/views/refund-request/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Request - Print</title>
    <!-- Include CSS file for styling (if any) -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>
<body>
    <h1>Refund Request</h1>
    <table>
        <thead>
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
    <!-- JavaScript for printing the page -->
    <script>
        window.print();
    </script>
</body>
</html>
