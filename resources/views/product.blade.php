@extends('layouts.app')
@section('content')

    
        

        



     <div class="tab-content">
        <h4 class=""> Products</h4>
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


                            <div class="row">
                                <div class="col-lg-4">

                                    <div class="form-group">
                                        
                                    <input class="form-control inp" type="text" name="search_text" id="prod_search_text" required="" style="" placeholder="Enter Product Name or Code" onfocusout="SearchProduct(this.value)">
                                   
                                    </div>
                                    
                                </div>

                                <div class="col-lg-3"><br></div>
                                <div class="col-lg-5">
                                <form method="post" action="{{route('upload-products-using-csv')}}" enctype="multipart/form-data">
                                        @csrf 
                                    <button class="btn btn-default-pos" onclick="AddProduct()" type="button">
                                        &nbsp;&nbsp;<i class="zmdi zmdi-plus"></i> &nbsp;Add Product &nbsp;&nbsp;</button>

                                           &nbsp;&nbsp;&nbsp;
                                       <label for="csv_file" class="btn btn-default-pos">&nbsp;&nbsp;<i class="fa fa-file"></i> &nbsp;Select CSV File&nbsp;&nbsp;</label><input id="csv_file" name="csv_file" style="visibility:hidden; width: 15px;" onchange="form.submit()" type="file" accept=".csv">
                                        

                                   
                                        </form>
                                </div>
                            </div>





                               <div class="table-div">
                                <div class="table-pad-div">
                                    
                                    <table class="table table-1 table-sm dataTablesOptions">
                                        <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>Description</th>
                                                <th>Cost</th>
                                                <th>Tax %</th>
                                                <th>Price</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="prodTBody">
                                            @foreach($product_list as $key)
                                            <tr id="prod<?php echo $key['id']?>">
                                                <td scope="row"><b>{{$loop->iteration}}</b></td>
                                                <td>{{$key['product_code']}}</td>
                                                <td>{{$key['name']}}</td>
                                                <td>{{isset($key->category_name->name)?$key->category_name->name:"-"}}</td>
                                                <td>{{Str::limit($key['description'],35)}}</td>
                                                <td>{{$key['cost']}}</td>
                                                <td>{{$key['tax']}}%</td>
                                                <td>{{$key['price']}}</td>
                                                <td><a class="" href="javascript:void(0)" onclick='EditProduct("<?php echo $key['id']?>","<?php echo $key['product_code']?>","<?php echo $key['name']?>","<?php echo $key['category_id']?>","<?php echo $key['cost']?>","<?php echo $key['tax']?>","<?php echo $key['price']?>","<?php echo $key['description']?>","<?php echo $key['image']?>")'><i class="fa fa-edit tx-20"></i></a>&nbsp;&nbsp;&nbsp;<a class="" onclick='DeleteProduct("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-20 text-danger"></i></a>&nbsp;&nbsp;&nbsp;<a href="{{env('IMG_URL')}}{{$key['image']}}" class="" target="_blank"><i class="fa fa-picture-o text-success tx-20"></i></a>
											</td>
                                            </tr>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                <!-- END DATA TABLE-->

                    </div>
           






<script type="text/javascript">
    function AddProduct()
    {
        document.getElementById('prod_name').value = "";
        document.getElementById('prod_code').value = "";
        document.getElementById('prod_price').value = "";
        document.getElementById('prod_tax').value = "";
        document.getElementById('prod_cost').value = "";
        document.getElementById('prod_descrip').value = "";
        document.getElementById('prod_id').value = "";
        document.getElementById("product_image").value = "";
        document.getElementById('product_image_output').src = "{{ env('IMG_URL')}}choose_img.png";
        $('#ProductModal').modal('show');
        $('#ProductModalLabel').html('Add New Product');

        document.getElementById('ProductModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('ProductModalDialog').style.paddingTop="0px";
        document.getElementById('ProductModalData').style.padding="20px 20px 0px 20px";


        cate_id = "0"

         $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-category-name-list/"+cate_id,
        success: function(data) {

            $('#prod_cate').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });


    }

    function EditProduct(id,code,name,cate_id,cost,tax,price,description,image)
    {
        document.getElementById('prod_code').value = code;
        document.getElementById('prod_name').value = name;
        document.getElementById('prod_cost').value = cost;
        document.getElementById('prod_tax').value = tax;
        document.getElementById('prod_price').value = price;
        document.getElementById('prod_descrip').value = description;
        document.getElementById('product_image_output').src = "{{ env('IMG_URL')}}"+image;
        document.getElementById('prod_id').value = id;
        $('#ProductModal').modal('show');
        $('#ProductModalLabel').html('Edit Product');

        document.getElementById('ProductModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('ProductModalDialog').style.paddingTop="0px";
        document.getElementById('ProductModalData').style.padding="20px 20px 0px 20px";



         $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-category-name-list/"+cate_id,
        success: function(data) {

            $('#prod_cate').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }

    function SearchProduct(value)
    {   
        var val = value.trim();

        if (!val) 
        {
            val = "0";
        }

             $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-product-list-AJAX/"+val,
        success: function(data) {

            $('#prodTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }

    function DeleteProduct(id)
    {

        var r = confirm("Are You Sure?");
        if (r == false) 
        {
          return;
        }

        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}delete-product/" + id,
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
                                    document.getElementById("prod"+id).style.display="none";
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
