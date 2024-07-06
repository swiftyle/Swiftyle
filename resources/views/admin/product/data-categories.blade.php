@extends('layouts.modern-layout.master')

@section('title')
    Data Main Category Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Data Main Category</h3>
        @endslot
        <li class="breadcrumb-item">Data</li>
        <li class="breadcrumb-item active">Main Category</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <input class="form-control" type="text" id="searchInput" style="max-width: 300px" placeholder="Search..">
                            <div class="ms-2">
                                <a href="{{ route('product.export-data-categories') }}" class="btn btn-info"><span class="fa fa-file-archive-o"></span> Export </a>
                                <a href="{{ route('product.add-categories') }}" class="btn btn-success"><span class="fa fa-plus"></span> Add Categories </a>
                                <a href="{{ route('product.deleted-categories') }}" class="btn btn-light"><span class="fa fa-trash"></span> View Deleted Categories</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="paginationSelect" class="form-label me-2">Items per page:</label>
                        <select id="paginationSelect" class="form-select" onchange="changePagination(this.value)">
                            <option value="10" {{ $mainCategories->perPage() == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $mainCategories->perPage() == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $mainCategories->perPage() == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $mainCategories->perPage() == 100 ? 'selected' : '' }}>100</option>
                            <option value="-1" {{ $mainCategories->perPage() == -1 ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="categoriesTable" class="table table-bordernone">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mainCategories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td>
                                        @if($category->trashed())
                                            <form action="{{ route('categories.restore', $category->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success">Restore</button>
                                            </form>
                                        @else
                                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-success">Edit</a>
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
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
                        @if ($mainCategories->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $mainCategories->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($mainCategories->getUrlRange(1, $mainCategories->lastPage()) as $page => $url)
                            @if ($page == $mainCategories->currentPage())
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
                        @if ($mainCategories->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $mainCategories->nextPageUrl() }}" aria-label="Next">&raquo;</a>
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
            var url = "{{ route('categories.index') }}";
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
