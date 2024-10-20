@extends('back-end.components.master')
@section('contens')

     <!-- Page Title Header Starts-->
     <div class="row page-title-header">
        <div class="col-12">
          <div class="page-header">
            <h4 class="page-title">Dashboard</h4>
            <div class="quick-link-wrapper w-100 d-md-flex flex-md-wrap">
              <ul class="quick-links">
                <li><a href="#">ICE Market data</a></li>
                <li><a href="#">Own analysis</a></li>
                <li><a href="#">Historic market data</a></li>
              </ul>
              <ul class="quick-links ml-auto">
                <li><a href="#">Settings</a></li>
                <li><a href="#">Analytics</a></li>
                <li><a href="#">Watchlist</a></li>
              </ul>
            </div>
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
                <p data-bs-toggle="modal" data-bs-target="#modalCreateUser" class="card-description btn btn-primary ">new users</p>
            </div>
            <table class="table table-striped">
              <thead>
                <tr> 
                  <th>User ID</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Image</th>
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
 const ListUser = () => {
    $.ajax({
        type: 'POST',
        url: "{{ route('user.list') }}",  // Adjust this to the correct route if necessary
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
                            <td>${(value.role==1) ? 'Admin' : 'User'}</td>
                            <td>
                                ${value.img ? `<img src="{{ asset('storage/${value.img}') }}" alt="User Image" width="50">` : 'No Image'}
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
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('An error occurred while fetching users');
        }
    });
};


  ListUser();


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
      $('#userForm').trigger("reset");
      ListUser(); // Refresh user list
      Message('User created successfully!'); // Use the Message function here
    } else {
      Message('Failed to create user'); // Use the Message function here for failure
    }
  },
  error: function(xhr, status, error) {
    console.error(error);
    Message('An error occurred while creating the user'); // Use the Message function here for error
  }
});
  }


  const deleteUser = (id) => {
    if (confirm("Are you sure you want to delete this user?")) {
        $.ajax({
            type: 'POST', // Use DELETE method for deleting a resource
            url: `{{ route('user.destroy', '') }}/${id}`, // Correct way to concatenate URL
            datatype: "json",
            success: function(response) {
                if (response.status == 200) {
                    ListUser(); // Refresh user list after deletion
                    Message('User deleted successfully!'); // Show success message
                } else {
                    Message('Failed to delete user');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                Message('An error occurred while deleting the user');
            }
        });
    }
};
  
</script>
@endsection