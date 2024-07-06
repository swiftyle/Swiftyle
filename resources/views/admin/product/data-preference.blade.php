@extends('layouts.modern-layout.master')

@section('title')
    Data Preference Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Data Preference</h3>
        @endslot
        <li class="breadcrumb-item">Data</li>
        <li class="breadcrumb-item active">Preference</li>
    @endcomponent
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <input class="form-control" type="text" id="searchInput" style="max-width: 300px" placeholder="Search..">
                            <div class="ms-2">
                                <a href="{{ route('product.export-data-preference') }}" class="btn btn-info"><span class="fa fa-file-archive-o"></span> Export </a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="paginationSelect" class="form-label me-2">Items per page:</label>
                        <select id="paginationSelect" class="form-select" onchange="changePagination(this.value)">
                            <option value="10" {{ $preferences instanceof \Illuminate\Pagination\LengthAwarePaginator && $preferences->perPage() == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $preferences instanceof \Illuminate\Pagination\LengthAwarePaginator && $preferences->perPage() == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $preferences instanceof \Illuminate\Pagination\LengthAwarePaginator && $preferences->perPage() == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $preferences instanceof \Illuminate\Pagination\LengthAwarePaginator && $preferences->perPage() == 100 ? 'selected' : '' }}>100</option>
                            <option value="-1" {{ !$preferences instanceof \Illuminate\Pagination\LengthAwarePaginator ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordernone" id="preferenceTable">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Style</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($preferences as $preference)
                                <tr>
                                    <td>{{ $preference->user->name }}</td>
                                    <td>{{ $preference->style->name }}</td>                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($preferences instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="pagination justify-content-center pagination-primary">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($preferences->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $preferences->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($preferences->getUrlRange(1, $preferences->lastPage()) as $page => $url)
                                @if ($page == $preferences->currentPage())
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
                            @if ($preferences->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $preferences->nextPageUrl() }}" aria-label="Next">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function changePagination(size) {
            var url = "{{ route('preferences.index') }}";
            if (size != -1) {
                url += "?size=" + size;
            }
            window.location.href = url;
        }

        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchTerm = this.value.toLowerCase();
            var tableRows = document.querySelectorAll('#preferenceTable tbody tr');

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
