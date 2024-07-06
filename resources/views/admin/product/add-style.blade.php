@extends('layouts.modern-layout.master')

@section('title')
    Add Style
    {{ $title }}
@endsection

@push('css')
    <!-- Add any custom CSS here if needed -->
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Add Style</h3>
        @endslot
        <li class="breadcrumb-item">Style</li>
        <li class="breadcrumb-item active">Add</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex mb-3">
                    <div class="card-body">
                        <form action="{{ route('styles.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="name">Name</label>
                                    <input class="form-control" id="name" name="name" type="text" placeholder="Enter Style Name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="description">Description</label>
                                    <input class="form-control" id="description" name="description" type="text" placeholder="Enter Description" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="image">Image</label>
                                    <input class="form-control" id="image" name="image" type="file" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <div class="checkbox p-0">
                                        <input class="form-check-input" id="agree" type="checkbox" required>
                                        <label class="form-check-label" for="agree">Agree to terms and conditions</label>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit form</button>
                            <a href="{{ route('styles.index') }}" class="btn btn-info">Back</a>
                        </form>
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
