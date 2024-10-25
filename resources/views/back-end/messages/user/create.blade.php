<div class="modal fade" id="modalCreateUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createUserForm" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="name" class="name form-control" id="username" required>
                        <p></p>
                    </div>

                    <div class="form-group">
                        <label for="userEmail">Email</label>
                        <input type="email" name="email" class="email form-control" id="userEmail" required>
                        <p></p> 
                    </div>

                    <div class="form-group">
                        <label for="userPassword">Password</label>
                        <input type="password" name="password" class="password form-control" id="userPassword" required>
                        <p></p>
                    </div>

                    <div class="form-group">
                        <label for="userRow">Role</label>
                        <select name="row" class="role form-control">
                            <option value="1">Admin</option>
                            <option value="0">User</option>
                        </select>
                        <p></p>
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" id="image" class="form-control">
                        <button type="button" onclick="UplaodImage('#createUserForm')" class="btn btn_upload btn-success rounded-0">Upload</button>
                        <p></p>
                    </div>
                    <div id="image-preview" class="mt-3"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="SaveUser('#createUserForm')">Save</button>
            </div>
        </div>
    </div>
</div>
