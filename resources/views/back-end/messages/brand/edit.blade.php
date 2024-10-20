<div class="modal fade" id="modalEditBrand" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Brand</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editBrandForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="brandId"> <!-- ID is now inside the form -->
                    
                    <div class="form-group">
                        <label for="brand_name">Brand Name</label>
                        <input type="text" name="name" class="name form-control" id="brand_name" required>
                        <p></p>
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select name="category" class="category form-control" id="brand_category">
                           @foreach ($categories as $category)
                              <option value="{{$category->id }}">{{ $category->name }}</option>
                           @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="status form-control" id="brand_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <p></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="UpdateBrand('#editBrandForm')">Save</button>
            </div>
        </div>
    </div>
</div>
