@extends('layouts.admin_app')
@section('content')
       

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">

        <div class="table-data__tool">
            <div class="table-data__tool-left">

                <div class="form-group">
                                        
                    <input class="au-input au-input--full" type="text" name="search_text" id="payment_search_text" required="" style="padding-right: 105px;" placeholder="Enter Payment Name" onfocusout="SearchPayment(this.value)">
                    <button class="btn btn-primary" style="float: right;position: absolute; margin: 0px 0px 0px -43px; height: 44px;"><i class="fa fa-search"></i></button>
                </div>
                                    
            </div>
            
            <div class="table-data__tool-right">
                <button class="au-btn au-btn-icon au-btn--green au-btn--small" onclick="AddPayment()" type="button"><i class="zmdi zmdi-plus"></i>Add Payment Method</button>
                                   
            </div>
        </div>



        <div class="user-data">
                  
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <!-- <td width="2%">
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td> -->
                                                    <th width="35%">Name</th>
                                                    <th class="tx-center" width="20%">Status</th>
                                                    <th width="20%" class="tx-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 14px;" id="paymentTBody">
                                                @foreach($payment_method as $key)
                                                <tr id="payment{{$key['id']}}">
                                                    <!-- <td>
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td> -->
                                                    <td>
                                                        {{$key['name']}}
                                                    </td>
                                                   
                                                    <td class="tx-center">
                                                         <label class="switch switch-3d switch-primary mr-3">
                                                          <input id="visibility_value<?php echo $key['id']?>" onclick='PaymentStatus("<?php echo $key['id']?>","<?php echo $key['is_show']; ?>")' type="checkbox" class="switch-input"  <?php if ($key['is_show'] ==1): ?>
                                                              checked
                                                          <?php endif ?> value="<?php echo $key['is_show'] ?>">
                                                          <span class="switch-label"></span>
                                                          <span class="switch-handle"></span>
                                                        </label>
                                                    </td>
                                                    <td class="tx-center">
                                                         <a class="btn btn-primary" href="javascript:void(0)" onclick='EditPayment("<?php echo $key['id']?>","<?php echo $key['name']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" onclick='DeletePayment("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a>
                                                    </td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

        </div>


<br>


    
    </div>
  </div>
</div>

<script type="text/javascript">
    
    function AddPayment()
    {
        document.getElementById('payment_name').value = "";
        document.getElementById('payment_id').value = "";
        $('#PaymentModal').modal('show');
        $('#PaymentModalLabel').html('Add Payment Method');

        document.getElementById('PaymentModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('PaymentModalDialog').style.paddingTop="0px";
        document.getElementById('PaymentModalData').style.padding="20px 20px 0px 20px";

    }

    function EditPayment(id,name)
    {
        document.getElementById('payment_name').value = name;
        document.getElementById('payment_id').value = id;


        $('#PaymentModal').modal('show');
        $('#PaymentModalLabel').html('Edit Payment Method');

        document.getElementById('PaymentModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('PaymentModalDialog').style.paddingTop="0px";
        document.getElementById('PaymentModalData').style.padding="20px 20px 0px 20px";
    }

    function SearchPayment(value)
    {   
        var val = value.trim();

        if (!val) 
        {
            val = "0";
        }

        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/get-payment-list-AJAX/"+val,
        success: function(data) {

            $('#paymentTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }

    function DeletePayment(id)
    {

        var r = confirm("Are You Sure?");
        if (r == false) 
        {
          return;
        }

        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}admin/delete-payment-method/" + id,
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
                                    document.getElementById("payment"+id).style.display="none";
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



    function PaymentStatus(id,status)
    {   

        status = document.getElementById("visibility_value"+id).value;

        if (status == "0") 
        {
            document.getElementById("visibility_value"+id).value = "1";
        }
        else
        {
            document.getElementById("visibility_value"+id).value = "0";
        }
        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/change-payment-availability/"+id +"/"+ status,
        beforeSend: function(){
                        },
        success: function(data) {
            

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
                                
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }
</script>


@endsection
