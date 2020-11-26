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
                                                    <th width="30%">Name</th>
                                                    <th width="30%">Route</th>
                                                    <th width="25%">Icon</th>
                                                    <th width="20%" class="tx-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 14px;" id="moduleTBody">
                                                @foreach($modules as $key)
                                                <tr id="module{{$key['id']}}">
                                                    <!-- <td>
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td> -->
                                                    <td>
                                                        {{$key['name']}}
                                                    </td>
                                                    <td>
                                                        {{$key['route']}}
                                                    </td>
                                                    <td>
                                                       <i class="{{$key['icon']}} tx-24"></i> 
                                                    </td>
                                                   
                                                    <td class="tx-center">
                                                         <a class="btn btn-primary" href="javascript:void(0)" onclick='EditModule("<?php echo $key['id']?>","<?php echo $key['name']?>","<?php echo $key['route']?>","<?php echo $key['icon']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" onclick='DeleteModule("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a>
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
    function EditModule(id,name,route,icon)
    {
        document.getElementById('module_name').value = name;
        document.getElementById('module_route').value = route;
        document.getElementById('icon').value = icon;
        document.getElementById('selected_icon').className = icon;
        
        document.getElementById('module_id').value = id;


        $('#ModuleModal').modal('show');
        $('#ModuleModalLabel').html('Edit Module');

        document.getElementById('ModuleModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('ModuleModalDialog').style.paddingTop="0px";
        document.getElementById('ModuleModalData').style.padding="20px 20px 0px 20px";
    }
</script>

@endsection
