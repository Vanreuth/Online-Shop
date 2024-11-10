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
                    <button class="btn btn-primary" onclick="handelClickProduct()" data-bs-toggle="modal"
                        data-bs-target="#modalCreateProduct">New
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
        $(document).ready(function() {
            $('.color-add').select2({
                placeholder: 'Select options',
                allowClear: true,
                tags: true,
            });
        });
        $(document).ready(function() {
            $('.color-add').select2({
                placeholder: 'Select options',
                allowClear: true,
                tags: true,
            });
        });
        $(document).ready(function() {
            $('.category-add').select2({
                placeholder: 'Select options',
                allowClear: true,
                tags: true,
            });
        });
        $(document).ready(function() {
            $('.brand-add').select2({
                placeholder: 'Select options',
                allowClear: true,
                tags: true,
            });
        });
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
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpdateProduct" onclick="editProduct(${value.id})">Edit</button>
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

        const handelClickProduct = () => {
            $.ajax({
                type: "POST",
                url: "{{ route('product.data') }}",
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        console.log(response);
                        
                        // category
                        let category = response.category;

                        $.each(category, function(key, value) {
                            let option = `
                                <option value="${value.id}">${value.name}</option>
                            `;
                            $('.category-add').append(option);

                        });

                        // Brand

                        let brand = response.brand;
                        $.each(brand, function(key, value) {
                            let option = `
                                <option value="${value.id}">${value.name}</option>
                            `;
                            $('.brand-add').append(option);
                        });


                        // color

                        let color = response.color;
                        $.each(color, function(key, value) {
                            let option = `
                                <option value="${value.id}">${value.name}</option>
                            `;
                            $('.color-add').append(option);
                        });

                        let relatedProduct = response.relatedProducts;
                        $.each(relatedProduct, function(key, value) {
                            let option = `
                                <option value="${value.id}">${value.name}</option>
                            `;
                            $('.related-add').append(option);
                        });


                    }



                }
            });

        }

        const StoreProduct = (form) => {
            let payloads = new FormData($(form)[0]);
            $.ajax({
                type: 'POST',
                url: "{{ route('product.store') }}",
                data: payloads,
                datatype: "json",
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 200) {
                        $('#modalCreateProduct').modal('hide');
                        $('#createProductForm').trigger("reset");
                        ListProduct(); // Refresh product list
                        Message('Product created successfully!'); // Use the Message function here
                    } else {
                        Message("Failed to create product",
                        true); // Use the Message function here for failure
                        if (response.errors) {
                            if (response.errors.title) {
                                $("input[name='title']").addClass("is-invalid").siblings("p").addClass(
                                    "text-danger").text(response.errors.title[0]);
                            }
                            if (response.errors.price) {
                                $("input[name='price']").addClass("is-invalid").siblings("p").addClass(
                                    "text-danger").text(response.errors.price[0]);
                            }
                           
                        }
                    }
                }
            });


        }
        
        const editProduct = (id) => {
       $.ajax({
        type: "POST",
        url: "{{ route('product.edit') }}",
        data: {
          'id' : id
        },
        dataType: "json",
        success: function (response) {
          if(response.status == 200){
           $('#name').val(response.data.product.name);
           $('#des').val(response.data.product.des);
           $('#price').val(response.data.product.price);
           $('#qty').val(response.data.product.qty);
           
             
            //categories start
            let categories = response.data.categories;
            let cate_option = ``;
            $.each(categories, function (key, value) { 
              cate_option += `
              <option value="${value.id}" ${(value.id == response.data.product.category_id) ? 'selected' : ''}>
                ${value.name}
              </option>
              `;
            });

            //inner to category edit 
            $('.category_edit').html(cate_option);
            //categories end
          }

          //brands start
          let brands = response.data.brands;
          let brand_option = ``;
          $.each(brands, function (key, value) { 
              brand_option += `
              <option value="${value.id}" ${(value.id == response.data.product.brand_id)? 'selected' : ''}>
                ${value.name}
              </option>
              `;
          }); 
          //inner to brand edit 
          $('.brand_edit').html(brand_option);
          //brands end


          //colors start
          let colors = response.data.colors;
          let color_ids = response.data.product.color; // 4,2,1
         
          //let find  = array.includes(5)  // => true or false => 1
          let color_option = ``;
          $.each(colors, function (key, value) { 
              if(color_ids.includes(String(value.id))){
                color_option += `
                   <option value="${value.id}" selected >${value.name}</option>
                `;
              }else{
                color_option += `
                  <option value="${value.id}">${value.name}</option>
                `;
              }
          }); 
          //inner to color edit 
          $('.color_edit').html(color_option);
          //colors end


          //Images start
          let images = response.data.productImg;
          let img = ``;
          $.each(images, function (key, value) { 
               img = `
                 <div class="col-lg-4 col-md-6 col-12 mb-3">
                     <input type="hidden" name="image_uploads[]" value="${value.image}">
                     <img class="w-100" src="{{ asset('uploads/product/${value.image}') }}">
                     <button onclick="ProductCancelImage(this,'${value.image}')" type="button" class="btn btn-danger btn-sm ">cancel</button>
                 </div>
               `
             $('.show-images-edit').append(img)
          });

          //Images end


        }
       });
    }



        ListProduct();
    </script>
@endsection
