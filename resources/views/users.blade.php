@extends('layouts.app')
@section('content')

    
  
     <div class="page-content--bgf7">

            <!-- STATISTIC-->
            <section class="statistic statistic2">
                <div class="container">

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
                                        
                                    <input class="au-input au-input--full" type="text" name="search_text" id="user_search_text" required="" style="padding-right: 75px;" placeholder="Enter User Name" onfocusout="SearchUser(this.value)">
                                    <button class="btn btn-primary" style="float: right;position: absolute; margin: 0px 0px 0px -43px; height: 44px;"><i class="fa fa-search"></i></button>
                                    </div>
                                    
                                </div>
                                <div class="table-data__tool-right">
                                    <button class="au-btn au-btn-icon au-btn--green au-btn--small" onclick="AddUser()">
                                        <i class="zmdi zmdi-plus"></i>Add User</button>
                                   
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
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="userTBody">
                                            @foreach($users as $key)
                                            <tr id="user<?php echo $key['id']?>">
                                                <td scope="row"><b>{{$loop->iteration}}</b></td>
                                                <td>{{$key['username']}}</td>
                                                <td>{{$key['email']}}</td>
                                                <td>{{$key->member_type_name['name']}}</td>
                                                <td>
                                                  @if($key['is_verified'] == 0)
                                                  <span class="badge badge-danger">Not Verified!</span>
                                                  @else
                                                  <span class="badge badge-success">Verified!</span>
                                                  @endif
                                                </td>
                                                <td>
                                                  
                                                  <a data-toggle="tooltip" title="Block/Unblock User" id="status-btn-color<?php echo $key['id']?>" class="<?php if ($key['is_blocked'] == 0): ?>btn btn-danger<?php else: ?>btn btn-success<?php endif ?>" href="javascript:void(0)" onclick='BlockUnblockUser("<?php echo $key['id']?>")'><i id="status-btn-icon<?php echo $key['id']?>"  class="
                                                    <?php if ($key['is_blocked'] == 0): ?>
                                                    fa fa-lock tx-15
                                                    <?php else: ?>
                                                    fa fa-unlock tx-15
                                                    <?php endif ?>

                                                    "></i></a>
                                                
                                                &nbsp;&nbsp;&nbsp;<!-- <a class="btn btn-danger" onclick='DeleteUser("<?php // echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a> --></td>
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
            

    function AddUser()
    {
        document.getElementById('user_name').value = "";
        document.getElementById('user_email').value = "";
        document.getElementById('user_password').value = "";
        $('#UserModal').modal('show');
        $('#UserModalLabel').html('Add New User');

        document.getElementById('UserModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('UserModalDialog').style.paddingTop="0px";
        document.getElementById('UserModalData').style.padding="20px 20px 0px 20px";

        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-member-type",
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

                                  if (document.getElementById("status-btn-color"+id).className == "btn btn-danger") 
                                  {
                                    document.getElementById("status-btn-color"+id).className = "btn btn-success";
                                    document.getElementById("status-btn-icon"+id).className = "fa fa-unlock tx-15";
                                    
                                  }
                                  else
                                  {
                                    document.getElementById("status-btn-color"+id).className = "btn btn-danger";
                                    document.getElementById("status-btn-icon"+id).className = "fa fa-lock tx-15";

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
  
</script>
                        
                      
                       



  
@endsection
