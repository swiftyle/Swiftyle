<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Main Category Table - Print</title>
    <!-- Include any CSS files for styling -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>
<body>
    <h1>Data Main Category</h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mainCategories as $mainCategory)
                <tr>
                    <td>{{ $mainCategory->name }}</td>
                    <td>{{ $mainCategory->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Include any JavaScript needed for the print functionality -->
    <script>
        window.print();
    </script>
</body>
</html>
