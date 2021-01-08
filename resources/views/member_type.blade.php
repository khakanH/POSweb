@extends('layouts.app')
@section('content')
       
<div class="tab-content">
 <h4 class=""> Users Types</h4>
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
                                        
                    <input class="form-control inp" type="text" name="search_text" id="member_type_search_text" required="" style="" placeholder="Enter User Type Name" onfocusout="SearchMemberType(this.value)">
                    
                </div>
                                    
            </div>
            
            <div class="table-data__tool-right">
                <button class="btn btn-default-pos" onclick="AddMemberType()" type="button">&nbsp;&nbsp;<i class="zmdi zmdi-plus"></i> &nbsp;Add User Type&nbsp;&nbsp;</button>
                                   
            </div>
        </div>



       <div class="table-div">
                                <div class="table-pad-div">
                  
                                        <table class="table table-1 table-sm dataTablesOptions">
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
                                            <tbody style="font-size: 14px;" id="member_typeTBody">
                                                @foreach($member_type as $key)
                                                <tr id="member_type{{$key['id']}}">
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
                                                         <label  class="switch switch-3d switch-primary mr-3">
                                                          <input  id="visibility_value<?php echo $key['id']?>" onclick='MemberTypeStatus("<?php echo $key['id']?>","<?php echo $key['is_show']; ?>")' type="checkbox" class="switch-input"  <?php if ($key['is_show'] ==1): ?>
                                                              checked
                                                          <?php endif ?> value="<?php echo $key['is_show'] ?>">
                                                          <span class="switch-label"></span>
                                                          <span class="switch-handle"></span>
                                                        </label>
                                                    </td>
                                                    <td class="tx-center">
                                                         <a class="" href="javascript:void(0)" onclick='EditEMemberType("<?php echo $key['id']?>","<?php echo $key['name']?>")'><i class="fa fa-edit tx-20 text-primary"></i></a>&nbsp;&nbsp;&nbsp;<a class=" onclick='DeleteMemberType("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-20 text-danger"></i></a>
                                                    </td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

        </div>
</div>

<br>


    
</div>

<script type="text/javascript">
    
    function AddMemberType()
    {
        document.getElementById('member_type_name').value = "";
        document.getElementById('member_type_id').value = "";
        $('#MemberTypeModal').modal('show');
        $('#MemberTypeModalLabel').html('Add User Type');

        document.getElementById('MemberTypeModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('MemberTypeModalDialog').style.paddingTop="0px";
        document.getElementById('MemberTypeModalData').style.padding="20px 20px 0px 20px";

    }

    function EditEMemberType(id,name)
    {
        document.getElementById('member_type_name').value = name;
        document.getElementById('member_type_id').value = id;


        $('#MemberTypeModal').modal('show');
        $('#MemberTypeModalLabel').html('Edit User Type');

        document.getElementById('MemberTypeModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('MemberTypeModalDialog').style.paddingTop="0px";
        document.getElementById('MemberTypeModalData').style.padding="20px 20px 0px 20px";
    }

    function SearchMemberType(value)
    {   
        var val = value.trim();

        if (!val) 
        {
            val = "0";
        }

        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-member-type-list-AJAX/"+val,
        success: function(data) {

            $('#member_typeTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }

    function DeleteMemberType(id)
    {

        var r = confirm("Are You Sure?");
        if (r == false) 
        {
          return;
        }

        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}delete-member-type/" + id,
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
                                    document.getElementById("member_type"+id).style.display="none";
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



    function MemberTypeStatus(id,status)
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
        url: "{{ env('APP_URL')}}change-member-type-availability/"+id +"/"+ status,
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
