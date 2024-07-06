<!-- File: resources/views/products/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Product - Print</title>
    <!-- Include CSS file for styling (if any) -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>
<body>
    <h1>Data Product</h1>
    <table>
        <thead>
            <tr>
                <th>Number</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Style</th>
                <th>Category</th>
                <th>Sell</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        @foreach ($product->styles as $style)
                            {{ $style->name }}@if (!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>
                        @if ($product->subcategories->isNotEmpty())
                            @foreach ($product->subcategories as $subcategory)
                                @if ($subcategory->mainCategory)
                                    {{ $subcategory->mainCategory->name }}@if (!$loop->last), @endif
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $product->sell }}</td>
                    <td>{{ $product->rating }}</td>
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
