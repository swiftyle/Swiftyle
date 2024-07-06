@extends('layouts.modern-layout.master')

@section('title')
    User Data Table
    {{ $title }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card full-card">
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
                    <a href="{{ route('users.printuser') }}" class="btn btn-info">Print </a>
                    <a href="{{ route('users.exportexceluser') }}" class="btn btn-success">Export to Excel</a>
                </div>
                <div class="table-responsive">
                    @include('admin.users.tableuser', $users)
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
