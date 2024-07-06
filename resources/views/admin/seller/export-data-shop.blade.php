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
            <div class="card full-card">
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('seller.index') }}" class="btn btn-primary">Back</a>
                        <a href="{{ route('seller.print-shop') }}" class="btn btn-info">Print</a>
                        <a href="{{ route('seller.export-exce-shop') }}" class="btn btn-success">Export to Excel</a>
                    </div>
                    <div class="table-responsive">
                        @include('admin.seller.tableshop', $shops)
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
