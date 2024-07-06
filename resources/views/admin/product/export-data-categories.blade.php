@extends('layouts.modern-layout.master')

@section('title')
    Export Data Main Category
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Export Data Main Category</h3>
        @endslot
        <li class="breadcrumb-item">Export</li>
        <li class="breadcrumb-item active">Main Category</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card full-card">
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
                        <a href="{{ route('product.print-categories') }}" class="btn btn-info">Print</a>
                        <a href="{{ route('product.export-excel-categories') }}" class="btn btn-success">Export to Excel</a>
                    </div>
                    <div class="table-responsive">
                        @include('admin.product.table-categories', ['mainCategories' => $mainCategories])
                    </div>
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
