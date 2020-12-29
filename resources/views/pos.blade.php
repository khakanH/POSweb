@extends('layouts.app')
@section('content')
  
  <style type="text/css">
    .statistic2 {
    padding-top: 10px;
}

.table td, .table th
{
  padding: 3px;
}
  
  .qty-btn{
    line-height: 0px;
    font-size: 1rem;
    padding: 3px;
    height: 20px;
  }


    .tax-dis-input{
    padding: 3px;
    line-height: 0px;
    font-size: 1rem;
    height: 25px;
    max-width: auto;
  }

   input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
               -webkit-appearance: none;
               margin: 0;
            }

            

  </style>
    
  
     <div class="tab-content">

             <div class="row">
                          <div class="main-heading">
                              <h4>POS</h4>
                          </div>

                          <div class="col-md-7 col-sm-6 col-xs-12">
                              
                              <!-- main content left -->
                              <div class="pos-left-container">
                                  <div class="left-heading">
                                      <h4>create new sale</h4>
                                  </div>
                                  <hr>
                                  <div class="left-heading">
                                      <h5>Category</h5>
                                    <!--  <div class="search-form">
                                      <input class="form-control" id="search-inp" type="text" placeholder="Search...." aria-label="Search">
                                     </div> -->
                                  </div>
                                  <div class="" style="display: flex;  float: none; height: 70px; white-space: nowrap; overflow-x:  auto; overflow-y: hidden; padding-top: 10px;">
                                     @foreach($category as $cate)
                                      <div class="col-lg-2" style="margin: 5px;">
                                         <button style="width: 100%;" onclick='GetProductByCategory("{{$cate['id']}}")' class="pos-btn btn">{{$cate['name']}}</button>
                                      </div>
                                     @endforeach
                                  </div>
                                  <hr>
                                  <div class="left-heading">
                                      <h5>Item</h5>
                                      <div class="search-form">
                                          
                                          <input class="form-control" type="text" name="search_text" id="prod_search_text" required="" style="padding-right: 105px;" placeholder="Enter Product Name or Code" onfocusout="SearchProduct(this.value)">
                                      </div>
                                      <div class="row left-heading left-slider" id="pos-prod-list" style="height: 383px; overflow-y: auto;">
                                        @foreach($product as $prod)
                                          <div class="col-lg-4">
                                              <div class="card" style="cursor: pointer;" onclick='AddProductToBill("{{$prod['id']}}","{{$prod['name']}}","{{$prod['price']}}")'>
                                    <div class="">
                                        <div class="">
                                            <img style="height: 100px;" class="rounded-circle mx-auto d-block" src="{{env('IMG_URL')}}{{$prod['image']}}" width="100" height="100" alt="{{$prod['name']}}">
                                            <hr>
                                            <center><span>{{$prod['name']}}</span></center>
                                            <center><span>{{$prod['price']}}/-</span></center>
                                            
                                        </div>
                                    </div>
                                  
                                </div>
                                          </div>
                                        @endforeach
                                      </div>
                                  </div>
                                  
                              </div>
                              <!-- main content left  ends -->
                          </div>

                          <!-- main content right satrts here -->
                          <div class="col-md-5 col-sm-6 col-xs-12 pos-rigt-content">
                              <!-- pos-left-container satrts here -->
                              <div class="pos-left-container">
                              
                               <nav>
                        <div class="nav nav-tabs" id="nav-tab_" role="tablist">
                          

                          @foreach($pending_bill as $bill)
                          <a class="nav-item nav-link <?php if ($loop->first): ?>
                            active
                          <?php endif ?>" id="nav-home-tab_" data-toggle="tab" href="#nav-home_" onclick='getBill("{{$bill['id']}}")' role="tab" aria-controls="nav-home_" aria-selected="false">{{$loop->iteration}}</a>
                          @endforeach


                          &nbsp;&nbsp;
                          <a href="javascript:void(0)" class="nav-item nav-link btn btn-primary" onclick="createNewBill()">+</a>&nbsp;&nbsp;
                          <a href="javascript:void(0)" class="nav-item nav-link btn btn-danger" onclick="deleteLastBill()">-</a>
                        </div>
                      </nav>
                      <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home_" role="tabpanel" aria-labelledby="nav-home-tab">
                         <div id="bill_pos">

                          <div class="row">
                           <div class="col-7">&nbsp;</div>
                           <div class="col-5"><i  style="float: right; padding-right: 5px; cursor: pointer;" onclick="CreateNewCustomer()" data-toggle="tooltip" title="Create Customer" class="fa fa-user"></i> <i  style="float: right; padding-right: 10px; cursor: pointer;" data-toggle="tooltip" title="Show Last Bill" onclick="ShowLastBill()" class="fa fa-list-alt"></i></div>
                          </div>

                           <div class=" row form-group">
                           <div class="col-12 mb-2 pos-right-search">
                            <input type="hidden" name="customer_list" id="customer_list" value="{{$pending_bill[0]['customer_id']}}">
                            <input type="text" id="cust_live_search_field" value="{{isset($pending_bill[0]->customer_name->customer_name)?$pending_bill[0]->customer_name->customer_name:"Walk In Customer"}}" autocomplete="off" class="form-control" onkeyup='LiveSearchCustomer(this.value)' onfocusout="CheckSelectedCustomer(this.value)" >
                            <div id="customer-search-list" style="position: absolute;border: solid lightgray 1px;width: 95%; height: auto; background: #fff;display: none;"></div>
                           </div>
                           <div class="col-12 pos-right-search"> <input type="text" autofocus="on" name="bar_code" id="bar_code" class="form-control" onkeypress="AddProductToBillBarCode(this.value)" placeholder="Enter Barcode"></div>

                           
                           <input type="hidden" name="current_bill_id" id="current_bill_id" value="{{$pending_bill[0]['id']}}">
                           

                           </div>
                          











                           <br>

                           <div class="table-responsive" style="min-height: 270px; max-height: 270px;">
                                    <table class="table table-1 table-sm">
                                        <thead class="">
                                            <tr>
                                                
                                                <th width="40%">Product</th>
                                                <th width="5%">Price</th>
                                                <th width="40%">Qty</th>
                                                <th width="10%">Total</th>
                                                <th width="15%">
                                                    <i class="fas fa-trash"></i>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="" id="bill-prod-list{{$pending_bill[0]['id']}}">
                                          @if(count($pending_bill_item)==0)
                                          <tr class="spacer"></tr>
                                          <tr class="spacer"></tr>
                                          <tr class="tr-shadow">
                                            <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
                                          </tr>

                                          @else

                                            @foreach($pending_bill_item as $bill_item)
                                            <tr class="tr-shadow" id="bill_item{{$bill_item['id']}}">
                                                
                                                <td>{{$bill_item['product_name']}}</td>
                                                <td>{{$bill_item['product_price']}}</td>
                                                <td>
                                                  <div class="" style="margin: auto; vertical-align: middle;">
                                                  <span style="cursor: pointer;" class="fa fa-minus" onclick='DecreaseBillItem("{{$bill_item['id']}}","{{$bill_item['product_id']}}")'></span><input class="qty-input" type="number" value="{{$bill_item['product_quantity']}}" onkeypress='ChangeBillProductQty(this.value,"{{$bill_item['id']}}","{{$bill_item['product_id']}}")' step="1" min="1" name="prod-qty" id="prod-qty{{$bill_item['id']}}"><span style="cursor: pointer;" class="fa fa-plus" onclick='IncreaseBillItem("{{$bill_item['id']}}","{{$bill_item['product_id']}}")'></span>
                                                  </div>
                                                </td>
                                                <td>{{$bill_item['product_subtotal']}}</td>
                                                <td>
                                                    <i class="fa fa-times" style="cursor: pointer;" onclick='DeleteBillProductItem("{{$bill_item['id']}}")'></i>
                                                </td>
                                            </tr>
                                            @endforeach
                                          @endif
                                        </tbody>
                                    </table>
                                </div>
                                  <br>
                                  <br>
                                <table width="100%" class="table-2 table table-sm bottom-table">
                                  <tbody id="bill-summary-total{{$pending_bill[0]['id']}}">
                                    <tr class="tr-1">
                                      <td width="35%">Total Items: </td>
                                      <td width="25%" style="text-align: center;">{{$pending_bill[0]['total_item']}}</td>
                                      <td width="20%" style="text-align: center;">Subtotal: </td>
                                      <td width="20%" style="text-align: right;">{{$pending_bill[0]['subtotal']}}</td>
                                    </tr>
                                    <tr>
                                      <td >Total Tax: </td>
                                      <td style="text-align: center;" colspan="2"><input autocomplete="off" type="text" class="form-control tax-dis-input" onkeypress='ApplyBillTax(this.value,"{{$pending_bill[0]['id']}}")' id="bill-tax-input{{$pending_bill[0]['id']}}" value="{{number_format($pending_bill[0]['tax_percentage'],1)}}%"></td>
                                        <td style="text-align: left;"><i style="float: right;">{{number_format($pending_bill[0]['tax_amount'],2)}}</i></td>
                                    </tr>
                                    <tr>
                                      <td >Bill Discount: </td>
                                      <td style="text-align: center;" colspan="2"><input autocomplete="off" type="text" class="form-control tax-dis-input" onkeypress='ApplyBillDiscount(this.value,"{{$pending_bill[0]['id']}}")' id="bill-discount-input{{$pending_bill[0]['id']}}" value="{{number_format($pending_bill[0]['discount_percentage'],1)}}%"></td>
                                        <td style="text-align: center;"><i style="float: right;">{{number_format($pending_bill[0]['discount_amount'],2)}}</i></td>
                                    </tr>
                                   <tr>
                                    <td colspan="2"><b>Total Payable:</b> </td>
                                    <td colspan="2">{{number_format($pending_bill[0]['total_bill'],2)}}</td>
                                   </tr>
                                  </tbody>  
                                </table>

                                <div class="row" style="margin-top: 14px;">
                                 
                                  <div class="col-6"><button style="width: 100%;" id="payment_button{{$pending_bill[0]['id']}}" class="pos-btn btn-green btn btn-primary"<?php if($pending_bill[0]['total_item'] == 0):?>
                                    disabled=""
                                  <?php endif ?> onclick='PayBill("{{$pending_bill[0]['id']}}")'>Payment</button></div>
                                   <div class="col-6"><button style="width: 100%;" onclick='CancelBill("{{$pending_bill[0]['id']}}")' class="pos-btn btn-red  btn btn-primary">Delete Sale</button></div>
                                </div>
































                         </div>

                        </div>
                       
                       
                      </div>                                 

                                 
                              </div>
                               <!-- pos-left-container ends here -->  
                          </div>
                          <!-- pos-left-container ends here -->
                          <!-- main content ends here -->
                      </div>

           

        </div>


        <script type="text/javascript">
          
       


          function AddProductToBillBarCode(val)
          { 
            var x = event.which || event.keyCode;
            if (!(x == 13 || x == 'Enter')) 
            {
              return false;
            }

            val = val.trim();
            if (!val) 
            {
              return false;
            }

              $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}get-pos-product-by-barcode/"+val,
                     beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {
                            $('#LoadingModal').modal('hide');
                            document.getElementById("bar_code").value ="";
                            document.getElementById("bar_code").focus();

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
                                    var bill_id = document.getElementById("current_bill_id").value;

                                  AddProductToBill(data['result']['id'],data['result']['name'],data['result']['price']);
                                }                        
                          
                          return;
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
              });
          }          

          function GetAllProducts() 
          {
            txt =document.getElementById("prod_search_text").value.trim();

            if (!txt) 
            {
              txt = "0"; 
            }

            $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}get-pos-product-list/"+"0"+"/"+txt,
                     beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {
                            $('#LoadingModal').modal('hide');

                        $('#pos-prod-list').html(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
                });
          }
          function GetProductByCategory(cate_id) 
          { 
            txt =document.getElementById("prod_search_text").value.trim();

            if (!txt) 
            {
              txt = "0"; 
            }

            $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}get-pos-product-list/"+cate_id+"/"+txt,
                    beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {
                            $('#LoadingModal').modal('hide');

                        $('#pos-prod-list').html(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
                });
          }

          function SearchProduct(value)
          {   
              var txt = value.trim();

              if (!txt) 
              {
                  txt = "0";
              }

                   $.ajax({
                      type: "GET",
                      url: "{{ env('APP_URL')}}get-pos-product-list/"+"0"+"/"+txt,
                      beforeSend: function(){
                              $('#LoadingModal').modal('show');
                          },
                      success: function(data) {
                              $('#LoadingModal').modal('hide');

                          $('#pos-prod-list').html(data);
                      },
                      error: function(jqXHR, textStatus, errorThrown) {
                          alert('Exception:' + errorThrown);
                      }
                  });
          }


        function getBill(id)
        {

          $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}get-pending-bill/"+id,
                    beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {
                            $('#LoadingModal').modal('hide');

                            
                                    $('#bill_pos').html(data);
                                    document.getElementById("bar_code").focus();

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
                });
        }

        function createNewBill()
        {
           $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}create-new-bill",
                    beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {

                        $.ajax({
                            type: "GET",
                            url: "{{ env('APP_URL')}}get-bill-nav-links",
                            success: function(data_) {
                              $('#LoadingModal').modal('hide');
                                    
                                    $('#nav-tab_').html(data_);
                                    $('#bill_pos').html(data);
                                    document.getElementById("bar_code").focus();



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });
                            
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
                });

         
        }
        function deleteLastBill()
        {
          $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}delete-last-bill",
                    beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {

                            $.ajax({
                            type: "GET",
                            url: "{{ env('APP_URL')}}get-bill-nav-links",
                            success: function(data_) {
                                    
                              $('#LoadingModal').modal('hide');
                                    $('#nav-tab_').html(data_);
                                    $('#bill_pos').html(data);
                                    document.getElementById("bar_code").focus();



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });





                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
                });
        }

        function CancelBill(id)
        { 

          $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}cancel-bill/"+id,
                    beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {

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

                            $.ajax({
                            type: "GET",
                            url: "{{ env('APP_URL')}}get-pending-bill/"+id,
                            success: function(data_) {
                                    
                                    $('#LoadingModal').modal('hide');
                                    $('#bill_pos').html(data_);
                                    document.getElementById("bar_code").focus();



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });
                                    document.getElementById('toast').style.visibility = "visible";
                                    document.getElementById('toast').className = "alert alert-success alert-rounded fadeIn animated";
                                    document.getElementById('toastMsg').innerHTML =  get_msg;


                                     setTimeout(function() {
                                   
                                    document.getElementById('toast').style.visibility = "hidden";

                                }, 5000);


                                }

                            





                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
                });
        }


        function AddProductToBill(id,name,price)
        {
          var bill_id = document.getElementById("current_bill_id").value;


          $.ajax({
                  type: "POST",
                  url: "{{ env('APP_URL')}}add-product-to-bill",
                  data: { 
                          "bill_id":bill_id,
                          "prod_id":id,
                          "prod_name":name,
                          "prod_price":price,
                          "_token": $('meta[name="csrf-token"]').attr('content') },
                          beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                  dataType: "html",
                  success: function(data) {
                            $('#LoadingModal').modal('hide');
                          
                            $('#bill-prod-list'+bill_id).html(data);

                            document.getElementById("payment_button"+bill_id).disabled =false;

                             $.ajax({
                            type: "GET",

                            url: "{{ env('APP_URL')}}calculate-total-bill/"+bill_id,
                            success: function(data_) {
                                    
                                    $('#bill-summary-total'+bill_id).html(data_);



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });



                            
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                  }
          });

        }

        function DeleteBillProductItem(item_id)
        {
          var bill_id = document.getElementById("current_bill_id").value;

          $.ajax({
                  type: "POST",
                  url: "{{ env('APP_URL')}}delete-product-from-bill",
                  data: { 
                          "bill_id":bill_id,
                          "item_id":item_id,
                          "_token": $('meta[name="csrf-token"]').attr('content') },
                          beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                  success: function(data) {
                          
                            $('#bill-prod-list'+bill_id).html(data);

                             $.ajax({
                            type: "GET",

                            url: "{{ env('APP_URL')}}calculate-total-bill/"+bill_id,
                            success: function(data_) {
                                    
                            $('#LoadingModal').modal('hide');
                                    $('#bill-summary-total'+bill_id).html(data_);



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });



                            
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                  }
          });

        }


        function DecreaseBillItem(bill_item_id,prod_id)
        {
          var bill_id = document.getElementById("current_bill_id").value;

          if (document.getElementById('prod-qty'+bill_item_id).value <= 1) 
          {
            return;
          }


           $.ajax({
                  type: "POST",
                  url: "{{ env('APP_URL')}}decrease-bill-product-item",
                  data: { 
                          "bill_id":bill_id,
                          "bill_item_id":bill_item_id,
                          "prod_id":prod_id,
                          "_token": $('meta[name="csrf-token"]').attr('content') },
                          beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                  success: function(data) {
                            $('#LoadingModal').modal('hide');
                          
                            
                            $('#bill-prod-list'+bill_id).html(data);


                             $.ajax({
                            type: "GET",

                            url: "{{ env('APP_URL')}}calculate-total-bill/"+bill_id,
                            success: function(data_) {
                                    
                                    $('#bill-summary-total'+bill_id).html(data_);



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });



                            
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                  }
          });
        }

        function IncreaseBillItem(bill_item_id,prod_id)
        {
          var bill_id = document.getElementById("current_bill_id").value;

           $.ajax({
                  type: "POST",
                  url: "{{ env('APP_URL')}}increase-bill-product-item",
                  data: { 
                          "bill_id":bill_id,
                          "bill_item_id":bill_item_id,
                          "prod_id":prod_id,
                          "_token": $('meta[name="csrf-token"]').attr('content') },
                          beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                  success: function(data) {
                            $('#LoadingModal').modal('hide');
                          
                            $('#bill-prod-list'+bill_id).html(data);
                           

                             $.ajax({
                            type: "GET",

                            url: "{{ env('APP_URL')}}calculate-total-bill/"+bill_id,
                            success: function(data_) {
                                    
                                    $('#bill-summary-total'+bill_id).html(data_);



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });



                            
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                  }
          });
        }

        function ChangeBillProductQty(val,bill_item_id,prod_id)
        {

            var x = event.which || event.keyCode;
            if (!(x == 13 || x == 'Enter')) 
            {
              return false;
            }


          if (val < 1) 
          {
            return;
          }
          var bill_id = document.getElementById("current_bill_id").value;

           $.ajax({
                  type: "POST",
                  url: "{{ env('APP_URL')}}change-bill-product-quantity",
                  data: { 
                          "bill_id":bill_id,
                          "bill_item_id":bill_item_id,
                          "prod_id":prod_id,
                          "prod_qty":val,
                          "_token": $('meta[name="csrf-token"]').attr('content') },
                          beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                  success: function(data) {
                          
                            $('#bill-prod-list'+bill_id).html(data);
                           

                             $.ajax({
                            type: "GET",

                            url: "{{ env('APP_URL')}}calculate-total-bill/"+bill_id,
                            success: function(data_) {
                                    
                                    $('#LoadingModal').modal('hide');
                                    $('#bill-summary-total'+bill_id).html(data_);



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });



                            
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                  }
          });
        }


        function ApplyBillTax(val,bill_id)
        {

          var x = event.which || event.keyCode;
            if (!(x == 13 || x == 'Enter')) 
            {
              return false;
            }


          val = parseFloat(val)?parseFloat(val):0;

          if(val < 0 || val > 100)
          {
            document.getElementById('bill-tax-input'+bill_id).value = "0%";
            document.getElementById('bill-tax-input'+bill_id).style.border = "solid red 1px";
          }
          else
          {
            document.getElementById('bill-tax-input'+bill_id).value = val+"%";
            document.getElementById('bill-tax-input'+bill_id).style.border = "solid lightgray 1px";
            
                $.ajax({
                  type: "POST",
                  url: "{{ env('APP_URL')}}apply-bill-tax",
                  data: { 
                          "bill_id":bill_id,
                          "tax":val,
                          "_token": $('meta[name="csrf-token"]').attr('content') },
                          beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                  success: function(data) {

                             $.ajax({
                            type: "GET",

                            url: "{{ env('APP_URL')}}calculate-total-bill/"+bill_id,
                            success: function(data_) {
                                    
                                    $('#LoadingModal').modal('hide');
                                    $('#bill-summary-total'+bill_id).html(data_);



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });



                            
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                  }
          });



          }
        }
         function ApplyBillDiscount(val,bill_id)
         {

          var x = event.which || event.keyCode;
            if (!(x == 13 || x == 'Enter')) 
            {
              return false;
            }


            if (val.substr(val.length - 1) == "%") 
            {
              val = parseFloat(val)?parseFloat(val):0;
              if(val < 0 || val > 100)
              {
                document.getElementById('bill-discount-input'+bill_id).value = "0%";
                document.getElementById('bill-discount-input'+bill_id).style.border = "solid red 1px";

                return;
              }
              else
              {
                document.getElementById('bill-discount-input'+bill_id).value = val+"%";
                document.getElementById('bill-discount-input'+bill_id).style.border = "solid lightgray 1px";
                         $.ajax({
                  type: "POST",
                  url: "{{ env('APP_URL')}}apply-bill-discount",
                  data: { 
                          "bill_id":bill_id,
                          "discount":val,
                          "type": 1, 
                          "_token": $('meta[name="csrf-token"]').attr('content') },
                          beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                  success: function(data) {

                             $.ajax({
                            type: "GET",

                            url: "{{ env('APP_URL')}}calculate-total-bill/"+bill_id,
                            success: function(data_) {
                                    
                                    $('#LoadingModal').modal('hide');
                                    $('#bill-summary-total'+bill_id).html(data_);



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });



                            
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                  }
          });
              }
         

            }
            else
            {

              val = parseFloat(val)?parseFloat(val):0;
                                $.ajax({
                  type: "POST",
                  url: "{{ env('APP_URL')}}apply-bill-discount",
                  data: { 
                          "bill_id":bill_id,
                          "discount":val,
                          "type": 2, 
                          "_token": $('meta[name="csrf-token"]').attr('content') },
                          beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                  success: function(data) {

                             $.ajax({
                            type: "GET",

                            url: "{{ env('APP_URL')}}calculate-total-bill/"+bill_id,
                            success: function(data_) {
                                    
                                    $('#LoadingModal').modal('hide');
                                    $('#bill-summary-total'+bill_id).html(data_);



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });



                            
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                  }
          });
            }

         }


          function CreateNewCustomer()
          {
            document.getElementById('cust_name').value = "";
            document.getElementById('cust_email').value = "";
            document.getElementById('cust_phone').value = "";
            document.getElementById('cust_discount').value = "";
            document.getElementById('cust_id').value = "";

            document.getElementById('cust_code').value = "";

            document.getElementById("cust_cc").style.display = "none";
            
            document.getElementById('create_type').value = "1";

            $('#CustomerModal').modal('show');
            $('#CustomerModalLabel').html('Add New Customer');

            document.getElementById('CustomerModal').style.backgroundColor="rgba(0,0,0,0.8)";
            document.getElementById('CustomerModalDialog').style.paddingTop="0px";
            document.getElementById('CustomerModalData').style.padding="5px 5px 0px 5px";
             $.ajax({
                            type: "GET",

                            url: "{{ env('APP_URL')}}get-payment-method/"+"0",
                            success: function(data) {
                                    $('#cust_payment_method').html(data);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                          });

          }



          function ChangeBillCustomer(val,bill_id)
          {
            $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}change-bill-customer/"+val+"/"+bill_id,
                    success: function(data) {
                          
                          $.ajax({
                            type: "GET",

                            url: "{{ env('APP_URL')}}calculate-total-bill/"+bill_id,
                            success: function(data_) {
                                    $('#bill-summary-total'+bill_id).html(data_);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                          });              
                      
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                      alert('Exception:' + errorThrown);
                            }
                    }); 
          }

          function PayBill(bill_id)
          {

            $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}get-bill-details/"+bill_id,
                    success: function(data) {

                            $('#BillModalData').html(data);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                      alert('Exception:' + errorThrown);
                    }
            });     


            $('#BillModal').modal('show');
            $('#BillModalLabel').html('Add Sale');

            document.getElementById('BillModal').style.backgroundColor="rgba(0,0,0,0.8)";
            document.getElementById('BillModalDialog').style.paddingTop="0px";
            document.getElementById('BillModalData').style.padding="5px 5px 0px 5px";
          }
          
          function CheckPaymentMethod(val)
          {
            if (val == "") 
            {
              alert('Kindly Select a Payment Method');
              return;
            }
            else
            {
              if (val == 1) 
              {
                document.getElementById("for_cash").style.display = "block";
                document.getElementById("for_credit_card").style.display = "none";
                document.getElementById("for_cheque").style.display = "none";
              }
              else if(val ==2)
              {
                document.getElementById("for_cash").style.display = "none";
                document.getElementById("for_credit_card").style.display = "block";
                document.getElementById("for_cheque").style.display = "none";
              }
              else if(val ==3)
              {
                document.getElementById("for_cash").style.display = "none";
                document.getElementById("for_credit_card").style.display = "none";
                document.getElementById("for_cheque").style.display = "block";
              }
              else
              {

                document.getElementById("for_cash").style.display = "none";
                document.getElementById("for_credit_card").style.display = "none";
                document.getElementById("for_cheque").style.display = "none";
              }

            }

          }                       


              function CalculateBillChange(val)
              { 
                val = parseFloat(val)?parseFloat(val):0.0;

                total_bill = parseFloat(document.getElementById("total_bill_amount").value);
                
                change = parseFloat(val - total_bill);

                if (change < 0) 
                {
                  document.getElementById("bill-payment-btn").disabled = true;
                }
                else
                {
                  document.getElementById("bill-payment-btn").disabled = false;

                }

                document.getElementById("bill_change").innerHTML = change.toFixed(2);
                document.getElementById("bill_cash_change").value = change.toFixed(2);
              }


              

              function ShowLastBill()
              {
                 $('#ReceiptModal').modal('show');
                               
                                 $.ajax({
                                    type: "GET",

                                    url: "{{ env('APP_URL')}}get-bill-receipt/"+"0",
                                    success: function(data) {
                                           
                                      $('#ReceiptModalData').html(data);

                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        alert('Exception:' + errorThrown);
                                    }
                                  });      
              }

              function PrintReceipt()
              {
                  var ua = navigator.userAgent.toLowerCase();
                  var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
                  
                  var divToPrint=document.getElementById("ReceiptModalData");
                   newWin= window.open("Print");
                   newWin.document.write(divToPrint.innerHTML);
                   
                  if (isAndroid) 
                  {
                    // https://developers.google.com/cloud-print/docs/gadget
                    // var gadget = new cloudprint.Gadget();
                    // gadget.setPrintDocument("url", $('title').html(), window.location.href, "utf-8");
                    // gadget.openPrintDialog();
                  } 
                  else 
                  {
                  }
                  newWin.print();
                  newWin.close();
              }


              function LiveSearchCustomer(val)
              {
                
                var bill_id = document.getElementById("current_bill_id").value;
                val = val.trim();
                if (val) 
                {
                  document.getElementById("customer-search-list").style.display="block";

                  $.ajax({
                                    type: "GET",

                                    url: "{{ env('APP_URL')}}get-customer-live-search-list/"+val+"/"+bill_id,
                                      beforeSend: function(){
                                      $('#customer-search-list').html('<center><img src="<?php echo env('IMG_URL') ?>loading.gif" width="30" height="30"><br></center>');
                                      },
                                    success: function(data) {
                                      $('#customer-search-list').html('');
                                           
                                      get_status = data['status'];
                                      if (get_status == 0) 
                                      {
                                        document.getElementById("customer-search-list").style.display="none";
                                        document.getElementById("customer-search-list").innerHTML="";


                                      }
                                      else
                                      {
                                        $('#customer-search-list').html(data);
                                      }

                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        alert('Exception:' + errorThrown);
                                    }
                                  });

                }
                else
                {
                  document.getElementById("customer-search-list").style.display="none";
                  document.getElementById("customer-search-list").innerHTML="";


                }
              }

              function SelectCustomer(name,id)
              {
                document.getElementById("cust_live_search_field").value =name;
                document.getElementById("customer_list").value =id;
                document.getElementById("customer-search-list").style.display="none";
              }

              function CheckSelectedCustomer(val)
              {
                var bill_id = document.getElementById("current_bill_id").value;
                if (!val.trim()) 
                {
                  ChangeBillCustomer("0",bill_id);
                  document.getElementById("cust_live_search_field").value ="Walk In Customer";
                  document.getElementById("customer_list").value ="0"; 
                }
              }


        </script>
                      


  
@endsection