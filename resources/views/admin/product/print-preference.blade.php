<!-- File: resources/views/preferences/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Preference Table - Print</title>
    <!-- Include CSS file for styling (if any) -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>
<body>
    <h1>Data Preference</h1>

    <table>
        <thead>
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

    <!-- JavaScript for printing the page -->
    <script>
        window.print();
    </script>
</body>
</html>
