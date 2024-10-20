@extends('back-end.components.master')

@section('contens')
@include('back-end.messages.color.create')
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
                        <input type="text" id="searchBox" class="form-control" placeholder="Search by name">
                        <button class="btn btn-outline-primary ml-2 searchBtn">Search</button>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateColor">New Color</button>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Color ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="color_list">
                        <!-- Colors will be populated here -->
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="show-page"></div>
                    <button onclick="ColorRefresh()" class="btn btn-outline-danger rounded-0 btn-sm">Refresh</button>
                </div>

                <!-- Pagination -->
                <div class="mt-3" id="paginationContainer"></div>
            </div>
        </div>
    </div>

    <!-- Create Color Modal -->
    {{-- <div class="modal fade" id="modalCreateColor" tabindex="-1" aria-labelledby="modalCreateColorLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 40%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalCreateColorLabel">Create Color</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createColorForm">
                        <div class="form-group">
                            <label for="colorName">Color Name</label>
                            <input type="text" name="name" class="form-control" id="colorName" required>
                        </div>
                        <div class="form-group">
                            <label for="colorCode">Color Code</label>
                            <input type="text" name="code" class="form-control" id="colorCode" required>
                        </div>
                        <div class="form-group">
                            <label for="colorStatus">Status</label>
                            <select name="status" class="form-control" id="colorStatus">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="StoreColor()">Save</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Edit Color Modal -->
    <div class="modal fade" id="modalEditColor" tabindex="-1" aria-labelledby="modalEditColorLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 40%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditColorLabel">Edit Color</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editColorForm">
                        <input type="hidden" name="id" id="editColorId">
                        <div class="form-group">
                            <label for="editColorName">Color Name</label>
                            <input type="text" name="name" class="form-control" id="editColorName" required>
                        </div>
                        <div class="form-group">
                            <label for="editColorCode">Color Code</label>
                            <input type="text" name="code" class="form-control" id="editColorCode" required>
                        </div>
                        <div class="form-group">
                            <label for="editColorStatus">Status</label>
                            <select name="status" class="form-control" id="editColorStatus">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="UpdateColor()">Update</button>
                </div>
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
                            <td class="${value.status == 1 ? 'text-success' : 'text-danger'}">
                                ${value.status == 1 ? 'Active' : 'Inactive'}
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
                    alert('Color created successfully');
                    $('#modalCreateColor').modal('hide');
                    ColorRefresh();
                } else {
                    alert('Failed to create color');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while creating color');
            }
        });
    };

    // Edit Color Function
    const editColor = (id) => {
        $.ajax({
            type: 'GET',
            url: `{{ url('color') }}/${id}/edit`,
            success: function(response) {
                if (response.status === 200) {
                    $('#editColorId').val(response.color.id);
                    $('#editColorName').val(response.color.name);
                    $('#editColorCode').val(response.color.code);
                    $('#editColorStatus').val(response.color.status);
                } else {
                    alert('Failed to fetch color data');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while fetching color data');
            }
        });
    };

    // Update Color Function
    const UpdateColor = () => {
        const form = $('#editColorForm');
        const data = form.serialize();
        $.ajax({
            type: 'PUT',
            url: `{{ url('color') }}/${$('#editColorId').val()}`,
            data: data,
            success: function(response) {
                if (response.status === 200) {
                    alert('Color updated successfully');
                    $('#modalEditColor').modal('hide');
                    ColorRefresh();
                } else {
                    alert('Failed to update color');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while updating color');
            }
        });
    };

    // Delete Color Function
    const ColorDelete = (id) => {
        if (confirm('Are you sure you want to delete this color?')) {
            $.ajax({
                type: 'DELETE',
                url: `{{ url('color') }}/${id}`,
                success: function(response) {
                    if (response.status === 200) {
                        alert('Color deleted successfully');
                        ColorRefresh();
                    } else {
                        alert('Failed to delete color');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('An error occurred while deleting color');
                }
            });
        }
    };
</script>
@endsection
