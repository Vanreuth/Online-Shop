@extends('back-end.components.master')

@section('contens')
@include('back-end.messages.color.create')
@include('back-end.messages.color.edit')
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header">
                <h4 class="page-title">Color Management</h4>
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
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateColor">New Color</button>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Color ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="color_list">
                        <!-- Colors will be populated here -->
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-3" id="paginationContainer"></div>
            </div>
        </div>
    </div>

   
@endsection

@section('scripts')
<script>
    // Fetch colors via AJAX
    const ListColor = (page = 1, search = '') => {
        $.ajax({
            type: 'POST',
            url: "{{ route('color.list') }}",
            data: {
                page: page,
                search: search
            },
            datatype: "json",
            success: function(response) {
                if (response.status === 200) {
                    let colors = response.colors;
                    let tr = '';
                    $.each(colors, function(key, value) {
                        tr += `
                        <tr>
                            <td>${value.id}</td>
                            <td>${value.name}</td>
                            <td>
                              <div style="background-color: ${value.code}; width: 30px; height: 30px;"></div>
                            </td>      
                            <td>
                            ${(value.status == 1) ? '<span class="badge badge-success p-2">Active</span>' : ' <span class="badge badge-danger  p-2">Inactive</span>' }
                             </td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditColor" onclick="editColor(${value.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="ColorDelete(${value.id})">Delete</button>
                            </td>
                        </tr>`;
                    });

                    $('.color_list').html(tr);

                    // Pagination handling
                    let totalPage = response.page.totalPage;
                    let currentPage = response.page.currentPage;
                    let pageHtml = `
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">`;

                    pageHtml += `
                        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                            <a class="page-link" href="javascript:void(0);" onclick="PreviousPage(${currentPage})">Previous</a>
                        </li>`;

                    for (let i = 1; i <= totalPage; i++) {
                        pageHtml += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="javascript:void(0);" onclick="ColorPage(${i})">${i}</a>
                        </li>`;
                    }

                    pageHtml += `
                        <li class="page-item ${currentPage === totalPage ? 'disabled' : ''}">
                            <a class="page-link" href="javascript:void(0);" onclick="NextPage(${currentPage})">Next</a>
                        </li>
                    </ul>
                </nav>`;

                    $('#paginationContainer').html(pageHtml);
                } else {
                    alert('Failed to fetch colors');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while fetching colors');
            }
        });
    };

    // Initial call to list colors
    ListColor();

    // Pagination controls
    const ColorPage = (page) => {
        ListColor(page);
    };

    const NextPage = (page) => {
        ListColor(page + 1);
    };

    const PreviousPage = (page) => {
        ListColor(page - 1);
    };

    // Search event 
    $(document).on("click", '.searchBtn', function() {
        let searchValue = $("#searchBox").val();
        ListColor(1, searchValue); // Pass the search value to ListColor
    });

    const ColorRefresh = () => {
        ListColor();
        $("#searchBox").val("");
    };

    // Store Color Function
    const StoreColor = () => {
        const form = $('#createColorForm');
        const data = form.serialize();
        $.ajax({
            type: 'POST',
            url: "{{ route('color.store') }}",
            data: data,
            success: function(response) {
                if (response.status === 200) {
                    $('#modalCreateColor').modal('hide');
                    $('#createColorForm').trigger("reset");
                    ColorRefresh();
                    Message(response.message);
                } else {
                    Message(response.message);
                }
            },
            
        });
    };

    // Edit Color Function
    const editColor = (id) => {
        $.ajax({
            type: 'POST',
            url: '{{ route('color.edit') }}',
            data: {
                 "id": id
                },
            dataType: "json",
            success: function(response) {
                if (response.status === 200) {
                    $('#editColorId').val(response.color.id);
                    $('#editColorName').val(response.color.name);
                    $('#editColorCode').val(response.color.code);
                    $('#editColorStatus').val(response.color.status);
                    Message(response.message);
                } else {
                    Message(response.message);
                }
            }
        });
    };

    // Update Color Function
    const UpdateColor = () => {
        const form = $('#editColorForm');
        let payloads = new FormData($(form)[0]);
        $.ajax({
            type: 'POST',
                url: '{{ route('color.update') }}',
                data: payloads,
                dataType: "json",
                contentType: false,
                processData: false,
            success: function(response) {
                if (response.status === 200) {
                    $('#modalEditColor').modal('hide');
                    Message(response.message);
                    ColorRefresh();
                    ListColor();
                } else {
                    Message(response.message);
                }
            },
           
        });
    };

    // Delete Color Function
    const ColorDelete = (id) => {
            if (confirm("Do you want to delete this ?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('color.destroy') }}",
                    data: {
                        "id": id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 200) {
                            Message(response.message);
                            ListColor();
                            Message(response.message);
                        }
                        else{
                            Message(response.message);
                        }
                    }
                });
            }
        }
</script>
@endsection
