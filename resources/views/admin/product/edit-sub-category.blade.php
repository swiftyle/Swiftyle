@extends('layouts.modern-layout.master')

@section('title')
    Edit Sub Category
    {{ $title }}
@endsection

@push('css')
<!-- Add any custom CSS here -->
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Edit Sub Category</h3>
        @endslot
        <li class="breadcrumb-item">Sub Categories</li>
        <li class="breadcrumb-item active">Edit</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('sub-categories.update', $subCategory->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $subCategory->name) }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="description">Description</label>
                            <input class="form-control" id="description" name="description" type="text" value="{{ old('description', $subCategory->description) }}">
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="main_category_id">Main Category</label>
                            <select class="form-select" id="main_category_id" name="main_category_id" required>
                                <option selected disabled value="">Choose...</option>
                                @foreach($mainCategories as $mainCategory)
                                    <option value="{{ $mainCategory->id }}" {{ $subCategory->main_category_id == $mainCategory->id ? 'selected' : '' }}>{{ $mainCategory->name }}</option>
                                @endforeach
                            </select>
                            @error('main_category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3" type="submit">Update</button>
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-info mt-3">Back</a>
                </form>
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
