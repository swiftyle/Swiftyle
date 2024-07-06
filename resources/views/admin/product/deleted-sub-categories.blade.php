@extends('layouts.modern-layout.master')

@section('title')
    Deleted Data Sub Categories
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Deleted Data Sub Categories</h3>
        @endslot
        <li class="breadcrumb-item">Sub Categories</li>
        <li class="breadcrumb-item active">Deleted</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordernone">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Main Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deletedSubCategories as $subCategory)
                            <tr>
                                <td>{{ $subCategory->name }}</td>
                                <td>{{ $subCategory->description }}</td>
                                <td>{{ $subCategory->mainCategory->name }}</td>
                                <td>
                                    <form action="{{ route('sub-categories.restore', $subCategory->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $deletedSubCategories->links() }}
            </div>
        </div>
    </div>
@endsection
