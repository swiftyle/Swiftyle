@extends('layouts.modern-layout.master')

@section('title')
    Add Sub Categories
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Add Sub Categories</h3>
        @endslot
        <li class="breadcrumb-item">Sub Categories</li>
        <li class="breadcrumb-item active">Data</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex mb-3">
                    <div class="card-body">
                        <form action="{{ route('sub-categories.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="validationDefault01">Name</label>
                                    <input class="form-control" id="validationDefault01" type="text" name="name" placeholder="Enter Sub Category Name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="validationDefault02">Description</label>
                                    <input class="form-control" id="validationDefault02" type="text" name="description" placeholder="Enter Description">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="validationDefault04">Main Categories</label>
                                    <select class="form-select" id="validationDefault04" name="main_category_id" required>
                                        <option selected disabled value="">Choose...</option>
                                        @foreach($mainCategories as $mainCategory)
                                            <option value="{{ $mainCategory->id }}">{{ $mainCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <div class="checkbox p-0">
                                        <input class="form-check-input" id="invalidCheck2" type="checkbox" required>
                                        <label class="form-check-label" for="invalidCheck2">Agree to terms and conditions</label>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit form</button>
                            <a href="{{ route('sub-categories.index') }}" class="btn btn-info">Back</a>
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
