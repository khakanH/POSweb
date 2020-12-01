
<link href="{{ asset('css/fontawesome-iconpicker.min.css?'.time().'')}}" rel="stylesheet">
<script src="{{ asset('js/fontawesome-iconpicker.js')}}"></script>


<div id="MemberDetailModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog modal-lg" id="MemberDetailModalDialog">
        <div class="modal-content" id="MemberDetailModalContent">
          <div id="MemberDetailModalLoading" style="display: none;"><center><img style="margin-top: -25px; border-radius: 5px; height: 6px;" src="<?php echo env('IMG_URL') ?>loading_bar.gif" width="100%" height="6"></center></div>
           
                  
                  <div class="modal-body" id="MemberDetailModalData">

                  </div>
                  
                  <div class="modal-footer" id="MemberDetailModalFooter">
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


<!-- Country Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="CountryModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog modal-lg" id="CountryModalDialog">
        <div class="modal-content" id="CountryModalContent">
           
            <form name="countryForm" enctype="multipart/form-data" id="countryForm">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="CountryModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="CountryModalData">

                        <input type="hidden" id="country_id" name="country_id">


                        
                        <div class="row">
                          <div class="col-lg-6"> 
                            <label>Country Name:</label>
                            <input type="text" id="country_name" name="country_name" class="form-control" placeholder="Enter Country Name"></div>
                            <div class="col-lg-6"> 
                            <label>Country Code:</label>
                            <input type="text" id="country_code" name="country_code" class="form-control" placeholder="Enter Country Code"></div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-lg-6"> 
                            <label>Curreny Standard Name:</label>
                             <input type="text" id="currency_standard_name" name="currency_standard_name" class="form-control" placeholder="Enter Currency Standard Name">
                          </div>
                          <div class="col-lg-6"> 
                            <label>Currency Symbol: </label>
                             <input type="text" id="currency_short_name" name="currency_short_name" class="form-control" placeholder="Enter Currency Symbol">
                          </div>
                        </div>
                        <br>
                        <div class="row" id="country_flag_div">
                          <div class="col-lg-12"> 
                            <label>Country Flag:</label><br>
                            <span style="font-size: 12px; width: 100%;  z-index: 1; display: flex; margin: 0px;"><select id="myDropdown" name="flag_list" class="form-control" required="">

                              <?php 

                                if (Request::is('admin/country-list')) 
                                {
                                  $images = \File::allFiles(public_path('images/country_flags'));      
                                }
                                else
                                {
                                  $images =[];
                                } 

                                foreach ($images as $key):
                              ?>
                              <option value="<?php echo "/country_flags/".$key->getFilename() ?>" data-imagesrc="<?php echo env('IMG_URL')."country_flags/".$key->getFilename() ?>" data-description="&nbsp;&nbsp;&nbsp;"><?php echo ucfirst(explode("-flag", $key->getFilename())[0]) ; ?></option>
                            <?php endforeach; ?>

                            </select></span>
                          </div>
                        </div>


                      </div>
              

                      </div>
                  <div class="modal-footer" id="CountryModalFooter">
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



<!-- Payment Method Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="PaymentModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog" id="PaymentModalDialog">
        <div class="modal-content" id="PaymentModalContent">
           
            <form name="paymentForm" enctype="multipart/form-data" id="paymentForm">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="PaymentModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="PaymentModalData">

                        <input type="hidden" id="payment_id" name="payment_id">


                        
                        <div class="row">
                          <div class="col-lg-12"> 
                            <label>Payment Name:</label>
                            <input type="text" id="payment_name" name="payment_name" class="form-control" placeholder="Enter Payment Name"></div>
                        </div>
                        

                      </div>
              

                      </div>
                  <div class="modal-footer" id="PaymentModalFooter">
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



<!-- Module Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="ModuleModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog" id="ModuleModalDialog">
        <div class="modal-content" id="ModuleModalContent">
           
            <form name="moduleForm" enctype="multipart/form-data" id="moduleForm">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="ModuleModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="ModuleModalData">

                        <input type="hidden" id="module_id" name="module_id">
                        <input type="hidden" id="module_parent_id" name="module_parent_id">


                        
                        <div class="row">
                          <div class="col-lg-12"> 
                            <label>Module Name:</label>
                            <input type="text" id="module_name" name="module_name" class="form-control" placeholder="Enter Module Name"></div>
                            <div class="col-lg-12"> 
                            <label>Module Route:</label>
                            <input type="text" id="module_route" name="module_route" class="form-control" placeholder="Enter Module Route"></div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-lg-6"> 
                            <label>Module Icon:</label>
                            <input type="hidden" name="icon" id="icon" value="">
                                        <button style="width: 100%;" data-selected="graduation-cap" type="button" class="btn btn-small btn-rounded btn-dark-solid icp demo dropdown-toggle iconpicker-component" data-toggle="dropdown">
                                            Select Icon &nbsp;&nbsp;&nbsp; <i id="selected_icon" class=""></i>
                                    
                                        </button>
                                        <div class="dropdown-menu"></div>
    
                                        </div>
                          
                        </div>
                        

                      </div>
              

                      </div>
                  <div class="modal-footer" id="ModuleModalFooter">
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












<!-- Notification Modal                                         -->
<!-- ----------------------------------------------------------------------------------------------------- -->

<div id="NotificationModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog" id="NotificationModalDialog">
        <div class="modal-content" id="NotificationModalContent">
           
            <form name="notificationForm" enctype="multipart/form-data" id="notificationForm">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="NotificationModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="NotificationModalData">

                        <input type="hidden" id="receiver_id" name="receiver_id">


                        
                        <div class="row">
                          <div class="col-lg-12"> 
                            <label>Title:</label>
                            <input type="text" id="notifi_title" name="notifi_title" class="form-control" placeholder="Enter Notification Title"></div>
                            
                        </div>
                        <div class="row">
                          <div class="col-lg-12"> 
                            <label>Description:</label>
                            <textarea class="form-control" rows="3" name="notifi_description" id="notifi_description" placeholder="Enter Notification Description"></textarea></div>
                        </div>
                        <div class="row">
                          <div class="col-lg-12"> 
                            <label>Type:</label>
                            <select class="form-control" name="notifi_type" id="notifi_type">
                              <option value="1">Info</option>
                              <option value="2">Warning</option>
                              <option value="3">Danger</option>
                              <option value="4">Other</option>
                            </select>  
                          </div>
                        </div>

                      </div>
              

                      </div>
                  <div class="modal-footer" id="NotificationModalFooter">
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












<script src="{{ asset('js/imgDropDown.js') }}"></script>


<script type="text/javascript">




 $(function() {
        $("form[name='countryForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      country_name: {
        required: true,
      },
      country_code: {
        required: true,
      },
      currency_standard_name: {
        required: true,
      },
      currency_short_name: {
        required: true,
      },
      flag_list: {
        required: true,
      },
     
     

    },
    messages: {
      country_name: {
        required: "Please Provide a Country Name",
      },
      country_code: {
        required: "Please Provide a Country Code",
      },
      currency_standard_name: {
        required: "Please Provide a Currency Standard Name ",
      },
      currency_short_name: {
        required: "Please Provide a Currency Symbol ",
      },
      flag_list: {
        required: "Please Select a Flag Icon ",
      },
      
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('countryForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}admin/add-update-country",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#CountryModal').modal('hide');
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

                            var search_text = (document.getElementById("country_search_text").value.trim() == "")?"0":document.getElementById("country_search_text").value.trim();
        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/get-country-list-AJAX/"+search_text,
        success: function(data) {

            $('#countryTBody').html(data);
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


$(function() {
        $("form[name='paymentForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      payment_name: {
        required: true,
      },
     
     
     

    },
    messages: {
      payment_name: {
        required: "Please Provide a Payment Name",
      },
      
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('paymentForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}admin/add-update-payment-method",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#PaymentModal').modal('hide');
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

                            var search_text = (document.getElementById("payment_search_text").value.trim() == "")?"0":document.getElementById("payment_search_text").value.trim();
        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/get-payment-list-AJAX/"+search_text,
        success: function(data) {

            $('#paymentTBody').html(data);
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




$(function() {
        $("form[name='moduleForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      module_name: {
        required: true,
      },
       module_route: {
        required: true,
      },
      icon: {
        required: true,
      },

    },
    messages: {
      module_name: {
        required: "Please Provide a Module Name",
      },
       module_route: {
        required: "Please Provide a Module Route",
      },
      icon: {
        required: "Please Select a Module Icon",
      },
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('moduleForm');

         if (document.getElementById("selected_icon").className == "iconpicker-component") 
        {
            document.getElementById('icon').value = "";
        }
        else
        {
            document.getElementById('icon').value = document.getElementById("selected_icon").className;
        }
        
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}admin/add-update-module",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#ModuleModal').modal('hide');
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

                            var search_text = (document.getElementById("module_search_text").value.trim() == "")?"0":document.getElementById("module_search_text").value.trim();
        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/get-module-list-AJAX/"+search_text,
        success: function(data) {

            $('#moduleTBody').html(data);
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
        url: "{{ env('APP_URL')}}admin/add-update-member-type",
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
        url: "{{ env('APP_URL')}}admin/get-member-type-list-AJAX/"+search_text,
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



$(function() {
        $("form[name='notificationForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      notifi_title: {
        required: true,
      },
      
      notifi_type: {
        required: true,
      },
     
     
     

    },
    messages: {
      notifi_title: {
        required: "Please Provide a Notification Title",
      },
      
      notifi_type: {
        required: "Please Select a Notification Type",
      },
     
      
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('notificationForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}admin/send-notification-to-users",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#NotificationModal').modal('hide');
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

    }
  });

    }
  });
  });





// ----------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------













  $('.demo').iconpicker();





  $('#myDropdown').ddslick({
     width:370,
     height:200,
      imagePosition: "left",
      onSelected: function(selectedData){
      }   
  });
</script>