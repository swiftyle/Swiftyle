@extends('layouts.modern-layout.master')

@section('title')
    Data Product Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Data Product</h3>
        @endslot
        <li class="breadcrumb-item">Data</li>
        <li class="breadcrumb-item active">Product</li>
    @endcomponent

    <div class="container-fluid">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordernone">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Style</th>
                                <th>Category</th>
                                
                                <th>Sell</th>
                                <th>Rating</th>
                                <th>
                                    <div class="setting-list">
                                        <ul class="list-unstyled setting-option">
                                            <li>
                                                <div class="setting-primary"><i class="icon-settings"> </i></div>
                                            </li>
                                            <li><i class="view-html fa fa-code font-primary"></i></li>
                                            <li><i class="icofont icofont-maximize full-card font-primary"></i></li>
                                            <li><i class="icofont icofont-minus minimize-card font-primary"></i></li>
                                            <li><i class="icofont icofont-refresh reload-card font-primary"></i></li>
                                            <li><i class="icofont icofont-error close-card font-primary"></i></li>
                                        </ul>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <div class="media">
                                            <img class="img-fluid rounded-circle" src="{{ $product->image }}"
                                                alt="" width="30px" height="30px">
                                            <div class="media-body">
                                                <span>{{ $product->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p>{{ $product->description }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $product->price }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $product->image }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $product->sell }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $product->rating }}</p>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
