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
  .qty-input{
    width: 40%;
    padding: 3px;
    line-height: 0px;
    font-size: 1rem;
    margin-right:2px; 
    margin-left:2px;
    height: 25px;
  }

    .tax-dis-input{
    padding: 3px;
    line-height: 0px;
    font-size: 1rem;
    height: 25px;
    max-width: 100px;
    margin-right: 25px;
  }

   input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
               -webkit-appearance: none;
               margin: 0;
            }

  </style>
    
  
     <div class="page-content--bgf7">

            <section class="statistic statistic2">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">

                          <div class="card">
                 
                  <div class="card-body">
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

                           <h5>Choose Client</h5>
                           <div class=" row form-group">
                           <div class="col-7"><select class="form-control" name="customer_list" id="customer_list">
                             <option value="0">Walk in Customer</option>
                             @foreach($customers as $cust)
                             <option 
                              <?php if ($pending_bill[0]['customer_id']==$cust['id']): ?>
                                  selected
                              <?php endif ?>
                              value="{{$cust['id']}}">{{$cust['customer_name']}}</option>
                             @endforeach
                           </select></div>
                           <div class="col-5"> <input type="text" name="bar_code" class="form-control" placeholder="Enter Barcode"></div>

                           <!-- ______________________________________________ -->
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <input type="hidden" name="current_bill_id" id="current_bill_id" value="{{$pending_bill[0]['id']}}">
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <!-- ______________________________________________ -->

                           </div>
                          













                           <div class="table-responsive" style="min-height: 200px; max-height: 200px;">
                                    <table class="table table-data2">
                                        <thead class="text-sm-center">
                                            <tr>
                                                <th width="5%">
                                                    
                                                </th>
                                                <th width="40%">Product</th>
                                                <th width="10%">Price</th>
                                                <th width="25%">Qty</th>
                                                <th width="20%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm-center" id="bill-prod-list{{$pending_bill[0]['id']}}">
                                          @if(count($pending_bill_item)==0)
                                          <tr class="spacer"></tr>
                                          <tr class="spacer"></tr>
                                          <tr class="tr-shadow">
                                            <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
                                          </tr>

                                          @else

                                            @foreach($pending_bill_item as $bill_item)
                                            <tr class="tr-shadow" id="bill_item{{$bill_item['id']}}">
                                                <td>
                                                    <i class="fa fa-times-circle text-danger" style="cursor: pointer;" onclick='DeleteBillProductItem("{{$bill_item['id']}}")'></i>
                                                </td>
                                                <td>{{$bill_item['product_name']}}</td>
                                                <td>{{$bill_item['product_price']}}</td>
                                                <td>
                                                  <div class="row" style="margin: auto; vertical-align: middle;">
                                                  <button class="btn btn-primary qty-btn" onclick='DecreaseBillItem("{{$bill_item['id']}}","{{$bill_item['product_id']}}")'>-</button><input class="form-control qty-input" type="number" value="{{$bill_item['product_quantity']}}" onfocusout='ChangeBillProductQty(this.value,"{{$bill_item['id']}}","{{$bill_item['product_id']}}")' step="1" min="1" name="prod-qty" id="prod-qty{{$bill_item['id']}}"><button class="btn btn-primary qty-btn" onclick='IncreaseBillItem("{{$bill_item['id']}}","{{$bill_item['product_id']}}")'>+</button>
                                                  </div>
                                                </td>
                                                <td>{{$bill_item['product_subtotal']}}</td>
                                            </tr>
                                            @endforeach
                                          @endif
                                        </tbody>
                                    </table>
                                </div>
                                  <br>
                                <table width="100%" class="table">
                                  <tbody id="bill-summary-total{{$pending_bill[0]['id']}}">
                                    <tr><td width="50%" style="background: #ecf0f1;">SubTotal: </td><td>
                                      <span style="float: left;">{{$pending_bill[0]['subtotal']}}</span><i style="float: right;"><b>{{$pending_bill[0]['total_item']}}</b> items</i>
                                    </td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Order Tax: </td><td><div class="d-flex"><span style="float: left;"><input type="text" class="form-control tax-dis-input" onfocusout='ApplyBillTax(this.value,"{{$pending_bill[0]['id']}}")' id="bill-tax-input{{$pending_bill[0]['id']}}" value="{{$pending_bill[0]['tax_percentage']}}%"></span><i style="float: right;">{{$pending_bill[0]['tax_amount']}}</i></div></td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Discount: </td><td><div class="d-flex"><span style="float: left;"><input type="text" class="form-control tax-dis-input" onfocusout='ApplyBillDiscount(this.value,"{{$pending_bill[0]['id']}}")' name=""></span><i style="float: right;">here</i></div></td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Total: </td><td>{{number_format($pending_bill[0]['total_bill'],2)}}</td></tr>
                                  </tbody>  
                                </table>

                                <div class="row" style="margin-top: 14px;">
                                  <div class="col-6"><button style="width: 100%;" onclick='CancelBill("{{$pending_bill[0]['id']}}")' class="btn btn-danger">Cancel</button></div>
                                  <div class="col-6"><button style="width: 100%;" class="btn btn-success">Payment</button></div>
                                </div>
































                         </div>

                        </div>
                       
                       
                      </div>

                  </div>
                </div>


                        </div>
                        <div class="col-lg-8">
                          <div class="card">
                 
                  <div class="card-body">
                      <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                          <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" onclick="GetAllProducts()" aria-selected="false">Home</a>
                          @foreach($category as $cate)
                          <a class="nav-item nav-link" id="cate{{$cate['id']}}" data-toggle="tab" href="#nav-home" onclick='GetProductByCategory("{{$cate['id']}}")' role="tab" aria-controls="nav-profile" aria-selected="false">{{$cate['name']}}</a>
                          @endforeach
                        </div>
                      </nav>
                      <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                          <div class="form-group">
                                        
                                    <input class="au-input au-input--full" type="text" name="search_text" id="prod_search_text" required="" style="padding-right: 105px;" placeholder="Enter Product Name or Code" onfocusout="SearchProduct(this.value)">
                                    <button class="btn btn-primary" style="float: right;position: absolute; margin: 0px 0px 0px -43px; height: 44px;"><i class="fa fa-search"></i></button>
                                    </div>
                          <div class="row" id="pos-prod-list" style="min-height: 420px; max-height: 420px; overflow: auto;">
                          @foreach($product as $prod)
                          <div class="col-3">
                                <div class="card" style="cursor: pointer;" onclick='AddProductToBill("{{$prod['id']}}","{{$prod['name']}}","{{$prod['price']}}")'>
                                    <div class="card-body">
                                        <div class="mx-auto d-block">
                                            <img class="rounded-circle mx-auto d-block" src="{{env('IMG_URL')}}{{$prod['image']}}" width="100" height="100" alt="{{$prod['name']}}">
                                            <hr>
                                            <h5 class="text-sm-center mt-2 mb-1">{{$prod['name']}}</h5>
                                            <div class="location text-sm-center">
                                                {{$prod['price']}} /-</div>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                          @endforeach
                          </div>

                        </div>
                       
                       
                      </div>

                  </div>
                </div>
                        </div>
                        
                    </div>
                </div>
            </section>

           

        </div>


        <script type="text/javascript">
          
          $('.tax-dis-input').keydown(function (e) {
          

          var key = e.keyCode;
          
          if (! ((key >= 48 && key <= 57) || key == 8 || key == 9 || key == 13 || key == 16 || key == 37 || key == 39 || key == 46 || (key >= 96 && key <= 105)) ) {
          
            e.preventDefault();
            
          }

        
      });


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



        //______________ B I L L -- G E N E R A T I O N ___________________________

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
                    url: "{{ env('APP_URL')}}create-new-bill/",
                    beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {
                            $('#LoadingModal').modal('hide');

                            $.ajax({
                            type: "GET",
                            url: "{{ env('APP_URL')}}get-bill-nav-links/",
                            success: function(data_) {
                                    
                                    $('#nav-tab_').html(data_);
                                    $('#bill_pos').html(data);



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
                    url: "{{ env('APP_URL')}}delete-last-bill/",
                    beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {
                            $('#LoadingModal').modal('hide');

                            $.ajax({
                            type: "GET",
                            url: "{{ env('APP_URL')}}get-bill-nav-links/",
                            success: function(data_) {
                                    
                                    $('#nav-tab_').html(data_);
                                    $('#bill_pos').html(data);



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
                                    document.getElementById('toast').className = "alert alert-danger alert-rounded fadeOut animated";

                                }, 5000);

                                }
                                else
                                { 
                                    $('#LoadingModal').modal('hide');

                            $.ajax({
                            type: "GET",
                            url: "{{ env('APP_URL')}}get-pending-bill/"+id,
                            success: function(data_) {
                                    
                                    $('#bill_pos').html(data_);



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                        });
                                    document.getElementById('toast').style.visibility = "visible";
                                    document.getElementById('toast').className = "alert alert-success alert-rounded fadeIn animated";
                                    document.getElementById('toastMsg').innerHTML =  get_msg;


                                     setTimeout(function() {
                                    document.getElementById('toast').className = "alert alert-success alert-rounded fadeOut animated";
                                    document.getElementById('toast').style.visibility = "hidden";

                                }, 5000);


                                }

                            





                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
                });
        }
        //________________________________________________________________________



        //______________ A D D -- P R O D U C T -- T O -- B I L L_________________

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

        //________________________________________________________________________



        //______________ B I L L -- T A X -- D I S C O U N T _________________


        function ApplyBillTax(val,bill_id)
        {
          val = parseInt(val)?parseInt(val):0;

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
                            $('#LoadingModal').modal('hide');

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
        }


        </script>
                      


  
@endsection