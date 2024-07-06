<!-- File: resources/views/styles/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Style Table - Print</title>
    <!-- Include CSS file for styling (if any) -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>
<body>
    <h1>Data Style</h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($styles as $style)
                <tr>
                    <td>{{ $style->name }}</td>
                    <td>{{ $style->description }}</td>
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
