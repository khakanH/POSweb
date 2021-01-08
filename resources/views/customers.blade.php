@extends('layouts.app')
@section('content')

    
  <div class="tab-content">
 <h4 class=""> Customers</h4>
                   

                   <center>
            @if(session('success'))
                        <p class="text-success pulse animated">{{ session('success') }}</p>
                        {{ session()->forget('success') }}
                        @elseif(session('failed'))
                        <p class="text-danger pulse animated">{{ session('failed') }}</p>
                        {{ session()->forget('failed') }}
            @endif
            </center>
                    <div class="table-data__tool">
                                <div class="table-data__tool-left">

                                    <div class="form-group">
                                        
                                    <input class="form-control inp" type="text" name="search_text" id="cust_search_text" required="" style="" placeholder="Enter Customer Name" onfocusout="SearchCustomer(this.value)">
                                    
                                    </div>
                                    
                                </div>
                                <div class="table-data__tool-right">
                                    <button class="btn btn-default-pos" onclick="CreateNewCustomer()">
                                       &nbsp;&nbsp; <i class="zmdi zmdi-plus"></i> &nbsp;Add Customer&nbsp;&nbsp;</button>
                                   
                                </div>
                            </div>

                    <!-- DATA TABLE-->
                                  <div class="table-div">
                                <div class="table-pad-div">
                                    
                                    <table class="table table-1 table-sm dataTablesOptions">
                                        <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="custTBody">
                                            @foreach($customer as $key)
                                            <tr id="cust<?php echo $key['id']?>">
                                                <td scope="row"><b>{{$loop->iteration}}</b></td>
                                                <td>{{$key['customer_name']}}</td>
                                                <td>{{$key['customer_email']}}</td>
                                                <td>{{$key['customer_phone']}}</td>
                                                <td class="text-center"><a class="" href="javascript:void(0)" onclick='EditCustomer("<?php echo $key['id']?>","<?php echo $key['customer_name']?>","<?php echo $key['customer_email']?>","<?php echo $key['customer_phone']?>","<?php echo $key['customer_discount']?>","<?php echo $key['code']?>","<?php echo $key['payment_type']?>","<?php echo $key['credit_card_holder']?>","<?php echo $key['credit_card_number']?>")'><i class="fa fa-edit tx-20"></i></a>&nbsp;&nbsp;&nbsp;<a class="" onclick='DeleteCustomer("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash text-danger tx-20"></i></a>
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
          function CreateNewCustomer()
          {
            document.getElementById('cust_name').value = "";
            document.getElementById('cust_email').value = "";
            document.getElementById('cust_phone').value = "";
            document.getElementById('cust_discount').value = "";
            document.getElementById('cust_id').value = "";

            document.getElementById('cust_code').value = "";
            document.getElementById('cust_cc_holder').value = "";
            document.getElementById('cust_cc_number').value = "";

            document.getElementById("cust_cc").style.display = "none";
            
            document.getElementById('create_type').value = "2";

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

        function EditCustomer(id,name,email,phone,discount,code,payment,cc_holder,cc_number)
        {   
            document.getElementById('cust_code').value = code;
            document.getElementById('cust_name').value = name;
            document.getElementById('cust_email').value = email;
            document.getElementById('cust_phone').value = phone;
            document.getElementById('cust_discount').value = discount;
            document.getElementById('cust_id').value = id;

            $.ajax({
                            type: "GET",

                            url: "{{ env('APP_URL')}}get-payment-method/"+payment,
                            success: function(data) {
                                    $('#cust_payment_method').html(data);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Exception:' + errorThrown);
                            }
                          });

            if (payment == 2) 
            {
                document.getElementById("cust_cc").style.display = "flex";
                document.getElementById('cust_cc_holder').value = cc_holder;
                document.getElementById('cust_cc_number').value = cc_number;
            }
            else
            {
                document.getElementById("cust_cc").style.display = "none";
            }

            document.getElementById('create_type').value = "2";
            
            $('#CustomerModal').modal('show');
            $('#CustomerModalLabel').html('Edit Customer');

            document.getElementById('CustomerModal').style.backgroundColor="rgba(0,0,0,0.8)";
            document.getElementById('CustomerModalDialog').style.paddingTop="0px";
            document.getElementById('CustomerModalData').style.padding="5px 5px 0px 5px";
        }

    function SearchCustomer(value)
    {   
        var val = value.trim();

        if (!val) 
        {
            val = "0";
        }

             $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-customer-list-AJAX/"+val,
        success: function(data) {

            $('#custTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }

    function DeleteCustomer(id)
    {

        var r = confirm("Are You Sure?");
        if (r == false) 
        {
          return;
        }

        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}delete-customer/" + id,
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
                                    document.getElementById("cust"+id).style.display="none";
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



        </script>


  
@endsection
