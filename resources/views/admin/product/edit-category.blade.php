@extends('layouts.modern-layout.master')

@section('title')
    Edit Category
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Edit Category</h3>
        @endslot
        <li class="breadcrumb-item">Categories</li>
        <li class="breadcrumb-item active">Edit</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="validationDefault01">Name</label>
                            <input class="form-control" id="validationDefault01" name="name" type="text" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationDefault02">Description</label>
                            <input class="form-control" id="validationDefault02" name="description" type="text" value="{{ old('description', $category->description) }}">
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3" type="submit">Update</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-info mt-3">Back</a>
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
