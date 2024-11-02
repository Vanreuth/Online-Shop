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
                    <h4 class="card-title">Products</h4>
                    <div class="d-flex">
                        <div class="input-group">
                            <input type="text" id="searchBox" class="form-control" placeholder="Search by name">
                            <button class="btn btn-outline-primary ml-2 searchBtn">Search</button>
                        </div>
                        <button onclick="ProductRefresh()" class="btn btn-outline-danger rounded-0 btn-sm">Refresh</button>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateProduct">New
                        Product</button>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Product Image</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="product_list">
                        <!-- Products will be populated here -->
                    </tbody>
                </table>

                <!-- Pagination -->
                {{-- <div class="mt-3" id="paginationContainer"></div> --}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const ListProduct = (page = 1, search = '') => {
            $.ajax({
                type: 'POST',
                url: "{{ route('product.list') }}", // Ensure this route exists
                data: {
                    "page": page,
                    "search": search
                },
                datatype: "json",
                success: function(response) {
                    if (response.status == 200) {
                        let products = response.products; // Change to 'products'
                        let tr = '';
                        $.each(products, function(key, value) {
                            tr += `
                        <tr>
                            <td>${value.id}</td>
                            <td>${value.name}</td>
                            <td>
                                ${value.image ? `<img src="{{ asset('uploads/products/${value.image}') }}" alt="Product Image" width="50">` : 'No Image'}
                            </td>
                            <td>${value.category.name}</td>
                            <td>${value.brand.name}</td>
                            <td>${value.price}</td>
                            <td>${value.qty}</td>
                             <td>
                        ${value.qty > 0 ? '<span class="badge badge-success">In Stock</span>' : '<span class="badge badge-danger">Out of Stock</span>'}
                    </td>

                            <td>
                                ${(value.status == 1) ? '<span class="badge badge-success p-2">Active</span>' : '<span class="badge badge-danger p-2">Inactive</span>'}
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditProduct" onclick="editProduct(${value.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="ProductDelete(${value.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                        });
                        // Update the product list in the table body
                        $('.product_list').html(tr);
                    } else {
                        alert('Failed to fetch products');
                    }

                    // Pagination
                    let totalPage = response.page.totalPage;
                    let currentPage = response.page.currentPage;
                    let pageHtml = `
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center bg-danger">
                        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                            <a class="page-link" href="javascript:void(0);" onclick="ListProduct(${currentPage - 1})">Previous</a>
                        </li>`;

                    for (let i = 1; i <= totalPage; i++) {
                        pageHtml += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="ListProduct(${i})">${i}</a>
                    </li>`;
                    }

                    pageHtml += `
                        <li class="page-item ${currentPage === totalPage ? 'disabled' : ''}">
                            <a class="page-link" href="javascript:void(0);" onclick="ListProduct(${currentPage + 1})">Next</a>
                        </li>
                    </ul>
                </nav>`;

                    $('#paginationContainer').html(pageHtml);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('An error occurred while fetching products');
                }
            });
        };

        // Function to refresh product list
        const ProductRefresh = () => {
            const search = $('#searchBox').val();
            ListProduct(1, search); // Reset to page 1 on refresh
        };

        const UploadImage = (form) => {
            let payloads = new FormData($(form)[0]);
            $.ajax({
                type: 'POST',
                url: "{{ route('product.upload') }}",
                data: payloads,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 200) {
                        let images = response
                        .image; 
                        let img = ''; 
                        $.each(images, function(key, value) {
                            img += `
                <input type="hidden" name="image[]" value="${value}">
                <div class="uploaded-image-wrapper" style="position: relative; display: inline-block; width: 300px;">
                    <img src="{{ asset('uploads/temp/${value}') }}" alt="Uploaded Image" class="img-thumbnail">
                    <button type="button" class="btn btn-danger btn-sm clear-button" style="position: absolute; top: 10px; right: 10px; z-index: 1;" onclick="CancelImage('${value}')">
                        Cancel
                    </button>
                </div>
            `;
                        });
                        $('#image-preview').html(img);
                    } else {
                        console.error('Failed to upload image:', response.message);
                    }
                }

            });
        };

        const CancelImage = (img) => {
            if (confirm("Do you want to cancel the image?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('category.cancel') }}",
                    data: {
                        "image": img
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 200) {
                            $('#image-preview').html("");
                            Message('Image canceled successfully!');
                        } else {
                            Message('Failed to cancel the image');
                        }
                    }
                });
            }
        }
        ListProduct();



    </script>
@endsection
