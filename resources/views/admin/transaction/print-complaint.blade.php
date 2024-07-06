<!-- File: resources/views/complain-user/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complain User - Print</title>
    <!-- Include CSS file for styling (if any) -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>
<body>
    <h1>Complain User</h1>
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
            @foreach ($complaints as $complaint)
                <tr>
                    <td>{{ optional($complaint->user)->name }}</td>
                    <td>{{ $complaint->order_id }}</td>
                    <td>{{ $complaint->description }}</td>
                    <td>{{ $complaint->status }}</td>
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
