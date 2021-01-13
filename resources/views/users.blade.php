@extends('layouts.app')
@section('content')

    
  
     <div class="tab-content">
 <h4 class=""> Users</h4>
            <!-- STATISTIC-->
            
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
                                        
                                    <input class="form-control inp" type="text" name="search_text" id="user_search_text" required="" style="" placeholder="Enter User Name" onfocusout="SearchUser(this.value)">
                                  
                                    </div>
                                    
                                </div>
                                <div class="table-data__tool-right">
                                    <a href="{{route('member-role-list')}}" target="_blank"><button class="btn btn-default-pos">&nbsp;&nbsp;<i class="fa fa-gears"></i> &nbsp;Manage User Roles&nbsp;&nbsp;</button></a>&nbsp;&nbsp;
                                    <a href="{{route('member-type-list')}}" target="_blank"><button class="btn btn-default-pos">&nbsp;&nbsp;<i class="fa fa-bars"></i> &nbsp;Manage User Type&nbsp;&nbsp;</button></a>&nbsp;&nbsp;
                                    <button class="btn btn-default-pos" onclick="AddUser()">
                                        &nbsp;&nbsp;<i class="zmdi zmdi-plus"></i>&nbsp; Add User&nbsp;&nbsp;</button>
                                   
                                </div>
                            </div>



                                <!-- DATA TABLE-->
                                <div class="table-div">
                                <div class="table-pad-div">
                                    
                                    <table class="table table-1 table-sm dataTablesOptions">
                                        <thead>
                                            <tr>
                                                <th width="5%">S No.</th>
                                                <th width="5%">Name</th>
                                                <th width="20%">Email</th>
                                                <th width="10%">Type</th>
                                                <th width="5%">Salary</th>
                                                <th width="15%">Salary <small>(This Month)</small></th>
                                                <th width="10%">Status</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="userTBody">
                                            @foreach($users as $key)
                                            <tr id="user<?php echo $key['id']?>">
                                                <td scope="row"><b>{{$loop->iteration}}</b></td>
                                                <td>{{$key['username']}}</td>
                                                <td>{{$key['email']}}</td>
                                                <td >{{isset($key->member_type_name['name'])?$key->member_type_name['name']:"Super Admin"}}</td>
                                                <td><a data-toggle="tooltip" title="View Salary Details" href="javascript:void(0)" onclick='ViewSalaryDetails("<?php echo $key['id']?>","<?php echo $key['username']?>")'>{{number_format($key['salary'],0)}}</a></td>
                                                
                                                <td>
                                                    @if($key['is_salary_paid'] == 0)
                                                        <i class="fa fa-times-circle text-danger"> Unpaid</i>
                                                    @else
                                                        <i class="fa fa-check-circle text-success"> Paid</i>

                                                    @endif
                                                </td>
                                                
                                                <td>
                                                  @if($key['is_verified'] == 0)
                                                  <span class="text-danger">Not Verified!</span>
                                                  @else
                                                  <span class="text-success">Verified!</span>
                                                  @endif
                                                </td>
                                                <td>
                                                  
                                                  <a data-toggle="tooltip" title="Block/Unblock User" id="status-btn-color<?php echo $key['id']?>"  href="javascript:void(0)" onclick='BlockUnblockUser("<?php echo $key['id']?>")'><i id="status-btn-icon<?php echo $key['id']?>"  class="<?php if ($key['is_blocked'] == 0): ?>fa fa-lock tx-20 text-danger<?php else: ?>fa fa-unlock tx-20 text-success<?php endif ?>"></i></a>
                                                
                                                &nbsp;&nbsp;&nbsp;
                                                <a data-toggle="tooltip" title="Edit User" href="javascript:void(0)" onclick='EditUser("<?php echo $key['id']?>","<?php echo $key['username']?>","<?php echo $key['member_type']?>","<?php echo $key['salary']?>")'><i class="fa fa-edit tx-20 text-primary"></i></a>
                                                 &nbsp;&nbsp;&nbsp;
                                                <a data-toggle="tooltip" title="Pay Salary" href="javascript:void(0)" onclick='PaySalary("<?php echo $key['id']?>","<?php echo $key['salary']?>","<?php echo $key['username']?>")'><i class="fa fa-money tx-20 text-success"></i></a>

                                                <!-- <a class="btn btn-danger" onclick='DeleteUser("<?php // echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a> --></td>
                                            </tr>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                                <!-- END DATA TABLE-->


                     
           

        </div>




<script type="text/javascript">
            

    function AddUser()
    {
        document.getElementById('user_name').value = "";
         document.getElementById('user-email-div').style.display = "block";
        document.getElementById('user-pass-div').style.display = "block";
        document.getElementById('user_email').value = "";
        document.getElementById('user_password').value = "";
        document.getElementById('user_salary').value = "0";
        $('#UserModal').modal('show');
        $('#UserModalLabel').html('Add New User');

        document.getElementById('UserModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('UserModalDialog').style.paddingTop="0px";
        document.getElementById('UserModalData').style.padding="20px 20px 0px 20px";

        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-member-type/"+"0",
        success: function(data) {

            $('#user_type').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
      });
        document.getElementById('user_type').value = "";

    }

    

    function EditUser(id,name,type,salary)
    {
        document.getElementById('user_name').value = name;
        document.getElementById('user_id').value = id;
        document.getElementById('user_salary').value = salary;
        document.getElementById('user-email-div').style.display = "none";
        document.getElementById('user-pass-div').style.display = "none";
        $('#UserModal').modal('show');
        $('#UserModalLabel').html('Edit User');

        document.getElementById('UserModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('UserModalDialog').style.paddingTop="0px";
        document.getElementById('UserModalData').style.padding="20px 20px 0px 20px";

        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-member-type/"+type,
        success: function(data) {

            $('#user_type').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
      });

    }

    function SearchUser(value)
    {   
        var val = value.trim();

        if (!val) 
        {
            val = "0";
        }

      $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-user-list-AJAX/"+val,
        success: function(data) {

            $('#userTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
      });
    }

    // function DeleteUser(id)
    // {

    //     var r = confirm("Are You Sure?");
    //     if (r == false) 
    //     {
    //       return;
    //     }

    //     $.ajax({
    //         type: "GET",
    //         url: "{{ env('APP_URL')}}delete-user/" + id,
    //         success: function(data) {
                

    //                         get_status = data['status'];
    //             get_msg    = data['msg'];

    //                             if (get_status == "0") 
    //                             {

    //                              document.getElementById('toast').style.visibility = "visible";
    //                                 document.getElementById('toast').className = "alert alert-danger alert-rounded fadeIn animated";
    //                                 document.getElementById('toastMsg').innerHTML = get_msg;


    //                                  setTimeout(function() {
    //                                 document.getElementById('toast').className = "alert alert-danger alert-rounded fadeOut animated";

    //                             }, 5000);

    //                             }
    //                             else
    //                             {
    //                                 document.getElementById("user"+id).style.display="none";
    //                                 document.getElementById('toast').style.visibility = "visible";
    //                                 document.getElementById('toast').className = "alert alert-success alert-rounded fadeIn animated";
    //                                 document.getElementById('toastMsg').innerHTML =  get_msg;


    //                                  setTimeout(function() {
    //                                 document.getElementById('toast').className = "alert alert-success alert-rounded fadeOut animated";

    //                             }, 5000);


    //                             }

    //         },
    //         error: function(jqXHR, textStatus, errorThrown) {
    //             alert('Exception:' + errorThrown);
    //         }
    //     });


    // }


    function BlockUnblockUser(id)
    {


        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}block-unblock-user/" + id,
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

                                  if (document.getElementById("status-btn-icon"+id).className == "fa fa-lock tx-20 text-danger") 
                                  {
                                    document.getElementById("status-btn-icon"+id).className = "fa fa-unlock tx-20 text-success";
                                  }
                                  else
                                  {
                                    document.getElementById("status-btn-icon"+id).className = "fa fa-lock tx-20 text-danger";
                                  }

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
    


    function PaySalary(id,salary,name)
    {
        document.getElementById('salary_date').value = "{{date('Y-m-d')}}";
        document.getElementById('salary_amount').value = salary;
        document.getElementById('salary_deduction').value = "0";
        document.getElementById('salary_deduction_reason').value = "";
        document.getElementById('salary_bonus').value = "0";
        document.getElementById('salary_bonus_reason').value = "";
        document.getElementById('total_salary').value = salary;
        
        document.getElementById('user_salary_id').value = id;


        $('#UserSalaryModal').modal('show');
        $('#UserSalaryModalLabel').html('User Salary ('+name+')');

        document.getElementById('UserSalaryModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('UserSalaryModalDialog').style.paddingTop="0px";
        document.getElementById('UserSalaryModalData').style.padding="20px 20px 0px 20px";
    }

    function CalculateTotalSalary()
    {   

        var deduction = parseFloat(document.getElementById("salary_deduction").value);
        var bonus     = parseFloat(document.getElementById("salary_bonus").value);
        var salary    = parseFloat(document.getElementById("salary_amount").value); 

        if (!deduction) 
        {
            deduction =0;
            document.getElementById("salary_deduction").value = 0;
        }
        if (!bonus) 
        {
            bonus =0;
            document.getElementById("salary_bonus").value = 0;
        }

        document.getElementById("total_salary").value = salary + bonus - deduction;

    }


    function ViewSalaryDetails(id,name)
    {
        $('#UserSalaryDetailModal').modal('show');

        $('#UserSalaryDetailModalLabel').html('Salary Details ('+name+')');

        document.getElementById('UserSalaryDetailModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('UserSalaryDetailModalDialog').style.paddingTop="0px";
        document.getElementById('UserSalaryDetailModalData').style.padding="20px 20px 0px 20px";

        
           $.ajax({
                type: "GET",
                url: "{{ env('APP_URL')}}get-user-salary-detail/"+id,
                 beforeSend: function(){
                          
                            $('#UserSalaryDetailModalData').html('<center><img src="<?php echo env('IMG_URL') ?>loading.gif" width="50" height="50"></center>');


                        },
                success: function(data) {

                    $('#UserSalaryDetailModalData').html(data);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Exception:' + errorThrown);
                }
              });


    }
</script>
                        
                      
                       



  
@endsection
