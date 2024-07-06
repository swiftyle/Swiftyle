{{-- resources/views/admin/sub-category/create.blade.php --}}

@extends('layouts.modern-layout.master')

@section('title')
    Add Sub Categories
@endsection

@push('css')
<!-- Add any custom CSS here -->
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Add Sub Categories</h3>
        @endslot
        <li class="breadcrumb-item">Sub Categories</li>
        <li class="breadcrumb-item active">Add</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex mb-3">
                    <div class="card-body">
                        <form method="POST" action="{{ route('sub-categories.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="name">Name</label>
                                    <input class="form-control" id="name" name="name" type="text" placeholder="Enter Category Name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="description">Description</label>
                                    <input class="form-control" id="description" name="description" type="text" placeholder="Enter Description" value="{{ old('description') }}">
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="main_category_id">Main Category</label>
                                    <select class="form-select" id="main_category_id" name="main_category_id" required>
                                        <option selected disabled value="">Choose...</option>
                                        @foreach($mainCategories as $mainCategory)
                                            <option value="{{ $mainCategory->id }}">{{ $mainCategory->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('main_category_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
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
@endsection

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
