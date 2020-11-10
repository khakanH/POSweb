@extends('layouts.app')
@section('content')

    
  
     <div class="page-content--bgf7">
            <section class="statistic statistic2">
                <div class="container">
                   

                    <div class="table-data__tool">
                                <div class="table-data__tool-left">

                                    <div class="form-group">
                                        
                                    <input class="au-input au-input--full" type="text" name="search_text" id="sale_search_text" required="" style="padding-right: 75px;" placeholder="Enter Sale Code" onfocusout="SearchSale(this.value)">
                                    <button class="btn btn-primary" style="float: right;position: absolute; margin: 0px 0px 0px -43px; height: 44px;"><i class="fa fa-search"></i></button>
                                    </div>
                                    
                                </div>
                            </div>

                    <!-- DATA TABLE-->
                                <div class="table-responsive m-b-40">
                                    <table class="table table-borderless table-data3">
                                        <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>Code</th>
                                                <th>Customer</th>
                                                <th>Tax</th>
                                                <th>Discount</th>
                                                <th>Total Bill</th>
                                                <th>Total Item</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="saleTBody">
                                            @foreach($sales as $key)
                                            <tr id="sale<?php echo $key['id']?>">
                                                <td scope="row"><b>{{$loop->iteration}}</b></td>
                                                <td>{{$key['bill_code']}}</td>
                                                <td>{{isset($key->customer_name['customer_name'])?$key->customer_name['customer_name']:"Walk In Customer"}}</td>
                                                <td>{{$key['tax']}}%</td>
                                                <td>{{$key['discount']}}%</td>
                                                <td>{{$key['total_bill']}}</td>
                                                <td>{{$key['total_item']}}</td>
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
          

       
    function SearchSale(value)
    {   
        var val = value.trim();

        if (!val) 
        {
            val = "0";
        }

             $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-sale-list-AJAX/"+val,
        success: function(data) {

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
    


        </script>


  
@endsection
