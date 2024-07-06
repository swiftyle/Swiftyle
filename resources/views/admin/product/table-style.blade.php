<table id="styleTable" class="table table-bordernone">
    <thead>
        <tr>
            <th colspan="2" style="font-size: 14px; font-weight: bold;">Data Style</th>
        </tr>
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
