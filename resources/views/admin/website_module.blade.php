@extends('layouts.admin_app')
@section('content')
       

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      

      <div class="table-data__tool">
            <div class="table-data__tool-left">

                <div class="form-group">
                                        
                    <input class="au-input au-input--full" type="text" name="search_text" id="module_search_text" required="" style="padding-right: 105px;" placeholder="Enter Module Name" onfocusout="SearchModule(this.value)">
                    <button class="btn btn-primary" style="float: right;position: absolute; margin: 0px 0px 0px -43px; height: 44px;"><i class="fa fa-search"></i></button>
                </div>
                                    
            </div>
            
            <div class="table-data__tool-right">
                <button class="au-btn au-btn-icon au-btn--green au-btn--small" onclick="AddModule()" type="button"><i class="zmdi zmdi-plus"></i>Add Module</button>
                                   
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
                                                    <th width="15%">Name</th>
                                                    <th width="15%">Route</th>
                                                    <th width="10%">Icon</th>
                                                    <th width="30%">Sub-Modules</th>

                                                    <th width="20%" class="tx-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 14px;" id="moduleTBody">
                                                @for($i = 0; $i < count($modules); $i++)
                                                <tr id="module{{$modules[$i]['id']}}">
                                                    <!-- <td>
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td> -->
                                                    <td>
                                                        {{$modules[$i]['name']}}
                                                    </td>
                                                    <td>
                                                        {{$modules[$i]['route']}}
                                                    </td>
                                                    <td>
                                                       <i class="{{$modules[$i]['icon']}} tx-24"></i> 
                                                    </td>
                                                   <td>
                                                       @for($j = 0 ; $j < count($sub_modules[$i]); $j++)  
                                                           <li id="sub_modules<?php echo $sub_modules[$i][$j]['id']?>"><a  href="javascript:void(0)" onclick='EditSubModule("<?php echo $sub_modules[$i][$j]['id']?>","<?php echo $sub_modules[$i][$j]['name']?>","<?php echo $sub_modules[$i][$j]['route']?>","<?php echo $sub_modules[$i][$j]['icon']?>")'> {{$sub_modules[$i][$j]['name']}}</a><a onclick='DeleteSubModule("<?php echo $sub_modules[$i][$j]['id'] ?>")' data-toggle="tooltip" title="Delete Sub-Module" class="tx-danger" style="float: right;" href="javascript:void(0)"><i class="fa fa-times-circle"></i></a></li>
                                                       @endfor
                                                   </td>
                                                    <td class="tx-center">
                                                         <a data-toggle="tooltip" title="Add Sub-Module" class="btn btn-success" href="javascript:void(0)" onclick='AddSubModule("<?php echo $modules[$i]['id']?>")'><i class="fa fa-plus tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" href="javascript:void(0)" onclick='EditModule("<?php echo $modules[$i]['id']?>","<?php echo $modules[$i]['name']?>","<?php echo $modules[$i]['route']?>","<?php echo $modules[$i]['icon']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" onclick='DeleteModule("<?php echo $modules[$i]['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a>
                                                    </td>

                                                </tr>
                                                @endfor
                                            </tbody>
                                        </table>

        </div>


<br>


    
    </div>
  </div>
</div>

<script type="text/javascript">


    function AddModule()
    {
        document.getElementById('module_name').value = "";
        document.getElementById('module_route').value = "";
        document.getElementById('icon').value = "";
        document.getElementById('selected_icon').className = "";
        
        document.getElementById('module_id').value = "";
        document.getElementById('module_parent_id').value = "";


        $('#ModuleModal').modal('show');
        $('#ModuleModalLabel').html('Add Module');

        document.getElementById('ModuleModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('ModuleModalDialog').style.paddingTop="0px";
        document.getElementById('ModuleModalData').style.padding="20px 20px 0px 20px";
    }

    function EditModule(id,name,route,icon)
    {
        document.getElementById('module_name').value = name;
        document.getElementById('module_route').value = route;
        document.getElementById('icon').value = icon;
        document.getElementById('selected_icon').className = icon;
        
        document.getElementById('module_id').value = id;
        document.getElementById('module_parent_id').value = "";



        $('#ModuleModal').modal('show');
        $('#ModuleModalLabel').html('Edit Module');

        document.getElementById('ModuleModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('ModuleModalDialog').style.paddingTop="0px";
        document.getElementById('ModuleModalData').style.padding="20px 20px 0px 20px";
    }

    function AddSubModule(id)
    {
        document.getElementById('module_name').value = "";
        document.getElementById('module_route').value = "";
        document.getElementById('icon').value = "";
        document.getElementById('selected_icon').className = "";
        document.getElementById('module_id').value = "";

        document.getElementById('module_parent_id').value = id;


        $('#ModuleModal').modal('show');
        $('#ModuleModalLabel').html('Add Sub-Module');

        document.getElementById('ModuleModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('ModuleModalDialog').style.paddingTop="0px";
        document.getElementById('ModuleModalData').style.padding="20px 20px 0px 20px";
    }

    function EditSubModule(id,name,route,icon)
    {
        document.getElementById('module_name').value = name;
        document.getElementById('module_route').value = route;
        document.getElementById('icon').value = icon;
        document.getElementById('selected_icon').className = icon;
        
        document.getElementById('module_id').value = id;
        document.getElementById('module_parent_id').value = "";


        $('#ModuleModal').modal('show');
        $('#ModuleModalLabel').html('Edit Sub-Module');

        document.getElementById('ModuleModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('ModuleModalDialog').style.paddingTop="0px";
        document.getElementById('ModuleModalData').style.padding="20px 20px 0px 20px";
    }

    function DeleteModule(id)
    {

        var r = confirm("Are You Sure?");
        if (r == false) 
        {
          return;
        }

        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}admin/delete-module/" + id,
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
                                    document.getElementById("module"+id).style.display="none";
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


     function DeleteSubModule(id)
    {

        var r = confirm("Are You Sure?");
        if (r == false) 
        {
          return;
        }

        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}admin/delete-module/" + id,
            success: function(data) {
                

                get_status = data['status'];
                get_msg    = data['msg'];

                                if (get_status == "0") 
                                {

                                 document.getElementById('toast').style.visibility = "visible";
                                    document.getElementById('toast').className = "alert alert-danger alert-rounded fadeIn animated";
                                    document.getElementById('toastMsg').innerHTML = "Failed to Delete Sub-Module.";


                                     setTimeout(function() {
                                 document.getElementById('toast').style.visibility = "hidden";

                                }, 5000);

                                }
                                else
                                {
                                    document.getElementById("sub_modules"+id).style.display="none";
                                    document.getElementById('toast').style.visibility = "visible";
                                    document.getElementById('toast').className = "alert alert-success alert-rounded fadeIn animated";
                                    document.getElementById('toastMsg').innerHTML =  "Sub-Module Deleted Successfully.";


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


    function SearchModule(value)
    {   
        var val = value.trim();

        if (!val) 
        {
            val = "0";
        }

        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/get-module-list-AJAX/"+val,
        success: function(data) {

            $('#moduleTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }
</script>

@endsection
