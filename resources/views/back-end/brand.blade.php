@extends('back-end.components.master')

@section('contens')
    <!-- Page Title Header Starts-->
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header">
                <h4 class="page-title">Brand Magement</h4>
            </div>
        </div>
    </div>
    <!-- Page Title Header Ends-->

    {{-- Include Modals for Create and Edit --}}
    @include('back-end.messages.brand.create')
    @include('back-end.messages.brand.edit')

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Brand</h4>
                    <div class="d-flex">
                        <div class="input-group">
                            <input type="text" id="searchBox" class="form-control" placeholder="Search by name">
                            <button class="btn btn-outline-primary ml-2 searchBtn">Search</button>
                        </div>
                        <button onclick="BrandRefresh()" class="btn btn-outline-danger rounded-0 btn-sm ml-2">Refresh</button>
                    </div>
                    <p data-bs-toggle="modal" data-bs-target="#modalCreateBrand" class="card-description btn btn-primary">
                        New Brand
                    </p>
                </div>
        
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Brand ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="brand_list">
                        <!-- Brands will be populated here -->
                    </tbody>
                </table>
        
                <!-- Pagination -->
                <div class="mt-3 d-flex justify-content-center" id="paginationContainer">
                    <!-- Pagination content will go here -->
                </div>
            </div>
        </div>
        
    </div>
@endsection

@section('scripts')
    <script>
        // List all brands via AJAX
        const ListBrand = (page = 1, search = '') => {
            $.ajax({
                type: 'POST',
                url: "{{ route('brand.list') }}",
                data: {
                    "page": page,
                    "search": search
                },
                datatype: "json",
                success: function(response) {
                    if (response.status == 200) {
                        let brand = response.brand;
                        let tr = '';
                        $.each(brand, function(key, value) {
                            tr += `
                        <tr>
                            <td>${value.id}</td>
                            <td>${value.name}</td>
                            <td class="${(value.status == 1) ? 'text-success' : 'text-danger'}">
                             ${(value.status == 1) ? 'Active' : 'Inactive'}
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditBrand" onclick="editBrand(${value.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="BrandDelete(${value.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                        });
                        // Update the brand list in the table body
                        $('.brand_list').html(tr);
                    } else {
                        alert('Failed to fetch brands');
                    }

                    /// Pagination
                    let totalPage = response.page.totalPage;
                    let currentPage = response.page.currentPage;
                    let pageHtml = `
                              <nav aria-label="Page navigation example">
                <ul class="pagination  justify-content-center bg-danger">
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="PreviousPage(${currentPage})">Previous</a>
                    </li>`;

                    for (let i = 1; i <= totalPage; i++) {
                        pageHtml += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0);" onclick="BrandPage(${i})">${i}</a>
                </li>`;
                    }

                    pageHtml += `
                    <li class="page-item ${currentPage === totalPage ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="NextPage(${currentPage})">Next</a>
                    </li>
                </ul>
            </nav>`;

                    $('#paginationContainer').html(
                        pageHtml); 
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('An error occurred while fetching brands');
                }
            });
        };

        ListBrand();

        // Store brand via AJAX
        const StoreBrand = (form) => {
            let payloads = new FormData($(form)[0]);
            $.ajax({
                type: "POST",
                url: "{{ route('brand.store') }}",
                data: payloads,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == 200) {
                        $("#modalCreateBrand").modal("hide");
                        $(form).trigger('reset');
                        $(".name").removeClass("is-invalid").siblings("p").removeClass("text-danger").text(
                            " ");
                        Message(response.message);
                        ListBrand(); // Reload the brand list after successful creation
                    } else {
                        let error = response.error;
                        $(".name").addClass("is-invalid").siblings("p").addClass("text-danger").text(error
                            .name);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('An error occurred while storing the brand');
                }
            });
        };

        const BrandDelete = (id) => {
            if (confirm("Do you want to delete this ?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('brand.destroy') }}",
                    data: {
                        "id": id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 200) {
                            Message(response.message);
                            ListBrand();
                        }
                    }
                });
            }
        }

        // Fetch brand data and populate the edit form
        const editBrand = (id) => {
            $.ajax({
                type: 'POST',
                url: '{{ route('brand.edit') }}',
                data: {
                    "id": id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        console.log(response.brand);

                        $('#brandId').val(response.brand.id);
                        $('#brand_name').val(response.brand.name);
                        $('#brand_category').val(response.brand
                            .category_id); // Assuming a dropdown is already populated
                        $('#brand_status').val(response.brand.status); // Show the modal with populated data
                    } else {
                        alert('Brand not found');
                    }
                },
            });
        };

        const BrandRefresh = () => {
            ListBrand();
            $("#searchBox").val("");
        }

        // Search event 
        $(document).on("click", '.searchBtn', function() {
            let searchValue = $("#searchBox").val();
            ListBrand(1, searchValue); // Pass the search value to the ListBrand function
        });

        const BrandPage = (page) => {
            ListBrand(page);
        }

        const NextPage = (page) => {
            ListBrand(page + 1);
        }

        const PreviousPage = (page) => {
            ListBrand(page - 1);
        }

        // Update brand via AJAX
        const UpdateBrand = (form) => {
            let payloads = new FormData($(form)[0]);

            $.ajax({
                type: 'POST',
                url: '{{ route('brand.update') }}',
                data: payloads,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == 200) {
                        $('#modalEditBrand').modal('hide');
                        $(form).trigger('reset');
                        ListBrand(); // Reload the brand list after successful update
                        Message(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('An error occurred while updating the brand');
                }
            });
        };

        // Initial call to list brands
        ListBrand();
    </script>
@endsection
