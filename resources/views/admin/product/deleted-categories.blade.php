@extends('layouts.modern-layout.master')

@section('title')
    Deleted Data Categories
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Deleted Data Categories</h3>
        @endslot
        <li class="breadcrumb-item">Categories</li>
        <li class="breadcrumb-item active">Data</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordernone">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deletedCategories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description }}</td>
                                <td>
                                    <form action="{{ route('categories.restore', $category->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $deletedCategories->links() }}
            </div>
        </div>
    </div>
@endsection
