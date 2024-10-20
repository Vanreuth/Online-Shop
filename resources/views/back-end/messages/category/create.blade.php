<div class="modal fade" id="modalCreateCategory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createCategoryForm" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="categoryName">Category Name</label>
                        <input type="text" name="name" class="name form-control" id="categoryName" required>
                        <p></p>
                    </div>

                    <div class="form-group">
                        <label for="categoryStatus">Status</label>
                        <select name="status" class="status form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <p></p>
                    </div>

                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="form-control">
                        <button type="button" onclick="UploadImage('#createCategoryForm')" class="btn btn_upload btn-success rounded-0">Upload</button>
                        <p></p>
                    </div>
                    <div id="image-preview" class="mt-3"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="SaveCategory('#createCategoryForm')">Save</button>
            </div>
        </div>
    </div>
</div>
