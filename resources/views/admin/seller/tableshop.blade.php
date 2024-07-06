
<table class="table table-bordernone">
    <thead>
        <tr>
            <th colspan="9" style="font-size: 14px; font-weight: bold;">Data Shops</th> <!-- Adjust the colspan as needed -->
        </tr>
        <tr>
            <th>Name</th>
            <th>Seller</th>
            <th>Rating</th>
            <th>Email</th>
            <th>Addres</th>
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
