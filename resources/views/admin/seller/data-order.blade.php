@extends('layouts.modern-layout.master')

@section('title')
    Data Order Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Data Order</h3>
        @endslot
        <li class="breadcrumb-item">Order</li>
        <li class="breadcrumb-item active">Data</li>
    @endcomponent

    <div class="container-fluid">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordernone">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>User</th>
                                <th>Shipping</th>
                                <th>Status</th>
                                <th>
                                    <div class="setting-list">
                                        <ul class="list-unstyled setting-option">
                                            <li>
                                                <div class="setting-primary"><i class="icon-settings"> </i></div>
                                            </li>
                                            <li><i class="view-html fa fa-code font-primary"></i></li>
                                            <li><i class="icofont icofont-maximize full-card font-primary"></i></li>
                                            <li><i class="icofont icofont-minus minimize-card font-primary"></i></li>
                                            <li><i class="icofont icofont-refresh reload-card font-primary"></i></li>
                                            <li><i class="icofont icofont-error close-card font-primary"></i></li>
                                        </ul>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        <p>{{ $order->id }}</p>
                                    </td>
                                    <td>
                                        <div class="media">
                                            <img class="img-fluid rounded-circle" src="{{ $order->user->avatar }}"
                                                alt="" width="30px" height="30px">
                                            <div class="media-body">
                                                <span>{{ $order->user->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($order->shipping)
                                            <p>{{ $order->shipping->shipping_address }}</p>
                                        @else
                                            <p>No shipping address found</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p>{{ ucfirst($order->status) }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            if (window.history && window.history.pushState) {
                window.history.pushState(null, null, window.location.href);
                window.onpopstate = function() {
                    window.history.pushState(null, null, window.location.href);
                };
            }
        </script>


    @endpush
@endsection
