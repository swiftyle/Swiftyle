@extends('layouts.modern-layout.master')

@section('title')
    Data Product Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Data Product</h3>
        @endslot
        <li class="breadcrumb-item">Data</li>
        <li class="breadcrumb-item active">Product</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <input class="form-control" type="text" id="searchInput" style="max-width: 300px" placeholder="Search..">
                            <div class="ms-2">
                                <a href="{{ route('product.export-data-product') }}" class="btn btn-info"><span class="fa fa-file-archive-o"></span> Export </a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="paginationSelect" class="form-label me-2">Items per page:</label>
                        <select id="paginationSelect" class="form-select" onchange="changePagination(this.value)">
                            <option value="10" {{ $products->perPage() == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $products->perPage() == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $products->perPage() == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $products->perPage() == 100 ? 'selected' : '' }}>100</option>
                            <option value="-1" {{ $products->perPage() == -1 ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordernone" id="productTable">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Styles</th>
                                <th>Main Category</th>
                                <th>Sell</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="media">
                                            <img class="img-fluid rounded-circle" src="{{ $product->image }}" alt="" width="30px" height="30px">
                                            <div class="media-body">
                                                <span>{{ $product->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>
                                        @foreach ($product->styles as $style)
                                            {{ $style->name }}@if (!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($product->subcategories->isNotEmpty())
                                            @foreach ($product->subcategories as $subcategory)
                                                @if ($subcategory->mainCategory)
                                                    {{ $subcategory->mainCategory->name }}@if (!$loop->last), @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $product->sell }}</td>
                                    <td>{{ $product->rating }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination justify-content-center pagination-primary">
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($products->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if ($page == $products->currentPage())
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
                        @if ($products->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">&raquo;</a>
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
            var tableRows = document.querySelectorAll('#productTable tbody tr');

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
