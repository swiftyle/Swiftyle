@extends('layouts.modern-layout.master')

@section('title')
    Refund Request Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Refund Request</h3>
        @endslot
        <li class="breadcrumb-item">Refund</li>
        <li class="breadcrumb-item active">Request</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card full-card">
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('refund-request.index') }}" class="btn btn-primary">Back</a>
                        <a href="{{ route('refund.print-refund-request') }}" class="btn btn-info">Print</a>
                        <a href="{{ route('refund.export-excel-refund-request') }}" class="btn btn-success">Export to Excel</a>
                    </div>
                <div class="table-responsive">
                    @include('admin.refund.table-refund-request', ['refundRequest' => $refundRequest])
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
