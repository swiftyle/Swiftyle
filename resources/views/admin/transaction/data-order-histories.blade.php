@extends('layouts.modern-layout.master')

@section('title')
    Order History Table
    {{ $title }}
@endsection

@push('css')
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Order History</h3>
        @endslot
        <li class="breadcrumb-item">Data</li>
        <li class="breadcrumb-item active">History</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <input class="form-control" type="text" id="searchInput" style="max-width: 300px" placeholder="Search..">
                            <div class="ms-2">
                                <a href="{{ route('transaction.export-data-order-histories') }}" class="btn btn-info"><span class="fa fa-file-archive-o"></span> Export </a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="paginationSelect" class="form-label me-2">Items per page:</label>
                        <select id="paginationSelect" class="form-select" onchange="changePagination(this.value)">
                            <option value="10" {{ $orderHistories->perPage() == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $orderHistories->perPage() == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $orderHistories->perPage() == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $orderHistories->perPage() == 100 ? 'selected' : '' }}>100</option>
                            <option value="-1" {{ $orderHistories->perPage() == -1 ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordernone" id="orderTable">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Description</th>
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
                </div>
                <div class="pagination justify-content-center pagination-primary">
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($orderHistories->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $orderHistories->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($orderHistories->getUrlRange(1, $orderHistories->lastPage()) as $page => $url)
                            @if ($page == $orderHistories->currentPage())
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
                        @if ($orderHistories->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $orderHistories->nextPageUrl() }}" aria-label="Next">&raquo;</a>
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
    </div>

    @push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchTerm = this.value.toLowerCase();
            var tableRows = document.querySelectorAll('#orderTable tbody tr');

            tableRows.forEach(function(row) {
                var rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });

        function changePagination(size) {
            var url = "{{ route('order.histories.index') }}";
            if (size != -1) {
                url += "?size=" + size;
            }
            window.location.href = url;
        }

        if (window.history && window.history.pushState) {
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, null, window.location.href);
            };
        }
    </script>
    @endpush
@endsection
