<div class="modal fade" id="modalCreateProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> 
    <div class="modal-dialog" style="max-width: 80%;"> 
      <div class="modal-content"> 
        <div class="modal-header"> 
          <h1 class="modal-title fs-5" id="exampleModalLabel">Create Product</h1> 
        </div> 
        <div class="modal-body"> 
            <form id="createProductForm" class="createProductForm" method="POST" enctype="multipart/form-data"> 
                <div class="row"> 
                    <div class="col-lg-8"> 
                        <div class="form-group"> 
                            <label for="name">Product Name</label> 
                            <input type="text" class="form-control" name="name" required> 
                        </div> 

                        <div class="form-group"> 
                            <label for="desc">Description</label> 
                            <textarea name="des" id="des" class="form-control" rows="10"></textarea> 
                        </div> 

                        <div class="form-group"> 
                            <label for="price">Product Price</label> 
                            <input type="text" class="form-control" name="price" required> 
                        </div> 

                        <div class="form-group"> 
                            <label for="qty">Product Quantity</label> 
                            <input type="text" class="form-control" name="qty" required> 
                        </div> 

                        <div class="form-group"> 
                            <label for="image">Product Image</label> 
                            <input type="file" id="image" class="form-control" multiple name="image[]" required> 
                            <button type="button" onclick="UploadImage('.createProductForm')" class="btn btn-primary upload_images">Uploads</button> 
                        </div>
                        
                        <div id="image-preview" class="mt-3"></div>
                    </div> 

                    <div class="col-lg-4"> 
                        <div class="form-group"> 
                            <label for="category_id">Category</label> 
                            <select name="category_id" style="width : 100%" class="form-control category-add">
                                <option></option> 
                            </select> 
                        </div> 

                        <div class="form-group"> 
                            <label for="brand_id">Brand</label> 
                            <select name="brand_id"style="width : 100%" class="form-control brand-add"> 
                                <option></option>
                            </select> 
                        </div> 

                        <div class="form-group"> 
                            <label for="color">Color</label> 
                            <select name="color[]" id="color_add" style="width : 100%"  class="color-add form-control " multiple ="multiple"> 
                                <option></option>
                            </select> 
                        </div> 

                        <div class="form-group"> 
                            <label for="related_product">Related Product</label> 
                            <select name="related_product" class="related-add form-control"> 
                                <option></option> 
                            </select> 
                        </div> 

                        <div class="form-group"> 
                            <label for="status">Status</label> 
                            <select name="status" class="form-control"> 
                                <option value="1">Active</option> 
                                <option value="0">Inactive</option> 
                            </select> 
                        </div> 
                    </div> 
                </div> 
            </form> 
        </div> 
        <div class="modal-footer"> 
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> 
          <button onclick="StoreProduct('#createProductForm')" type="button" class="btn btn-primary">Save</button> 
        </div> 
      </div> 
    </div> 
 </div>
