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

      {{--Include Model create --}}
      {{-- Modal start --}}
      @include('back-end.messages.category.create') {{-- Change modal file to "category.create" --}}
      {{-- Modal end --}}


      {{--Include Model create --}}
      {{-- Modal start --}}
      @include('back-end.messages.category.edit')
       {{-- Modal end --}}

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Categories</h4>
                <p data-bs-toggle="modal" data-bs-target="#modalCreateCategory" class="card-description btn btn-primary ">New Category</p>
            </div>
            <table class="table table-striped">
              <thead>
                <tr> 
                  <th>Category ID</th>
                  <th>Name</th>
                  <th>Status</th>
                  <th>Image</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="categories_list">
                <!-- Categories will be populated here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
@endsection

@section('scripts')
<script>

const UploadImage = (form) => {
    let payloads = new FormData($(form)[0]);
    $.ajax({
        type: 'POST',
        url: "{{ route('category.upload') }}",
        data: payloads,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log(response);
            if (response.status === 200) {
                let image = `
                <input type="hidden" name="image" value="${response.image}">
                <div class="uploaded-image-wrapper" style="position: relative; display: inline-block; width: 300px;">
                    <img src="{{ asset('uploads/temp/${response.image}') }}" alt="Uploaded Image" class="img-thumbnail">
                </div>
                `;
                $('#image-preview').html(image);
            } else {
                console.error('Failed to upload image:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Upload error:', error);
            alert('An error occurred while uploading the image');
        }
    });
};

 
// const CancelImage = (img) => {
//     if (confirm("Do you want to cancel the image?")) {
//         $.ajax({
//             type: "POST",
//             url: "{{ route('category.upload') }}",
//             data: { "image": img },
//             dataType: "json",
//             success: function(response) {
//                 if (response.status === 200) {
//                     $('#image-preview').html("");
//                     Message('Image canceled successfully!');
//                 } else {
//                     Message('Failed to cancel the image');
//                 }
//             },
//             error: function(xhr, status, error) {
//                 console.error(error);
//                 Message('An error occurred while canceling the image');
//             }
//         });
//     }
// }

const ListCategory = () => {
    $.ajax({
        type: 'POST',
        url: "{{ route('category.list') }}",
        datatype: "json",
        success: function(response) {
            if (response.status == 200) {
                let categories = response.categories;
                let tr = '';
                $.each(categories, function(key, value) {
                    // Construct the image URL using JavaScript
                    let imageUrl = value.image ? `{{ asset('uploads/category') }}/${value.image}` : '';
                    
                    tr += `
                        <tr>
                            <td>${value.id}</td>
                            <td>${value.name}</td>
                             <td>${(value.status==1) ? 'Active' : 'Inactive'}</td>
                             
                            <td>
                                ${value.image ? `<img src="${imageUrl}" alt="Category Image" width="100">` : 'No Image'}
                            </td>
                            <td>
                                 <button data-bs-toggle="modal" data-bs-target="#modalEditCategory" class="btn btn-primary btn-sm" onclick="editCategory(${value.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteCategory(${value.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                $('.categories_list').html(tr);
            } else {
                alert('Failed to fetch categories');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('An error occurred while fetching categories');
        }
    });
};


const SaveCategory = (form) => {
    let payloads = new FormData($(form)[0]);
    $.ajax({
        type: 'POST',
        url: "{{ route('category.store') }}",
        data: payloads,
        datatype: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status == 200) {
                $('#modalCreateCategory').modal('hide');
                $('#categoryForm').trigger("reset");
                ListCategory(); 
                Message('Category created successfully!'); 
            } else {
                Message('Failed to create category');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            Message('An error occurred while creating the category');
        }
    });
}

const editCategory = (id) => {

    $.ajax({
        type: 'POST',
        url: '{{route('category.edit')}}', 
        data:{"id":id},
            datatype: "json",
            success: function(response) {
                if (response.status == 200) {
                    $("#categoryId").val(response.category.id);
                    $(".name-edit").val(response.category.name)
                    $('#edit-image-preview').html("");
                    if(response.category.image != null){
                        let image = `
                <input type="hidden" name="old_image" value="${response.category.image}">
                <div class="uploaded-image-wrapper" style="position: relative; display: inline-block; width: 300px;">
                    <img src="{{ asset('uploads/category/${response.category.image}') }}" alt="Uploaded Image" class="img-thumbnail">
                </div>
                `;
                $('#edit-image-preview').html(image);
                        
                    }
                } else {
                    Message('Failed to delete category');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                Message('An error occurred while deleting the category');
            }
    });
};




const deleteCategory = (id) => {
    if (confirm("Are you sure you want to delete this category?")) {
        $.ajax({
            type: 'POST',
            url: `{{ route('category.destroy') }}`,
            data:{"id":id},
            datatype: "json",
            success: function(response) {
                if (response.status == 200) {
                    Message('Category deleted successfully!');
                    ListCategory();  
                } else {
                    Message('Failed to delete category');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                Message('An error occurred while deleting the category');
            }
        });
    }
};

const UpdateCategory = (form) => {
            let payloads = new FormData($(form)[0]);
            $.ajax({
                type: "POST",
                url: "{{ route('category.update') }}",
                data: payloads,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response.status == 200){
                        $("#modalUpdateCategory").modal("hide");
                        $(form).trigger("reset");
                        $(".name").removeClass("is-invalid").siblings("p").removeClass("text-danger").text("")
                        $(".edit-image-preview").html("");
                        Message(response.message);
                        CategoryList();
                    }else{
                        $(".name").addClass("is-invalid").siblings("p").addClass("text-danger").text(response.error.name);
                    }
                }
            });
        }

  
ListCategory();
</script>
@endsection
