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
                           </div>
                          













                           <div class="table-responsive" style="min-height: 200px; max-height: 200px;">
                                    <table class="table table-data2">
                                        <thead class="text-sm-center">
                                            <tr>
                                                <th>
                                                    
                                                </th>
                                                <th width="50%">Product</th>
                                                <th width="10%">Price</th>
                                                <th width="10%">Qty</th>
                                                <th width="30%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm-center">
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
                                                    <i class="fa fa-times-circle text-danger"></i>
                                                </td>
                                                <td>{{$bill_item['product_name']}}</td>
                                                <td>{{$bill_item['product_price']}}</td>
                                                <td>{{$bill_item['product_quantity']}}</td>
                                                <td>{{$bill_item['product_subtotal']}}</td>
                                            </tr>
                                            @endforeach
                                          @endif
                                        </tbody>
                                    </table>
                                </div>
                                  <br>
                                <table width="100%" class="table">
                                  <tbody>
                                    <tr><td width="50%" style="background: #ecf0f1;">SubTotal: </td><td>
                                      <span style="float: left;">{{$pending_bill[0]['subtotal']}}</span><i style="float: right;"><b>{{$pending_bill[0]['total_item']}}</b> items</i>
                                    </td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Order Tax: </td><td>{{$pending_bill[0]['tax']}}</td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Discount: </td><td>{{$pending_bill[0]['discount']}}</td></tr>
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
                                <div class="card">
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
          




        //______________ B I L L -- G E N E R A T I O N ______________________________

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

                                }, 5000);


                                }

                            





                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
                });
        }
        //________________________________________________________________________








        </script>
                      


  
@endsection
