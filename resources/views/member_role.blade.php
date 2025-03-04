@extends('layouts.app')
@section('content')
       

 <div class="tab-content">
 <h4 class=""> Users Roles</h4>


                   <center>
            @if(session('success'))
                        <p class="text-success pulse animated">{{ session('success') }}</p>
                        {{ session()->forget('success') }}
                        @elseif(session('failed'))
                        <p class="text-danger pulse animated">{{ session('failed') }}</p>
                        {{ session()->forget('failed') }}
            @endif
            </center>

       <div class="row">
            <div class="col-lg-6">

                <div class="form-group">
                   <label>Select Member Type</label>
                      <select class="form-control inp" id="member_type_list" onchange='GetMemberRoles(this.value)'>
                          @foreach($member_type as $key)
                          <option value="{{$key['id']}}">{{$key['name']}}</option>
                          @endforeach
                      </select>
                </div>
                                    
            </div>
            
        </div>
        <form method="post" name="memberRolesForm" id="memberRolesForm">
        @csrf

       <div class="table-div">
                                <div class="table-pad-div">
                  

                                        <table class="table table-striped">
                                            <thead>
                                                <tr style="background-color: #3a3e97; color: #ffffff;font-size: 14px; font-weight: 600;">
                                                    <!-- <td width="2%">
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td> -->
                                                    <th width="35%">Modules</th>
                                                    <th width="35%">Sub-Modules</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 14px;" id="member_roleTBody">
                                              <input type="hidden" name="member_type_id" id="member_type_id" value="{{$member_type[0]['id']}}">
                                            @for($i = 0; $i < count($all_modules); $i++)
                                                <tr id="member_role{{$all_modules[$i]['id']}}">
                                                    <td><li><input onclick='ToggleAllSubModule("{{$all_modules[$i]['id']}}")' id="main_module-cb{{$all_modules[$i]['id']}}"  type="checkbox" name="main_module-cb[]" value="{{$all_modules[$i]['id']}}" <?php if (in_array($all_modules[$i]['id'], $member_role)): ?>
                                                        checked=""
                                                        <?php else: ?>
                                                    <?php endif ?>>&nbsp;&nbsp;&nbsp;{{$all_modules[$i]['name']}}</li></td>
                                                    <td> @for($j = 0 ; $j < count($all_sub_modules[$i]); $j++)  
                                                           <li><input value="{{$all_sub_modules[$i][$j]['id']}}" class="sub_module-cb{{$all_modules[$i]['id']}}" type="checkbox" name="main_module-cb[]" <?php if (in_array($all_sub_modules[$i][$j]['id'], $member_role)): ?>
                                                        checked=""
                                                    <?php endif ?>>&nbsp;&nbsp;&nbsp;{{$all_sub_modules[$i][$j]['name']}}</li>
                                                       @endfor</td>
                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>

        </div>
    </div>
                                        <br>
                                        <button class="btn btn-default-pos" style="float: right; width: 20%;" type="submit">Save</button>
                                        <br>
                                        <br>

        </form>


<br>


    
    </div>


<script type="text/javascript">
   function ToggleAllSubModule(id)
   {    
        check = document.getElementById("main_module-cb"+id);

   

    if (check.checked == true) 
    {
        $('.sub_module-cb'+id).each(function() {
                this.checked = true; 
            }); 
    }
    else
    {
        $('.sub_module-cb'+id).each(function() {
                this.checked = false; 
            });   
    }

   }

   function GetMemberRoles(val)
   {
      $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-member-roles-AJAX/"+val,
        beforeSend: function(){
                            $('#LoadingModal').modal('show');
          
        },
        success: function(data) {
                            $('#LoadingModal').modal('hide');

            $('#member_roleTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
   }









    $(function() {
        $("form[name='memberRolesForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
     
    },
    messages: {
     
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('memberRolesForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}save-roles",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
        success: function(data) {
           
                            $('#LoadingModal').modal('hide');

            get_status = data['status'];
            get_msg    = data['msg'];

                            if (get_status == "0") 
                            {

                             document.getElementById('toast').style.visibility = "visible";
                                document.getElementById('toast').className = "alert alert-danger alert-rounded fadeIn animated";
                                document.getElementById('toastMsg').innerHTML = "Failed, Try Again Later";


                                 setTimeout(function() {
                                document.getElementById('toast').className = "alert alert-danger alert-rounded fadeOut animated";

                            }, 5000);

                            }
                            else
                            {
                                document.getElementById('toast').style.visibility = "visible";
                                document.getElementById('toast').className = "alert alert-success alert-rounded fadeIn animated";
                                document.getElementById('toastMsg').innerHTML = get_msg;


                                 setTimeout(function() {
                                document.getElementById('toast').className = "alert alert-success alert-rounded fadeOut animated";

                            }, 5000);


                            }

    }
  });

    }
  });
  });
</script>

@endsection
