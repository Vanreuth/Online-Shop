@extends('back-end.components.master')
@section('contens')
    <!-- Page Title Header Starts-->
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header">
                <h4 class="page-title">User Manegement</h4>
            </div>
        </div>
    </div>
    <!-- Page Title Header Ends-->


    {{-- Modal start --}}
    @include('back-end.messages.user.create')
    {{-- Modal end --}}


    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Users</h4>
                    <p data-bs-toggle="modal" data-bs-target="#modalCreateUser" class="card-description btn btn-primary ">
                        new users</p>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Profile</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="users_list">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const UplaodImage = (form) => {
            let payloads = new FormData($(form)[0]);
            $.ajax({
                type: "POST",
                url: "{{ route('user.upload') }}",
                data: payloads,
                processData: false,
                contentType: false,
                success: function(response) {

                    if (response.status === 200) {
                        console.log(response);

                        let image = `
                <input type="hidden" name="image" value="${response.image}">
                <div class="uploaded-image-wrapper" style="position: relative; display: inline-block; width: 300px;">
                    <img src="{{ asset('uploads/temp/${response.image}') }}" alt="Uploaded Image" class="img-thumbnail">
                    <button type="button" class="btn btn-danger btn-sm clear-button" style="position: absolute; top: 10px; right: 10px; z-index: 1;" onclick="CancelImage('${response.image}')">
                    Cancle
                   </button>
                </div>
                `;
                        $('#image-preview').html(image);
                    } else {
                        console.error('Failed to upload image:', response.message);
                    }


                }
            });
        }
        const CancelImage = (img) => {
            if (confirm("Do you want to cancel the image?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.cancel') }}",
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
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        Message('An error occurred while canceling the image');
                    }
                });
            }
        }

        const ListUser = () => {
            $.ajax({
                type: 'POST',
                url: "{{ route('user.list') }}", // Adjust this to the correct route if necessary
                datatype: "json",
                success: function(response) {
                    if (response.status == 200) {
                        let users = response.users;

                        let tr = '';
                        $.each(users, function(key, value) {
                            tr += `
                        <tr>
                            <td>${value.id}</td>
                            <td>${value.name}</td>
                            <td>${value.email}</td>
                            <td>
                                ${value.img ? `<img src="{{ asset('uploads/user/${value.img}') }}" alt="User Image" width="100">` : 'No Image'}
                            </td>
                           <td>
                             ${value.role == 1 ? '<span class="badge badge-success p-2">Admin</span>' : '<span class="badge badge-danger p-2">User</span>'}
                </td>

                            <td>
                                <a href="{{ route('user.show', '') }}/${value.id}" class="btn btn-primary btn-sm">View</a>
                                <button class="btn btn-danger btn-sm" onclick="deleteUser(${value.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                        });

                        // Update the users list in the table body
                        $('.users_list').html(tr);
                    } else {
                        alert('Failed to fetch users');
                    }
                }
            });
        };
        const SaveUser = (form) => {
            let payloads = new FormData($(form)[0]);

            $.ajax({
                type: 'POST',
                url: "{{ route('user.store') }}",
                data: payloads,
                datatype: "json",
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 200) {
                        $('#modalCreateUser').modal('hide');
                        $('#createUserForm').trigger("reset");
                        ListUser(); // Refresh user list
                        Message('User created successfully!'); // Use the Message function here
                    } else {
                        Message('Failed to create user'); // Use the Message function here for failure
                    }
                }

            });
        }


        const deleteUser = (id) => {
            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    type: 'POST', 
                    url: "{{ route('user.destroy') }}",
                    data: {
                        "id": id
                    },
                    datatype: "json",
                    success: function(response) {
                        if (response.status == 200) {
                            Message(response.message);
                            ListUser();
                            Message(response.message);
                        }
                        else{
                            Message(response.message);
                        }
                    }
                });
            }
        };

        ListUser();
    </script>
@endsection
