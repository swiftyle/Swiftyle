@extends('layouts.modern-layout.master')

@section('title')
    Data Sub Category Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Data Sub Category</h3>
        @endslot
        <li class="breadcrumb-item">Data</li>
        <li class="breadcrumb-item active">Sub Category</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <input class="form-control" type="text" id="searchInput" style="max-width: 300px" placeholder="Search..">
                            <div class="ms-2">
                                <a href="{{ route('product.export-data-sub-category') }}" class="btn btn-info"><span class="fa fa-file-archive-o"></span> Export </a>
                                <a href="{{ route('product.add-sub-category') }}" class="btn btn-success"><span class="fa fa-plus"></span> Add Sub Categories </a>
                                <a href="{{ route('product.deleted-sub-categories') }}" class="btn btn-light"><span class="fa fa-trash"></span> View Deleted Sub Categories</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="paginationSelect" class="form-label me-2">Items per page:</label>
                        <select id="paginationSelect" class="form-select" onchange="changePagination(this.value)">
                            <option value="10" {{ $subCategories->perPage() == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $subCategories->perPage() == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $subCategories->perPage() == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $subCategories->perPage() == 100 ? 'selected' : '' }}>100</option>
                            <option value="-1" {{ $subCategories->perPage() == -1 ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="categoriesTable" class="table table-bordernone">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Main Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subCategories as $subCategory)
                                <tr>
                                    <td>{{ $subCategory->name }}</td>
                                    <td>{{ $subCategory->description }}</td>
                                    <td>{{ $subCategory->mainCategory->name }}</td>
                                    <td>
                                        @if($subCategory->trashed())
                                            <form action="{{ route('sub-categories.restore', $subCategory->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success">Restore</button>
                                            </form>
                                        @else
                                            <a href="{{ route('sub-categories.edit', $subCategory->id) }}" class="btn btn-success">Edit</a>
                                            <form action="{{ route('sub-categories.destroy', $subCategory->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
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
                        @if ($subCategories->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $subCategories->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($subCategories->getUrlRange(1, $subCategories->lastPage()) as $page => $url)
                            @if ($page == $subCategories->currentPage())
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
                        @if ($subCategories->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $subCategories->nextPageUrl() }}" aria-label="Next">&raquo;</a>
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
            var tableRows = document.querySelectorAll('#categoriesTable tbody tr');

            tableRows.forEach(function(row) {
                var rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });

        function changePagination(size) {
            var url = "{{ route('sub-categories.index') }}";
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
