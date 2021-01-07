@extends('layouts.app')
@section('content')

    
  

     <div class="tab-content">
 <h5 class=""> Category</h5>
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
                                        
                                    <input class="form-control inp" type="text" name="search_text" id="cate_search_text" required="" style="" placeholder="Enter Category Name" onfocusout="SearchCategory(this.value)">
                                   
                                    </div>
                                    
                                </div>
                                <div class="table-data__tool-right">
                                    <button class="btn btn-default-pos" onclick="AddCategory()">
                                        &nbsp;&nbsp;<i class="zmdi zmdi-plus"></i> &nbsp;Add Category&nbsp;&nbsp;</button>
                                   
                                </div>
                            </div>



                                <!-- DATA TABLE-->
                               <div class="table-div">
                                <div class="table-pad-div">
                                    
                                    <table class="table table-1 table-sm">
                                        <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cateTBody">
                                            @foreach($category_list as $key)
                                            <tr id="cate<?php echo $key['id']?>">
                                                <td scope="row"><b>{{$loop->iteration}}</b></td>
                                                <td>{{$key['id']}}</td>
                                                <td>{{$key['name']}}</td>
                                                <td><a class="" href="javascript:void(0)" onclick='EditCategory("<?php echo $key['id']?>","<?php echo $key['name']?>")'><i class="fa fa-edit tx-20"></i></a>&nbsp;&nbsp;&nbsp;<a class="" onclick='DeleteCategory("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash text-danger tx-20"></i></a></td>
                                            </tr>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                <!-- END DATA TABLE-->

</div>



<script type="text/javascript">
    function AddCategory()
    {
        document.getElementById('cate_name').value = "";
        document.getElementById('cate_id').value = "";
        $('#CategoryModal').modal('show');
        $('#CategoryModalLabel').html('Add New Category');

        document.getElementById('CategoryModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('CategoryModalDialog').style.paddingTop="0px";
        document.getElementById('CategoryModalData').style.padding="20px 20px 0px 20px";
    }

    function EditCategory(id,name)
    {
        document.getElementById('cate_name').value = name;
        document.getElementById('cate_id').value = id;
        $('#CategoryModal').modal('show');
        $('#CategoryModalLabel').html('Edit Category');

        document.getElementById('CategoryModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('CategoryModalDialog').style.paddingTop="0px";
        document.getElementById('CategoryModalData').style.padding="20px 20px 0px 20px";
    }

    function SearchCategory(value)
    {   
        var val = value.trim();

        if (!val) 
        {
            val = "0";
        }

             $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-category-list-AJAX/"+val,
        success: function(data) {

            $('#cateTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }

    function DeleteCategory(id)
    {

        var r = confirm("Are You Sure?");
        if (r == false) 
        {
          return;
        }

        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}delete-category/" + id,
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
                                    document.getElementById("cate"+id).style.display="none";
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
