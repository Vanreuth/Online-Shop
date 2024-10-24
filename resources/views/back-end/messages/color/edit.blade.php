<div class="modal fade" id="modalEditColor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Brand</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editColorForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editColorId">
                    <div class="form-group">
                        <label for="colorName">Color Name</label>
                        <input type="text" name="name" class="form-control" id="editColorName" required>
                        <p class="text-danger" id="nameError" style="display:none;"></p>
                    </div>

                    <div class="form-group">
                        <label for="colorCode">Color</label>
                        <input type="color" name="code" class="form-control" id="editColorCode" required>
                        <p class="text-danger" id="codeError" style="display:none;"></p>
                    </div>

                    <div class="form-group">
                        <label for="colorStatus">Status</label>
                        <select name="status" class="form-control" id="editColorStatus">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="UpdateColor('#editColorForm')">Save</button>
            </div>
        </div>
    </div>
</div>
