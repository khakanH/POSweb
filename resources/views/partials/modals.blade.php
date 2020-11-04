<style type="text/css">
  /*input[type="file"] {
    display: none;
}*/
</style>

<div id="myModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="popup_modal_content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="mydata">
                
            </div>
            <div class="modal-footer" id="footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- ----------------------------------------------------------------------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div class="" id="toast" style="visibility: hidden; position: fixed; bottom: 5px; left: 30px; z-index: 999999999; font-size: 15px;">
                                        <p id="toastMsg" style="float: left;"></p> 
                                            <button type="button" class="close" onclick="hideToast('toast')" aria-label="Close" style="float: right;"> &nbsp;&nbsp;&nbsp;<span aria-hidden="true">×</span> </button>
                                        </div>


<!-- Loading Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="LoadingModal" class="modal" data-backdrop="false" data-keyboard="true" style="height: 1%; background: rgba(0,0,0,0.6);">
          <center><img src="<?php echo env('IMG_URL') ?>loading_bar.gif" width="100%" height="20"></center>
</div>

<!-- ----------------------------------------------------------------------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------------------- -->




<!-- Category Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="CategoryModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog" id="CategoryModalDialog">
        <div class="modal-content" id="CategoryModalContent">
           
            <form name="categoryForm" enctype="multipart/form-data" id="cateForm">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="CategoryModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="CategoryModalData">

                      <input type="hidden" id="cate_id" name="cate_id">


                        
                        <div class="form-group">
                          <input type="text" id="cate_name" name="cate_name" class="form-control" placeholder="Enter Category Name">
                        </div>
                                
                      </div>
              

                      </div>
                  <div class="modal-footer" id="CategoryModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-info ">Save</button>

                  </div>
            </form>


        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- ----------------------------------------------------------------------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------------------- -->


<!-- Product Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="ProductModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog modal-lg" id="ProductModalDialog">
        <div class="modal-content" id="ProductModalContent">
           
            <form name="productForm" enctype="multipart/form-data" id="prodForm">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="ProductModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="ProductModalData">

                        <input type="hidden" id="prod_id" name="prod_id">


                        
                        <div class="row">
                          <div class="col-lg-6"> 
                            <label>Product Code:</label>
                            <input type="text" id="prod_code" name="prod_code" class="form-control" placeholder="Enter Product Code"></div>
                          <div class="col-lg-6"> 
                            <label>Product Name:</label>
                            <input type="text" id="prod_name" name="prod_name" class="form-control" placeholder="Enter Product Name"></div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-lg-6"> 
                            <label>Product Category:</label>
                            <select class="form-control" name="prod_cate" id="prod_cate">
                              <option value="" disabled="" selected="">Selecet Category</option>
                            </select>
                          </div>
                          <div class="col-lg-6"> 
                            <label>Product Cost:</label>
                            <input type="number" id="prod_cost" name="prod_cost" class="form-control" placeholder="Enter Product Cost"></div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-lg-6"> 
                            <label>Product Tax:</label>
                            <input type="number" id="prod_tax" name="prod_tax" class="form-control" placeholder="Enter Product Tax"></div>
                          <div class="col-lg-6"> 
                            <label>Product Price:</label>
                            <input type="number" id="prod_price" name="prod_price" class="form-control" placeholder="Enter Product Price"></div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-lg-6"> 
                            <label>Product Image:</label><br>
                            <img id="product_image_output" src="{{ env('IMG_URL')}}choose_img.png" width="130" height="130" style="
  border-radius: 2%; border: solid gray 1px; object-position: top; object-fit: cover;">&nbsp;&nbsp;<input type="file"  name="product_image" onchange="product_loadFile(event)"  accept="image/*" required=""></div>
                          <div class="col-lg-6"> 
                            <label>Product Description:</label>
                            <textarea class="form-control" name="prod_descrip" id="prod_descrip" rows="5" placeholder="Enter Product Description"></textarea></div>
                        </div>


                      </div>
              

                      </div>
                  <div class="modal-footer" id="ProductModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-info ">Save</button>

                  </div>
            </form>


        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- ----------------------------------------------------------------------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------------------- -->





<script type="text/javascript">

     $(function() {
        $("form[name='categoryForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      cate_name: {
        required: true,
      },
    },
    messages: {
      cate_name: {
        required: "Please Provide a Category Name",
      },
     
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('cateForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}add-update-category",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#CategoryModal').modal('hide');
                            $('#LoadingModal').modal('show');
                        },
        success: function(data) {
           
                            $('#LoadingModal').modal('hide');

            get_status = data['status'];
            get_msg    = data['msg'];

                            if (get_status == "0") 
                            {

                             document.getElementById('toast').style.visibility = "visible";
                                document.getElementById('toast').className = "alert alert-danger alert-rounded fadeIn animated";
                                document.getElementById('toastMsg').innerHTML = "Failed, Try Again Later";


                                 setTimeout(function() {
                                document.getElementById('toast').className = "alert alert-danger alert-rounded fadeOut animated";

                            }, 5000);

                            }
                            else
                            {
                                document.getElementById('toast').style.visibility = "visible";
                                document.getElementById('toast').className = "alert alert-success alert-rounded fadeIn animated";
                                document.getElementById('toastMsg').innerHTML = get_msg;


                                 setTimeout(function() {
                                document.getElementById('toast').className = "alert alert-success alert-rounded fadeOut animated";

                            }, 5000);


                            }


        var search_text = (document.getElementById("cate_search_text").value.trim() == "")?"0":document.getElementById("cate_search_text").value.trim();
        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-category-list-AJAX/"+search_text,
        success: function(data) {

            $('#cateTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });

    }
  });

    }
  });
  });








//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------






     $(function() {
        $("form[name='productForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      prod_code: {
        required: true,
      },
      prod_name: {
        required: true,
      },
      prod_cate: {
        required: true,
      },
      prod_tax: {
        required: true,
      },
      prod_cost: {
        required: true,
      },
      prod_price: {
        required: true,
      },
      product_image: {
        required: true,
      },

    },
    messages: {
      prod_code: {
        required: "Please Provide a Product Code",
      },
      prod_name: {
        required: "Please Provide a Product Name",
      },
      prod_cate: {
        required: "Please Select a Product Category",
      },
      prod_tax: {
        required: "Please Provide a Product Tax",
      },
      prod_cost: {
        required: "Please Provide a Product Cost",
      },
      prod_price: {
        required: "Please Provide a Product Priver",
      },
      product_image: {
        required: "Please Provide a Product Image",
      },
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('prodForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}add-update-product",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#ProductModal').modal('hide');
                            $('#LoadingModal').modal('show');
                        },
        success: function(data) {
           
                            $('#LoadingModal').modal('hide');

            get_status = data['status'];
            get_msg    = data['msg'];

                            if (get_status == "0") 
                            {

                             document.getElementById('toast').style.visibility = "visible";
                                document.getElementById('toast').className = "alert alert-danger alert-rounded fadeIn animated";
                                document.getElementById('toastMsg').innerHTML = "Failed, Try Again Later";


                                 setTimeout(function() {
                                document.getElementById('toast').className = "alert alert-danger alert-rounded fadeOut animated";

                            }, 5000);

                            }
                            else
                            {
                                document.getElementById('toast').style.visibility = "visible";
                                document.getElementById('toast').className = "alert alert-success alert-rounded fadeIn animated";
                                document.getElementById('toastMsg').innerHTML = get_msg;


                                 setTimeout(function() {
                                document.getElementById('toast').className = "alert alert-success alert-rounded fadeOut animated";

                            }, 5000);


                            }


        var search_text = (document.getElementById("prod_search_text").value.trim() == "")?"0":document.getElementById("prod_search_text").value.trim();
        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-product-list-AJAX/"+search_text,
        success: function(data) {

            $('#prodTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });

    }
  });

    }
  });
  });







var product_loadFile = function(event) {
    var product_image_output = document.getElementById('product_image_output');
    product_image_output.src = URL.createObjectURL(event.target.files[0]);
    product_image_output.onload = function() {
      URL.revokeObjectURL(product_image_output.src) // free memory
    }
  };
</script>






















