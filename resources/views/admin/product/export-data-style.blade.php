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
            <div class="card full-card">
                <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('styles.index') }}" class="btn btn-primary">Back</a>
                    <a href="{{ route('product.print-styles') }}" class="btn btn-info">Print</a>
                    <a href="{{ route('product.export-excel-styles') }}" class="btn btn-success">Export to Excel</a>
                </div>
                <div class="table-responsive">
                    @include('admin.product.table-style', ['styles' => $styles])
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
