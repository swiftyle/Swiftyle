@extends('layouts.modern-layout.master')

@section('title')
    Deleted Data Styles
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Deleted Data Styles</h3>
        @endslot
        <li class="breadcrumb-item">Styles</li>
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
                        @foreach ($deletedStyles as $style)
                            <tr>
                                <td>{{ $style->name }}</td>
                                <td>{{ $style->description }}</td>
                                <td>
                                    <form action="{{ route('styles.restore', $style->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $deletedStyles->links() }}
            </div>
        </div>
    </div>
@endsection
