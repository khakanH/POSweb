@extends('layouts.app')
@section('content')

    
  
     <div class="page-content--bgf7">
            <section class="statistic statistic2">
                <div class="container">
                   

                    <div class="table-data__tool">
                                <div class="table-data__tool-left">

                                    <div class="form-group">
                                        
                                    <input class="au-input au-input--full" type="text" name="search_text" id="cust_search_text" required="" style="padding-right: 75px;" placeholder="Enter Customer Name" onfocusout="SearchCustomer(this.value)">
                                    <button class="btn btn-primary" style="float: right;position: absolute; margin: 0px 0px 0px -43px; height: 44px;"><i class="fa fa-search"></i></button>
                                    </div>
                                    
                                </div>
                                <div class="table-data__tool-right">
                                    <button class="au-btn au-btn-icon au-btn--green au-btn--small" onclick="CreateNewCustomer()">
                                        <i class="zmdi zmdi-plus"></i>Add Customer</button>
                                   
                                </div>
                            </div>

                    <!-- DATA TABLE-->
                                <div class="table-responsive m-b-40">
                                    <table class="table table-borderless table-data3">
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
                                                <td class="text-center"><a class="btn btn-primary" href="javascript:void(0)" onclick='EditCustomer("<?php echo $key['id']?>","<?php echo $key['customer_name']?>","<?php echo $key['customer_email']?>","<?php echo $key['customer_phone']?>","<?php echo $key['customer_discount']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" onclick='DeleteCustomer("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a>
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
          function CreateNewCustomer()
          {
            document.getElementById('cust_name').value = "";
            document.getElementById('cust_email').value = "";
            document.getElementById('cust_phone').value = "";
            document.getElementById('cust_discount').value = "";
            document.getElementById('cust_id').value = "";

            document.getElementById('create_type').value = "2";
            
            $('#CustomerModal').modal('show');
            $('#CustomerModalLabel').html('Add New Customer');

            document.getElementById('CustomerModal').style.backgroundColor="rgba(0,0,0,0.8)";
            document.getElementById('CustomerModalDialog').style.paddingTop="0px";
            document.getElementById('CustomerModalData').style.padding="5px 5px 0px 5px";
          }

        function EditCustomer(id,name,email,phone,discount)
        {
            document.getElementById('cust_name').value = name;
            document.getElementById('cust_email').value = email;
            document.getElementById('cust_phone').value = phone;
            document.getElementById('cust_discount').value = discount;
            document.getElementById('cust_id').value = id;

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
