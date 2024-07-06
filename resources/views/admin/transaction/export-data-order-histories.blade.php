@extends('layouts.modern-layout.master')

@section('title')
    Order History Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Order History</h3>
        @endslot
        <li class="breadcrumb-item">Data</li>
        <li class="breadcrumb-item active">History</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card full-card">
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('order.histories.index') }}" class="btn btn-primary">Back</a>
                        <a href="{{ route('transaction.print-order-histories') }}" class="btn btn-info">Print</a>
                        <a href="{{ route('transaction.export-excel-order-histories') }}" class="btn btn-success">Export to Excel</a>
                    </div>
                <div class="table-responsive">
                    @include('admin.transaction.table-order-histories', ['orderHistories' => $orderHistories])
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
