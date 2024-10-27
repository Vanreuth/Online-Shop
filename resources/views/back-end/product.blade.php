@extends('back-end.components.master')

@section('contens')
@include('back-end.messages.product.create')
@include('back-end.messages.product.edit')
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header">
                <h4 class="page-title">Product Management</h4>
            </div>
        </div>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Colors</h4>
                    <div class="d-flex">
                        <div class="input-group">
                        <input type="text" id="searchBox" class="form-control" placeholder="Search by name">
                        <button class="btn btn-outline-primary ml-2 searchBtn">Search</button>
                    </div>
                    <button onclick="ColorRefresh()" class="btn btn-outline-danger rounded-0 btn-sm">Refresh</button>
                </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateProduct">New Color</button>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Product Image</th>
                            <th>Cateogry</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="product_list">
                        <!-- Colors will be populated here -->
                    </tbody>
                </table>

                <!-- Pagination -->
                {{-- <div class="mt-3" id="paginationContainer"></div> --}}
            </div>
        </div>
    </div>

@endsection