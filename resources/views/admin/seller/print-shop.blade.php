<!-- File: resources/views/shops/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Data - Print</title>
    <!-- Include CSS file for styling (if any) -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>
<body>
    <h1>Shop Data</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Seller</th>
                <th>Rating</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shops as $shop)
                <tr>
                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->user->name }}</td>
                    <td>{{ $shop->rating }}</td>
                    <td>{{ $shop->email }}</td>
                    <td>{{ $shop->address }}</td>
                    <td>{{ $shop->phone }}</td>
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
