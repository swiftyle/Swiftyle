<table class="table table-bordernone" id="productTable">
    <thead>
        <tr>
            <th colspan="8" style="font-size: 14px; font-weight: bold;">Data Product</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Styles</th>
            <th>Main Category</th>
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
