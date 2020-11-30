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
                            <label>Product Code: <i style="font-size: 12px">(Barcode)</i></label>
                            <input type="text" id="prod_code" name="prod_code" class="form-control" placeholder="Enter Product Code"></div>
                            <!-- <div class="col-lg-3"> 
                            <label>PCT Code: <i style="font-size: 12px">(optional)</i></label>
                            <input type="text" id="pct_code" name="pct_code" class="form-control" placeholder="Enter PCT Code"  data-toggle="tooltip" title="Only Required For FBR Integration"></div> -->
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
                            <label>Product Cost: <i style="font-size: 12px">(without tax)</i> </label>
                            <input type="number" onkeyup="GetProdPrice()" onchange="GetProdPrice()" onclick="ToNormal()" id="prod_cost" name="prod_cost" class="form-control" placeholder="Enter Product Cost"></div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-lg-6"> 
                            <label>Product Tax: <i style="font-size: 12px">(percentage value)</i></label>
                            <input type="number" min="0" max="100" onkeyup="GetProdPrice()" onchange="GetProdPrice()" id="prod_tax" name="prod_tax" class="form-control" placeholder="Enter Product Tax"></div>
                          <div class="col-lg-6"> 
                            <label>Product Price: <i style="font-size: 12px">(sale price including tax)</i></label>
                            <input type="number" id="prod_price" name="prod_price" class="form-control" placeholder="Enter Product Price" readonly=""></div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-lg-6"> 
                            <label>Product Image:</label><br>
                            <img id="product_image_output" src="{{ env('IMG_URL')}}choose_img.png" width="130" height="130" style="border-radius: 2%; border: solid gray 1px; object-position: top; object-fit: cover;">&nbsp;&nbsp;<input type="file"  name="product_image" id="product_image" onchange="product_loadFile(event)"  accept="image/*" ></div>
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

<!-- Customer Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="CustomerModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog" id="CustomerModalDialog">
        <div class="modal-content" id="CustomerModalContent">
           
            <form name="customerForm" enctype="multipart/form-data" id="custForm">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="CustomerModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="CustomerModalData">

                      <input type="hidden" id="cust_id" name="cust_id">

                      <input type="hidden" id="create_type" name="create_type">


                        
                        <div class="form-group">
                          <label for="cust_name" class=" form-control-label">Name:</label>
                          <input type="text" id="cust_name" name="cust_name" required="" class="form-control" placeholder="Enter Customer Name">
                        </div>
                        <div class="form-group">
                          <label for="cust_email" class=" form-control-label">Email:</label>
                          <input type="email" id="cust_email" required="" name="cust_email" class="form-control" placeholder="Enter Customer Email Address">
                        </div>
                        <div class="form-group">
                          <label for="cust_phone" class=" form-control-label">Phone Number:</label>
                          <input type="text" id="cust_phone" required="" name="cust_phone" class="form-control" placeholder="Enter Customer Phone Number">
                        </div>
                        <div class="form-group">
                          <label for="cust_discount" class=" form-control-label">Discount:</label>
                          <input type=number id="cust_discount" name="cust_discount" class="form-control" min="0" max="100" placeholder="Enter Customer Discount">
                        </div>
                                
                      </div>
              

                      </div>
                  <div class="modal-footer" id="CustomerModalFooter">
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


<!-- Bill Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="BillModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog" id="BillModalDialog">
        <div class="modal-content" id="BillModalContent">
           
            <form name="billForm" enctype="multipart/form-data" id="billForm">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="BillModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="BillModalData">

                        <input type="hidden" id="bill_id" name="bill_id">
                        <input type="hidden" id="total_bill_amount" name="total_bill_amount">
                        <input type="hidden" id="bill_cash_change" name="bill_cash_change">

                        <h4>Customer: <span id="bill_cust_name"></span></h4>
                        <hr>
                        <p id="bill_total_item"></p>
                        <p id="bill_total_amount"></p>
                        <hr>
                         <div class="form-group">
                          <label for="payment_method" class=" form-control-label">Payment Method:</label>
                          <select required="" onchange="CheckPaymentMethod(this.value)" name="payment_method" id="payment_method" class="form-control">
                            <option value="">Select Payment Method</option>
                          </select>
                        </div>

                         <div class="form-group" id="for_cash">
                          <label for="payment_amount"  class=" form-control-label">Payment Amount:</label>
                          <input type="number" required="" name="payment_amount" onkeyup="CalculateBillChange(this.value)" id="payment_amount" class="form-control">
                          <br>
                          <label for="payment_amount" class=" form-control-label">Change: <span id="bill_change">0</span></label>
                        </div>

                         <div class="form-group" style="display: none;" id="for_credit_card">
                          <label for="payment_amount" class=" form-control-label">Credit Card Number:</label>
                          <input required="" name="credit_card_number" id="credit_card_number" class="form-control" type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}" autocomplete="cc-number" maxlength="19" placeholder="xxxx xxxx xxxx xxxx">
                          <br>
                          <label for="payment_amount" class=" form-control-label">Credit Card Holder:</label>
                          <input type="text" required="" name="credit_card_holder" id="credit_card_holder" class="form-control">
                        </div>

                        <div class="form-group" style="display: none;" id="for_cheque">
                          <label for="payment_amount" class=" form-control-label">Cheque Number:</label>
                          <input type="text" required="" name="cheque_number" id="cheque_number" class="form-control">
                         
                        </div>

                                                
                                
                      </div>
              

                      </div>
                  <div class="modal-footer" id="BillModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                      <button type="submit" id="bill-payment-btn" class="btn btn-info ">Save</button>

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


<!-- Receipt Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="ReceiptModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog" id="ReceiptModalDialog">
        <div class="modal-content" id="ReceiptModalContent">
           
                  <div class="modal-header">
                      <h4 class="modal-title" id="ReceiptModalLabel">Receipt</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="ReceiptModalData" style="overflow: auto; max-height: 450px; padding: 0px 20px 0px 20px;">

                                
                      </div>
              

                  </div>
                  <div class="modal-footer" id="ReceiptModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-info" onclick="PrintReceipt()">Print</button>

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


<!-- Sale Item Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="SaleItemModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog modal-lg" id="SaleItemModalDialog">
        <div class="modal-content" id="SaleItemModalContent">
           
                  <div class="modal-header">
                      <h4 class="modal-title" id="SaleItemModalLabel">Sale Item</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="SaleItemModalData">

                                
                      </div>
              

                  </div>
                  <div class="modal-footer" id="SaleItemModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
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





<!-- Category Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="UserModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog" id="UserModalDialog">
        <div class="modal-content" id="UserModalContent">
           
            <form name="userForm" enctype="multipart/form-data" id="userForm">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="UserModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="UserModalData">



                        
                        <div class="form-group">
                          <input type="text" id="user_name" name="user_name" class="form-control" placeholder="Enter User Name"><br>
                          <input type="email" id="user_email" name="user_email" class="form-control" placeholder="Enter User Email"><br>
                          <input type="password" id="user_password" name="user_password" class="form-control" placeholder="Enter User password"><br>
                          <select class="form-control" name="user_type" id="user_type">
                          </select>
                        </div>
                                
                      </div>
              

                      </div>
                  <div class="modal-footer" id="UserModalFooter">
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

























<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

<script type="text/javascript">
  function ToNormal()
  {
    document.getElementById("prod_cost").style.border = "solid lightgray 1px"
  }

  function GetProdPrice()
  {
    val = parseFloat(document.getElementById("prod_tax").value);
    cost = parseFloat(document.getElementById("prod_cost").value);
    document.getElementById("prod_cost").style.border = "solid lightgray 1px"

    if (!cost) 
    {
      document.getElementById("prod_cost").style.border = "solid red 1px"
      return false;
    }

    if (val < 0 || val > 100 ) 
    {
      return false;
    }


    document.getElementById("prod_price").value = +cost + +(cost*val/100);

  }


      $("#credit_card_number").mask("9999-9999-9999-9999");


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


//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------



       $(function() {
        $("form[name='customerForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      
    },
    messages: {
      
     
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('custForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}add-update-customer",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#CustomerModal').modal('hide');
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

                                 if (document.getElementById("create_type").value == "1") 
                                 {

                                       $.ajax({
                                        type: "GET",

                                        url: "{{ env('APP_URL')}}get-customers-list",
                                        success: function(data_) {
                                                
                                                $('#customer_list').html(data_);



                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            alert('Exception:' + errorThrown);
                                        }
                                      }); 
                                 }
                                 else
                                 {

                                        var search_text = (document.getElementById("cust_search_text").value.trim() == "")?"0":document.getElementById("cust_search_text").value.trim();
                                          $.ajax({
                                          type: "GET",
                                          url: "{{ env('APP_URL')}}get-customer-list-AJAX/"+search_text,
                                          success: function(data) {

                                              $('#custTBody').html(data);
                                          },
                                          error: function(jqXHR, textStatus, errorThrown) {
                                              alert('Exception:' + errorThrown);
                                          }
                                      });
  

                                 }

                            }



    }
  });

    }
  });
  });

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------



       $(function() {
        $("form[name='billForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      
    },
    messages: {
      
     
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('billForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}add-sale",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#BillModal').modal('hide');
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
                                document.getElementById('toastMsg').innerHTML = get_msg;


                                 setTimeout(function() {
                                
                                document.getElementById('toast').style.visibility = "hidden";


                            }, 5000);

                            }
                            else
                            {
                              if (get_status == "1") 
                              {
                                document.getElementById('toast').className = "alert alert-success alert-rounded fadeIn animated";
                              }
                              else if (get_status == "2")
                              {
                                document.getElementById('toast').className = "alert alert-warning alert-rounded fadeIn animated";
                              }
                              document.getElementById('toast').style.visibility = "visible";
                              document.getElementById('toastMsg').innerHTML = get_msg;
                              setTimeout(function() {
                                
                                document.getElementById('toast').style.visibility = "hidden";


                              }, 10000);

                                $('#ReceiptModal').modal('show');
                                createNewBill();
                               
                                 $.ajax({
                                    type: "GET",

                                    url: "{{ env('APP_URL')}}get-bill-receipt/"+data['sale_id'],
                                    success: function(data) {
                                           
                                      $('#ReceiptModalData').html(data);

                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        alert('Exception:' + errorThrown);
                                    }
                                  });              
                                  
                            }



    }
  });

    }
  });
  });

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------

  

     $.validator.addMethod('emailFormat', function(value, element) {
        return this.optional(element) || (value.match(/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/));
    },
    'Please enter a valid email address.');

    $(function() {
        $("form[name='userForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      user_name: {
        required: true,
      },
      user_email: {
        required: true,
        emailFormat:true,
      },
      user_type: {
        required: true,
      },
      user_password: {
        required: true,
        minlength: 6,
      },

    },
    messages: {
      user_name: {
        required: "Please Provide a User Name",
      },
      user_email: {
        required: "Please Provide a User Email",
      },
      user_type: {
        required: "Please Select a User Type",
      },
     user_password: {
        required: "Please Provide a User Password",
        minlength: "Password Must be at Least 6 Characters Long",

      },
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('userForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}add-user",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#UserModal').modal('hide');
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
                                document.getElementById('toastMsg').innerHTML = get_msg;


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


        var search_text = (document.getElementById("user_search_text").value.trim() == "")?"0":document.getElementById("user_search_text").value.trim();
        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-user-list-AJAX/"+search_text,
        success: function(data) {

            $('#userTBody').html(data);
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







var product_loadFile = function(event) {
    var product_image_output = document.getElementById('product_image_output');
    product_image_output.src = URL.createObjectURL(event.target.files[0]);
    product_image_output.onload = function() {
      URL.revokeObjectURL(product_image_output.src) // free memory
    }
  };
</script>






















