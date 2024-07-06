@extends('layouts.modern-layout.master')

@section('title')
    Data Shop Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Data Shop</h3>
        @endslot
        <li class="breadcrumb-item">Shop</li>
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
                                <a href="{{ route('seller.export-data-shop') }}" class="btn btn-info"><span class="fa fa-file-archive-o"></span> Export </a>
                                <!-- <a href="{{ route('seller.add-shop') }}" class="btn btn-success"><span class="fa fa-plus"></span> Add Shop </a> -->
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="paginationSelect" class="form-label me-2">Items per page:</label>
                        <select id="paginationSelect" class="form-select" onchange="changePagination(this.value)">
                            <option value="10" {{ $shops->perPage() == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $shops->perPage() == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $shops->perPage() == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $shops->perPage() == 100 ? 'selected' : '' }}>100</option>
                            <option value="-1" {{ $shops->perPage() == -1 ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="dataShopTable" class="table table-bordernone">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Seller</th>
                                <th>Rating</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shops as $shop)
                                <tr>
                                    <td>
                                        <div class="media">
                                            <img class="img-fluid rounded-circle" src="{{ $shop->logo }}" alt="" width="30px" height="30px">
                                            <div class="media-body">
                                                <span>{{ $shop->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $shop->user->name }}</td>
                                    <td>{{ $shop->rating }}</td>
                                    <td>{{ $shop->email }}</td>
                                    <td>{{ $shop->address }}</td>
                                    <td>{{ $shop->phone }}</td>
                                    <td>
                                        <a href="" class="btn btn-success" style="width: 120px;">Edit</a>
                                        <a href="" class="btn btn-danger" style="width: 120px;">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination justify-content-center pagination-primary">
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($shops->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $shops->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($shops->getUrlRange(1, $shops->lastPage()) as $page => $url)
                            @if ($page == $shops->currentPage())
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
                        @if ($shops->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $shops->nextPageUrl() }}" aria-label="Next">&raquo;</a>
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
        function changePagination(size) {
            var url = "{{ route('seller.index') }}";
            if (size != -1) {
                url += "?size=" + size;
            }
            window.location.href = url;
        }

        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchTerm = this.value.toLowerCase();
            var tableRows = document.querySelectorAll('#dataShopTable tbody tr');

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
@endsection
