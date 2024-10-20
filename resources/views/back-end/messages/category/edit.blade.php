<div class="modal fade" id="modalEditCategory" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Edit Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="categoryId">
                    <div class="form-group">
                        <label for="editCategoryName">Category Name</label>
                        <input type="text" name="name" class="name-edit form-control" id="editCategoryName" required>
                        <p></p>
                    </div>

                    <div class="form-group">
                        <label for="editCategoryStatus">Status</label>
                        <select name="status" class="form-control" id="editCategoryStatus">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <p></p>
                    </div>

                    <div class="form-group">
                        <label for="editImage">Image</label>
                        <input type="file" name="image" id="editImage" class="form-control">
                        <button type="button" onclick="UploadImage('#editCategoryForm')" class="btn btn_upload btn-success rounded-0">Upload</button>
                        <p></p>
                    </div>

                    <!-- Image Preview -->
                    <div id="edit-image-preview" class="mt-3"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="UpdateCategory('#editCategoryForm')">Save Changes</button>
            </div>
        </div>
    </div>
</div>
