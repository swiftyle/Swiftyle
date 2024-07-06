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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <input class="form-control" type="text" id="searchInput" style="max-width: 300px" placeholder="Search..">
                            <div class="ms-2">
                                <a href="{{ route('product.export-data-order') }}" class="btn btn-info"><span class="fa fa-file-archive-o"></span> Export </a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="paginationSelect" class="form-label me-2">Items per page:</label>
                        <select id="paginationSelect" class="form-select" onchange="changePagination(this.value)">
                            <option value="10" {{ $orders->perPage() == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $orders->perPage() == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $orders->perPage() == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $orders->perPage() == 100 ? 'selected' : '' }}>100</option>
                            <option value="-1" {{ $orders->perPage() == -1 ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordernone" id="orderTable">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>User</th>
                                <th>Shipping</th>
                                <th>Status</th>
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
                <div class="pagination justify-content-center pagination-primary">
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($orders->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $orders->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                            @if ($page == $orders->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($orders->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $orders->nextPageUrl() }}" aria-label="Next">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
    <script>
        function changePagination(size) {
            var url = "{{ route('orders.index') }}";
            if (size != -1) {
                url += "?size=" + size;
            }
            window.location.href = url;
        }

        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchTerm = this.value.toLowerCase();
            var tableRows = document.querySelectorAll('#orderTable tbody tr');

            tableRows.forEach(function(row) {
                var rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });

        if (window.history && window.history.pushState) {
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, null, window.location.href);
            };
        }
    </script>
    @endpush
