@extends('layouts.modern-layout.master')

@section('title')
    Refund Request Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Refund Request</h3>
        @endslot
        <li class="breadcrumb-item">Refund</li>
        <li class="breadcrumb-item active">Request</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <input class="form-control" type="text" id="searchInput" style="max-width: 300px" placeholder="Search..">
                            <div class="ms-2">
                                <a href="{{ route('refund.export-refund-request') }}" class="btn btn-info"><span class="fa fa-file-archive-o"></span> Export </a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="paginationSelect" class="form-label me-2">Items per page:</label>
                        <select id="paginationSelect" class="form-select" onchange="changePagination(this.value)">
                            <option value="10" {{ $refundRequest->perPage() == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $refundRequest->perPage() == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $refundRequest->perPage() == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $refundRequest->perPage() == 100 ? 'selected' : '' }}>100</option>
                            <option value="-1" {{ $refundRequest->perPage() == -1 ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordernone" id="refundTable">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Order ID</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($refundRequest as $request)
                                <tr>
                                    <td>
                                        <div class="media">
                                            <img class="img-fluid rounded-circle" src="{{ optional($request->user)->avatar }}" alt="" width="30px" height="30px">
                                            <div class="media-body">
                                                <span>{{ optional($request->user)->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $request->order_id }}</td>
                                    <td>{{ $request->reason }}</td>
                                    <td>{{ $request->status }}</td>
                                    <td>
                                        @if ($request->status == 'pending')
                                            <form action="{{ route('refund-request.approve', $request->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Approved</button>
                                            </form>
                                            <form action="{{ route('refund-request.reject', $request->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Rejected</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination justify-content-center pagination-primary">
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($refundRequest->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $refundRequest->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($refundRequest->getUrlRange(1, $refundRequest->lastPage()) as $page => $url)
                            @if ($page == $refundRequest->currentPage())
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
                        @if ($refundRequest->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $refundRequest->nextPageUrl() }}" aria-label="Next">&raquo;</a>
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
    @push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchTerm = this.value.toLowerCase();
            var tableRows = document.querySelectorAll('#refundTable tbody tr');

            tableRows.forEach(function(row) {
                var rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });

        function changePagination(size) {
            var url = "{{ route('refund-request.index') }}";
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
