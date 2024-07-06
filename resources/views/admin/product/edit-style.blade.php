@extends('layouts.modern-layout.master')

@section('title')
    Edit Style
@endsection

@push('css')
    <!-- Add any custom CSS here if needed -->
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Edit Style</h3>
        @endslot
        <li class="breadcrumb-item">Style</li>
        <li class="breadcrumb-item active">Edit</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex mb-3">
                    <div class="card-body">
                        <form action="{{ route('styles.update', $style->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="name">Name</label>
                                    <input class="form-control" id="name" name="name" type="text" value="{{ $style->name }}" placeholder="Enter Style Name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="description">Description</label>
                                    <input class="form-control" id="description" name="description" type="text" value="{{ $style->description }}" placeholder="Enter Description" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="image">Image</label>
                                    <input class="form-control" id="image" name="image" type="file">
                                    @if($style->image)
                                        <img src="{{ asset('storage/styles/' . $style->image) }}" alt="{{ $style->name }}" width="100">
                                    @endif
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
                            <button class="btn btn-primary" type="submit">Update Style</button>
                            <a href="{{ route('styles.index') }}" class="btn btn-info">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
