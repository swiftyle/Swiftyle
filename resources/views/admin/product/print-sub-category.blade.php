<!-- File: resources/views/sub-category/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sub Category - Print</title>
    <!-- Include CSS file for styling (if any) -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>
<body>
    <h1>Data Sub Category</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Main Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subCategories as $subCategory)
                <tr>
                    <td>{{ $subCategory->name }}</td>
                    <td>{{ $subCategory->description }}</td>
                    <td>{{ $subCategory->mainCategory->name }}</td> 
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
