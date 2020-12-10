@extends('layouts.admin_app')
@section('content')
       

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
                        

         <div class="table-data__tool">
            <div class="table-data__tool-left">

                <div class="form-group">
                                        
                    <input class="au-input au-input--full" type="text" name="search_text" id="member_search_text" required="" style="padding-right: 105px;" placeholder="Enter Member Name" onfocusout="SearchMember(this.value)">
                    <button class="btn btn-primary" style="float: right;position: absolute; margin: 0px 0px 0px -43px; height: 44px;"><i class="fa fa-search"></i></button>
                </div>
                                    
            </div>
            
            <div class="table-data__tool-right">
               
                                   
            </div>
        </div>

        <div class="user-data">
                  
                                        <table class="table text-center">
                                            <thead>
                                                <tr>
                                                    <!-- <td width="2%">
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td> -->
                                                    <th width="5%">Name</th>
                                                    <th width="15%">Email</th>
                                                    <th width="10%">Account</th>
                                                    <th width="10%">Profile</th>
                                                    <th width="20%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 14px;" id="memberTBody">
                                                @foreach($members as $key)
                                                <tr id="row{{$key['id']}}">
                                                    <!-- <td>
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td> -->
                                                    <td>
                                                        {{$key['username']}}
                                                    </td>
                                                    <td style="word-break: break-all;">
                                                        {{$key['email']}}
                                                        
                                                    </td>
                                                    <td>
                                                        @if($key['is_verified'] == 0)
                                                        <span class="role admin">Not Verified</span>
                                                        @else
                                                        <span class="role member">Verified</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($key['is_set_profile'] == 0)
                                                        <span class="role admin">Not Updated</span>
                                                        @else
                                                        <span class="role member">Updated</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                         <a data-toggle="tooltip" title="Block/Unblock Member" id="status-btn-color<?php echo $key['id']?>" class="<?php if ($key['is_blocked'] == 0): ?>btn btn-danger<?php else: ?>btn btn-success<?php endif ?>" href="javascript:void(0)" onclick='BlockUnblockMember("<?php echo $key['id']?>")'><i id="status-btn-icon<?php echo $key['id']?>"  class="
                                                    <?php if ($key['is_blocked'] == 0): ?>
                                                    fa fa-lock tx-15
                                                    <?php else: ?>
                                                    fa fa-unlock tx-15
                                                    <?php endif ?>

                                                    "></i></a>

                                                    <a data-toggle="tooltip" title="View Member Details" class="btn btn-primary" href="javascript:void(0)" onclick='ViewMember("<?php echo $key['id']?>")'><i class="fa fa-eye tx-15"></i></a>
                                                    <a data-toggle="tooltip" title="Send Notification" class="btn btn-info" href="javascript:void(0)" onclick='SendMemberNotification("<?php echo $key['id']?>")'><i class="fa fa-comments tx-15"></i></a>
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
    function ViewMember(id)
    {
        $('#MemberDetailModal').modal('show');
        $('#back-arrow-span').html("");
        $('#MemberDetailModalData').html("");

        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/get-member-details/"+id,
        beforeSend: function(){
                             document.getElementById("MemberDetailModalLoading").style.display = "block";
                          },
        success: function(data) {
                             document.getElementById("MemberDetailModalLoading").style.display = "none";


            $('#MemberDetailModalData').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
        });   
    }



    function CompanyDetails(id,member_id)
    {   
        $('#MemberDetailModalData').html("");
       
        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/get-company-details/"+id,
        beforeSend: function(){
                             document.getElementById("MemberDetailModalLoading").style.display = "block";
                          },
        success: function(data) {
            
                             document.getElementById("MemberDetailModalLoading").style.display = "none";
 $('#back-arrow-span').html('<i id="back-arrow" onclick="ViewMember('+member_id+')" class="fa fa-arrow-left tx-16" style="padding: 10px; cursor: pointer;"></i>');

            $('#MemberDetailModalData').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
        });   
    }

    function BlockUnblockMember(id)
    {


        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}admin/block-unblock-member/" + id,
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

    function SearchMember(value)
    {   
        var val = value.trim();

        if (!val) 
        {
            val = "0";
        }

        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/get-member-list-AJAX/"+val,
        success: function(data) {

            $('#memberTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }


    function SendMemberNotification(id)
    {
        document.getElementById('notifi_title').value = "";
        document.getElementById('notifi_description').value = "";
        document.getElementById('receiver_id').value = id;
        document.getElementById('notifi_type').value = "1";


        $('#NotificationModal').modal('show');
        $('#NotificationModalLabel').html('Send Notification To Member');

        document.getElementById('NotificationModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('NotificationModalDialog').style.paddingTop="0px";
        document.getElementById('NotificationModalData').style.padding="20px 20px 0px 20px";
    }

</script>


@endsection
