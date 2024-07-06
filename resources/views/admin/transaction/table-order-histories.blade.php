<table class="table table-bordernone">
                        <thead>
                            <tr>
                                <th colspan="4" style="font-size: 14px; font-weight: bold;">Data Order History</th>
                            </tr>
                            <tr>
                                <th>Order ID</th>
                                <th>Desrciption</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderHistories as $orderHistory)
                                <tr>
                                    <td>{{ $orderHistory->order_id }}</td>
                                    <td>{{ $orderHistory->description }}</td>
                                    <td>{{ $orderHistory->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>