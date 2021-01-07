@extends('layouts.app')
@section('content')

    
  <div class="tab-content">
 <h5 class=""> Users</h5>
       
            <center>
            @if(session('success'))
            <p class="text-success pulse animated">{{ session('success') }}</p>
            {{ session()->forget('success') }}
            <br>
          @elseif(session('failed'))
            <p class="text-danger pulse animated">{{ session('failed') }}</p>
            {{ session()->forget('failed') }}
            <br>
          @endif
          </center>
                                    
                                    <div class="row" style="margin: 0px 0px 0px -2px;">
                                        <div class="col-lg-3 m-b-10"> <input class="form-control inp" type="text" name="search_text" id="sale_search_text" required="" style="" placeholder="Enter Sale Code" onfocusout="SearchSale(this.value)">
                                    </div>
                                        <div class="col-lg-2 m-b-10"></div>
                                        <div class="col-lg-3 m-b-10"></div>
                                        <div class="col-lg-2 text-center m-b-10">
                                            <a style="width: 100%;" href="{{route('export-sale-csv')}}" target="_blank"><button style="width: 100%; height: 44px; font-size: 15px;" class="btn btn-default-pos" >Export csv</button></a>
                                        </div>
                                        <div class="col-lg-2 text-center m-b-10">
                                           
                                           <div class="btn-group">
                                                <button type="button" class="btn btn-default-pos dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                   <i class="fa fa-calendar"></i> &nbsp;&nbsp;Today &nbsp;&nbsp;&nbsp;
                                                </button>
                                                <div class="dropdown-menu" style="margin-right: 10px;">
                                                 <div class="pos-right-search">
                                                     
                                                    <label>From</label>
                                                    <input type="date" name="from_date" max="{{date('Y-m-d')}}" onclick="ToNormal()" id="from_date" class="form-control" name="">
                                                    <label>To</label>
                                                    <input type="date" name="to_date" max="{{date('Y-m-d')}}" onclick="ToNormal()" id="to_date" class="form-control" name="">
                                                    <br>
                                                    <center>
                                                    <input onclick="FilterSale()" readonly="" value="Apply" class="btn pos-btn " style="width: 80%; background-color: rgba(57, 63, 151, 1); ">
                                                    </center>
                                                 </div>
                                                </div>
                                            </div>
                                        </div>
                                                                           
                                    </div>


 
                    <!-- DATA TABLE-->
                             <div class="table-div">
                                <div class="table-pad-div">
                                    <table class="table table-1 table-sm tx-12">
                                        <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>Code</th>
                                                <th>Customer</th>
                                                <th>Tax</th>
                                                <th>Discount</th>
                                                <th>Total Bill</th>
                                                <th>Total Item</th>
                                                <th>Payment Type</th>
                                                <th>Is Paid</th>
                                                <th>Date</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="saleTBody">
                                            @foreach($sales as $key)
                                            <tr id="sale<?php echo $key['id']?>">
                                                <td scope="row"><b>{{$loop->iteration}}</b></td>
                                                <td>{{$key['bill_code']}}</td>
                                                <td>{{isset($key->customer_name['customer_name'])?$key->customer_name['customer_name']:"Walk In Customer"}}</td>
                                                <td>{{number_format($key['tax'],1)}}%</td>
                                                <td>{{number_format($key['discount'],1)}}%</td>
                                                <td>{{$key['total_bill']}}</td>
                                                <td>{{$key['total_item']}}</td>
                                                <td>{{$key->payment_method_name->name}}</td>
                                                <td>
                                                    <?php if($key['is_paid'] == 0): ?>
                                                        <p class="text-danger">No</p>
                                                    <?php else: ?>
                                                        <p class="text-success">Yes</p>
                                                    <?php endif; ?>

                                                </td>
                                                <td>{{date("d-M-Y",strtotime($key['created_at']))}}</td>
                                                <td class="text-center"><a class="" href="javascript:void(0)" onclick='ViewSaleItem("<?php echo $key['id']?>","<?php echo $key['bill_code']?>")'><i class="fa fa-eye tx-20"></i></a>&nbsp;&nbsp;&nbsp;<!-- <a class="btn btn-danger" onclick='DeleteSale("<?php // echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a> -->
                                            </td>
                                            </tr>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                <!-- END DATA TABLE-->


           

        </div>

        <script type="text/javascript">
            function ToNormal()
    {
          document.getElementById("from_date").style.border = "solid lightgray 1px";
          document.getElementById("to_date").style.border = "solid lightgray 1px";

    }

       
    function SearchSale(value_)
    {   
        var val = value_.trim();
        var start_date = document.getElementById("from_date").value;
        var end_date = document.getElementById("to_date").value;

        
             $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}get-sale-list-AJAX",
         data: { 
                                'search'      : val,
                                'start_date'  : start_date,
                                'end_date'    : end_date,
                                "_token": $('meta[name="csrf-token"]').attr('content') },
         beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
        success: function(data) {
                            $('#LoadingModal').modal('hide');

            $('#saleTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
        });
    }

    function DeleteSale(id)
    {

        var r = confirm("Are You Sure?");
        if (r == false) 
        {
          return;
        }

        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}delete-sale/" + id,
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
                                    document.getElementById("sale"+id).style.display="none";
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

    function ViewSaleItem(id,code)
    {   
        $('#SaleItemModal').modal('show');
        $('#SaleItemModalData').html("");
        $('#SaleItemModalLabel').html("Sale Items ("+code+")");



        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-bill-sale-items/"+id,
        success: function(data) {


            $('#SaleItemModalData').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
        });   
    }
    

    function FilterSale()
    {
        var start_date = document.getElementById("from_date").value;
        var end_date = document.getElementById("to_date").value;

        if (start_date == "") 
        {
          document.getElementById("from_date").style.border = "solid red 2px";
          return;
        }

        if (end_date == "") 
        {
          document.getElementById("to_date").style.border = "solid red 2px";
          return;
        }

        var search = document.getElementById("sale_search_text").value;

        $.ajax({
                        type: "POST",
                        url: "{{ env('APP_URL')}}get-sale-list-AJAX",
                        beforeSend: function(){
                          
                            $('#LoadingModal').modal('show');


                        },
                        data: { 
                                'search'        : search,
                                'start_date'  : start_date,
                                'end_date'    : end_date,
                                "_token": $('meta[name="csrf-token"]').attr('content') },
                        success: function(data) {
                            $('#LoadingModal').modal('hide');
                            
                            document.getElementById("saleTBody").innerHTML=data;

                            
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('Exception:' + errorThrown);
                        }
                    });

    }
















 function MarkBillPaid(id)
    {   
         $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}mark-bill-paid/"+id,
        success: function(data) {

             var search = document.getElementById("sale_search_text").value.trim();
            SearchSale(search);
            $('#SaleItemModal').modal('hide');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
        });   
    }


  



        </script>


  
@endsection
