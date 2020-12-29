<style type="text/css">
  /*input[type="file"] {
    display: none;
}*/
.modal-footer{
  display: block;
}
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
                            <label>Actual Product Cost: </label>
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
    <div class="modal-dialog modal-lg" id="CustomerModalDialog">
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


                        <div class="row pos-right-search">

                          <div class="form-group col-lg-6">
                            <label for="cust_name" class=" form-control-label">Code:</label>
                            <input type="text" readonly="" id="cust_code" name="cust_code" class="form-control btn btn-secondary" required="" placeholder="Click To Generate Code" style="cursor: pointer; color: #000;" onclick="GenerateCodeCustomer()">

                          </div>
                          <div class="form-group col-lg-6">
                            <label for="cust_name" class=" form-control-label">Name:</label>
                            <input type="text" id="cust_name" name="cust_name" required="" class="form-control" placeholder="Enter Customer Name">
                          </div>
                          
                        </div>


                        <div class="row pos-right-search">
                          <div class="form-group col-lg-6">
                            <label for="cust_email" class=" form-control-label">Email:</label>
                            <input type="email" id="cust_email" required="" name="cust_email" class="form-control" placeholder="Enter Customer Email Address">
                          </div>
                          <div class="form-group col-lg-6">
                            <label for="cust_phone" class=" form-control-label">Phone Number:</label>
                            <input type="text" id="cust_phone" required="" name="cust_phone" class="form-control" placeholder="Enter Customer Phone Number">
                          </div>
                        </div>
                        <div class="row pos-right-search">
                           <div class="form-group col-lg-6">
                            <label for="cust_discount" class=" form-control-label">Discount:</label>
                            <input type=number id="cust_discount" name="cust_discount" class="form-control" min="0" max="100" placeholder="Enter Customer Discount">
                          </div>
                          <div class="form-group col-lg-6">
                            <label for="cust_discount" class=" form-control-label">Payment Method:</label>
                            <select required="" onchange="CustomerPaymentMethod(this.value)" name="cust_payment_method" id="cust_payment_method" class="form-control">
                            <option value="">Select Payment Method</option>
                          </select>
                          </div>
                        </div>       

                        <div  class="row pos-right-search" id="cust_cc" style="display: none;">
                           <div class="form-group col-lg-6">
                            <label>Credit Card Holder:</label>
                            <input type="text" required="" name="cust_cc_holder" id="cust_cc_holder" class="form-control">
                          </div>
                          <div class="form-group col-lg-6">
                            <label>Credit Card Number:</label>
                            <input required="" name="cust_cc_number" id="cust_cc_number" class="form-control" type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}" autocomplete="cc-number" maxlength="19" placeholder="xxxx xxxx xxxx xxxx">
                          </div>
                        </div> 



                      </div>
              

                      </div>
                  <div class="modal-footer" id="CustomerModalFooter">
                      <button type="submit" class="btn pos-btn ">Save</button>
                      <button type="button" class="btn pos-btn-secondary" data-dismiss="modal">Close</button>

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


                                                
                                
                      </div>
              

                      </div>
                
                  <div class="modal-footer modal-bottom-btns" id="CustomerModalFooter">
                      <button type="submit" id="bill-payment-btn" class="pos-btn mt-3 mb-3 btn btn-default-pos">Save</button>
                      <button type="button" class="btn pos-btn-secondary" data-dismiss="modal">Close</button>
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
                      <button type="button" class="btn pos-btn" onclick="PrintReceipt()">Print</button>
                      <button type="button" class="btn pos-btn-secondary" data-dismiss="modal">Close</button>

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




<!-- Company Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="CompanyModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog modal-lg" id="CompanyModalDialog">
        <div class="modal-content" id="CompanyModalContent">
           
            <form name="companyForm" enctype="multipart/form-data" method="post" action="{{route('add-company-info')}}" id="companyForm">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="CompanyModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                        <div id="set-profile-msg"></div>

                      <div class="" id="CompanyModalData">


                        <input type="hidden" id="company_id" name="company_id">


                        
                        <div class="row">
                          <div class="col-lg-6"> 
                            <label>Compnay Name:</label>
                            <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Enter Company Name"></div>
                            <!-- <div class="col-lg-3"> 
                            <label>PCT Code: <i style="font-size: 12px">(optional)</i></label>
                            <input type="text" id="pct_code" name="pct_code" class="form-control" placeholder="Enter PCT Code"  data-toggle="tooltip" title="Only Required For FBR Integration"></div> -->
                          <div class="col-lg-6"> 
                            <label>Company Phone:</label>
                            <input type="text" id="company_phone" name="company_phone" class="form-control" placeholder="Enter Compnay Phone"></div>
                        </div>
                        <br>
                        <div class="row">
                          
                          <div class="col-lg-6"> 
                            <label>Compnay Email Address:</label>
                            <input type="email" id="company_email" name="company_email" class="form-control" placeholder="Enter Compnay Email Address"></div>
                          <div class="col-lg-6"> 
                            <label>Compnay Country:</label>
                            <select class="form-control" name="company_country" id="company_country">
                              <option value="" disabled="" selected="">Select Country</option>
                            </select>
                          </div>
                        </div>

                        <br>
                        <div class="row">
                          <div class="col-lg-6"> 
                            <label>Company Default Discount %</label>
                            <input type="number" min="0" max="100" id="company_default_discount" name="company_default_discount" class="form-control" placeholder="Enter Default Discount"></div>
                          <div class="col-lg-6"> 
                            <label>Company Default Tax %</label>
                            <input type="number" id="company_default_tax" name="company_default_tax" class="form-control" placeholder="Enter Default Tax"></div>
                        </div>
                              <br>



                                          <div class="row form-group">
                                          <div class="col-lg-6">
                                            <label for="company_logo_output" class="form-control-label">Company Logo:</label><br>
                                            <img id="company_logo_output" width="130" height="130" style="border-radius: 2%; border: solid gray 1px; object-position: top; object-fit: cover;">&nbsp;&nbsp;&nbsp;<input type="file" onchange="logo_loadFile(event)" onclick="clearImage()"   name="company_logo" id="company_logo" required="" accept="image/*" >
                                          </div>
                                           <div class="col-lg-6"> 
                            <label>Company Type</label>
                           <select class="form-control" name="company_type" id="company_type">
                              <option value="" disabled="" selected="">Select Company Type</option>
                            </select>
                                        </div>
                                      </div>

                        <br>


                                        <div class="row form-group" style="background: #333; border: solid gray 2px; border-radius: 3px; color: white; padding: 25px;">
                                          <div class="col-lg-6">
                                            <label class="form-control-label">FBR Invoice Data:</label>
                                            &nbsp;&nbsp;&nbsp;
                                            <label class="switch switch-3d switch-primary switch-lg mr-3">
                                              <input onclick="FBRToggle(this.value)" type="checkbox" class="switch-input" id="company_fbr" name="company_fbr">
                                              <span class="switch-label"></span>
                                              <span class="switch-handle"></span>
                                            </label>
                                          </div>
                                          <div class="col-lg-6" id="company_pos_id_div">
                                            
                                            <label class="form-control-label" style="">POS ID:</label>&nbsp;&nbsp;&nbsp;
                                            <input type="text" class="form-control" name="company_pos_id" id="company_pos_id">

                                          </div>
                                        </div>
                                        <br>

                                        <div class="row form-group">
                                          <div class="col-lg-12">
                                            <label for="receipt_header" class="form-control-label">Text in the Receipt Header:</label>
                                            
                                            <textarea placeholder="Enter receipt header text" name="company_receipt_header" id="company_receipt_header" class="form-control" rows="4"></textarea>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row form-group">
                                          <div class="col-lg-12">
                                            <label for="receipt_footer" class="form-control-label">Text in the Receipt Footer:</label>
                                            <input type="text" id="company_receipt_footer" name="company_receipt_footer" placeholder="Enter receipt footer text" value="" class="form-control">
                                            
                                          </div>
                                        </div>
                    


                      </div>
              

                      </div>
                  <div class="modal-footer" id="CompanyModalFooter">
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







<!-- Member Type Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="MemberTypeModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog" id="MemberTypeModalDialog">
        <div class="modal-content" id="MemberTypeModalContent">
           
            <form name="memberTypeForm" enctype="multipart/form-data" id="memberTypeForm">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="MemberTypeModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="MemberTypeModalData">

                        <input type="hidden" id="member_type_id" name="member_type_id">


                        
                        <div class="row">
                          <div class="col-lg-12"> 
                            <label>Member Type Name:</label>
                            <input type="text" id="member_type_name" name="member_type_name" class="form-control" placeholder="Enter Member Type Name"></div>
                        </div>
                        

                      </div>
              

                      </div>
                  <div class="modal-footer" id="MemberTypeModalFooter">
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

  $("#credit_card_number,#cust_cc_number").mask("9999-9999-9999-9999");

  function ToNormal()
  {
    document.getElementById("prod_cost").style.border = "solid lightgray 1px"
  }

  function GetProdPrice()
  {
    val = parseFloat(document.getElementById("prod_tax").value);
    cost = parseFloat(document.getElementById("prod_cost").value);

    if (!val) 
    {
      val = 0;
    }

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

  $.validator.addMethod('CCNoFormat', function(value, element) {
        return this.optional(element) || (value.match(/^([0-9]{4})-([0-9]{4})-([0-9]{4})-([0-9]{4})$/));
    },
    'Please enter a valid credit card number.');


       $(function() {
        $("form[name='customerForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      cust_cc_number: {
        CCNoFormat:true,
      },
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
      credit_card_number: {
        CCNoFormat:true,
      },
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






     $(function() {
        $("form[name='companyForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      
      company_name: {
        required: true,
      },
      company_email: {
        required: true,
        emailFormat: true,
      },
      company_phone: {
        required: true,
      },
      company_country: {
        required: true,
      },
       company_type: {
        required: true,
      },
     
      company_pos_id: {
        required: true,
        minlength: 6,
        maxlength: 6,
        digits: true,
      },
     

    },
    messages: {
     company_name: {
        required: "Please Provide a Company Name",
      },
      company_email: {
        required: "Please Provide a Company Email",
      },
      company_phone: {
        required: "Please Provide a Company Phone",
      },
      company_country: {
        required: "Please Select a Company Country",
      },
       company_type: {
        required: "Please Select a Company Type",
      },
      
      company_pos_id: {
        required: "Please Provide a POS ID",
        minlength: "POS ID Must be of 6 Digits",
        maxlength: "POS ID Must be of 6 Digits",
      },

      
    },
    submitHandler: function(form) {

      form.submit();

    }
  });
  });




//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------


$(function() {
        $("form[name='memberTypeForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      member_type_name: {
        required: true,
      },
     
     
     

    },
    messages: {
      member_type_name: {
        required: "Please Provide a Member Type Name",
      },
      
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('memberTypeForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}add-update-member-type",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#MemberTypeModal').modal('hide');
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
                                document.getElementById('toast').style.visibility = "visible";
                                document.getElementById('toast').className = "alert alert-success alert-rounded fadeIn animated";
                                document.getElementById('toastMsg').innerHTML = get_msg;


                                 setTimeout(function() {
                             document.getElementById('toast').style.visibility = "hidden";
                                

                            }, 5000);


                            }

                            var search_text = (document.getElementById("member_type_search_text").value.trim() == "")?"0":document.getElementById("member_type_search_text").value.trim();
        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-member-type-list-AJAX/"+search_text,
        success: function(data) {

            $('#member_typeTBody').html(data);
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







// ----------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------

          function CustomerPaymentMethod(val)
          {
            if (val == "") 
            {
              alert('Kindly Select a Payment Method');
            }
            else
            {
              if (val == 1) 
              {
                document.getElementById("cust_cc").style.display = "none";
              }
              else if(val ==2)
              {
                document.getElementById("cust_cc").style.display = "flex";
                document.getElementById('cust_cc_holder').value = "";
                document.getElementById('cust_cc_number').value = "";

              }
              else
              {
                document.getElementById("cust_cc").style.display = "none";
              }

            }

          } 
          function GenerateCodeCustomer()
          {
            document.getElementById('cust_code').value = "<?php echo strtoupper(substr(session("login.company_name"), 0,2) ); ?>"+"_"+(Math.random().toString(36).substring(2, 8) + Math.random().toString(36).substring(2,8)).toUpperCase();
          }

// ----------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------




var product_loadFile = function(event) {
    var product_image_output = document.getElementById('product_image_output');
    product_image_output.src = URL.createObjectURL(event.target.files[0]);
    product_image_output.onload = function() {
      URL.revokeObjectURL(product_image_output.src) // free memory
    }
  };
</script>






















