@extends('layouts.modern-layout.master')

@section('title')
    Data Style Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Data Style</h3>
        @endslot
        <li class="breadcrumb-item">Data</li>
        <li class="breadcrumb-item active">Style</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <input class="form-control" type="text" id="searchInput" style="max-width: 300px" placeholder="Search..">
                            <div class="ms-2">
                                <a href="{{ route('product.export-data-styles') }}" class="btn btn-info"><span class="fa fa-file-archive-o"></span> Export </a>
                                <a href="{{ route('product.add-styles') }}" class="btn btn-success"><span class="fa fa-plus"></span> Add Style </a>
                                <a href="{{ route('product.deleted-styles') }}" class="btn btn-light"><span class="fa fa-trash"></span> View Deleted Styles</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="paginationSelect" class="form-label me-2">Items per page:</label>
                        <select id="paginationSelect" class="form-select" onchange="changePagination(this.value)">
                            <option value="10" {{ $styles->perPage() == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $styles->perPage() == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $styles->perPage() == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $styles->perPage() == 100 ? 'selected' : '' }}>100</option>
                            <option value="-1" {{ $styles->perPage() == -1 ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="styleTable" class="table table-bordernone">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($styles as $style)
                                <tr>
                                    <td>{{ $style->name }}</td>
                                    <td>{{ $style->description }}</td> 
                                    <td>
                                        @if($style->trashed())
                                            <form action="{{ route('styles.restore', $style->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success" style="width: 120px;">Restore</button>
                                            </form>
                                        @else
                                            <a href="{{ route('styles.edit', $style->id) }}" class="btn btn-success" style="width: 120px;">Edit</a>
                                            <form action="{{ route('styles.destroy', $style->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" style="width: 120px;">Delete</button>
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
                        @if ($styles->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $styles->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($styles->getUrlRange(1, $styles->lastPage()) as $page => $url)
                            @if ($page == $styles->currentPage())
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
                        @if ($styles->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $styles->nextPageUrl() }}" aria-label="Next">&raquo;</a>
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
@endsection

@push('scripts')
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        var searchTerm = this.value.toLowerCase();
        var tableRows = document.querySelectorAll('#styleTable tbody tr');

        tableRows.forEach(function(row) {
            var rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchTerm) ? '' : 'none';
        });
    });

    function changePagination(size) {
        var url = "{{ route('styles.index') }}";
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
    