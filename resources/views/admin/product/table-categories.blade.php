<table class="table table-bordernone">
    <thead>
        <tr>
            <th colspan="2" style="font-size: 14px; font-weight: bold;">Data Category</th>
        </tr>
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
