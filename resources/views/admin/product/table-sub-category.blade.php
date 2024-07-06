<table class="table table-bordernone">
    <thead>
        <tr>
            <th colspan="2" style="font-size: 14px; font-weight: bold;">Data Sub Categories</th>
        </tr>
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
