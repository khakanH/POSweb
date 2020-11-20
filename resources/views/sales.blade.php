@extends('layouts.app')
@section('content')

    
  
     <div class="page-content--bgf7">
            <section class="statistic statistic2">
                <div class="container">
                   
       
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
                                        <div class="col-lg-4 m-b-10"> <input class="au-input au-input--full" type="text" name="search_text" id="sale_search_text" required="" style="padding-right: 75px;" placeholder="Enter Sale Code" onfocusout="SearchSale(this.value)">
                                    <button class="btn btn-primary" style="float: right;position: absolute; margin: 0px 0px 0px -43px; height: 44px;"><i class="fa fa-search"></i></button></div>
                                        <div class="col-lg-3 m-b-10"><input style="height: 44px;" type="date" name="from_date" max="{{date('Y-m-d')}}" onfocus="ToNormal()" id="from_date" class="form-control"> </div>
                                        <div class="col-lg-3 m-b-10"><input style="height: 44px;" type="date" name="to_date" max="{{date('Y-m-d')}}" onfocus="ToNormal()" id="to_date" class="form-control"> </div>
                                        <div class="col-lg-1 text-center m-b-10">
                                            <button class="btn btn-primary" onclick="FilterSale()" style="width: 100%; height: 44px;">Filter</button>
                                        </div>
                                        <div class="col-lg-1 text-center m-b-10">
                                            <a style="width: 100%;" href="{{route('export-sale-csv')}}" target="_blank"><button style="width: 100%; height: 44px;" class="btn btn-success" >Export csv</button></a>
                                        </div>
                                                                           
                                    </div>


 

                    <br>


                    <!-- DATA TABLE-->
                                <div class="table-responsive m-b-40">
                                    <table class="table table-borderless table-data3 text-center">
                                        <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>Code</th>
                                                <th>Customer</th>
                                                <th>Tax</th>
                                                <th>Discount</th>
                                                <th>Total Bill</th>
                                                <th>Total Item</th>
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
                                                <td>{{date("d-M-Y",strtotime($key['created_at']))}}</td>
                                                <td class="text-center"><a class="btn btn-primary" href="javascript:void(0)" onclick='ViewSaleItem("<?php echo $key['id']?>","<?php echo $key['bill_code']?>")'><i class="fa fa-eye tx-15"></i></a>&nbsp;&nbsp;&nbsp;<!-- <a class="btn btn-danger" onclick='DeleteSale("<?php // echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a> -->
                                            </td>
                                            </tr>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <!-- END DATA TABLE-->

                </div>
            </section>

           

           

        </div>

        <script type="text/javascript">
          

       
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



















    function ToNormal()
    {
          document.getElementById("from_date").style.border = "solid lightgray 1px";
          document.getElementById("to_date").style.border = "solid lightgray 1px";

    }



        </script>


  
@endsection
