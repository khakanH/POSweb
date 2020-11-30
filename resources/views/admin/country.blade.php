@extends('layouts.admin_app')
@section('content')
       

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">

        <div class="table-data__tool">
            <div class="table-data__tool-left">

                <div class="form-group">
                                        
                    <input class="au-input au-input--full" type="text" name="search_text" id="country_search_text" required="" style="padding-right: 105px;" placeholder="Enter Country Name" onfocusout="SearchCountry(this.value)">
                    <button class="btn btn-primary" style="float: right;position: absolute; margin: 0px 0px 0px -43px; height: 44px;"><i class="fa fa-search"></i></button>
                </div>
                                    
            </div>
            
            <div class="table-data__tool-right">
                <button class="au-btn au-btn-icon au-btn--green au-btn--small" onclick="AddCountry()" type="button"><i class="zmdi zmdi-plus"></i>Add Country</button>
                                   
            </div>
        </div>



        <div class="user-data">
                  
                                        <table class="table text-center table-striped">
                                            <thead>
                                                <tr>
                                                    <!-- <td width="2%">
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td> -->
                                                    <th width="5%">Name</th>
                                                    <th width="15%">Country Code</th>
                                                    <th width="10%">Currency</th>
                                                    <th width="10%">Flag</th>
                                                    <th width="10%">Status</th>
                                                    <th width="20%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 14px;" id="countryTBody">
                                                @foreach($country as $key)
                                                <tr id="country{{$key['id']}}">
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
                                                        {{$key['country_code']}}
                                                    </td>
                                                    <td>
                                                        {{$key['currency_standard_name']}} - {{$key['currency_short_name']}}
                                                    </td>
                                                    <td>
                                                        <img src="{{env('IMG_URL')}}{{$key['flag_icon']}}" width="40" height="30">
                                                    </td>
                                                    <td class="text-center">
                                                         <label class="switch switch-3d switch-primary mr-3">
                                                          <input id="visibility_value<?php echo $key['id']?>" onclick='CountryStatus("<?php echo $key['id']?>","<?php echo $key['is_show']; ?>")' type="checkbox" class="switch-input"  <?php if ($key['is_show'] ==1): ?>
                                                              checked
                                                          <?php endif ?> value="<?php echo $key['is_show'] ?>">
                                                          <span class="switch-label"></span>
                                                          <span class="switch-handle"></span>
                                                        </label>
                                                    </td>
                                                    <td class="text-center">
                                                         <a class="btn btn-primary" href="javascript:void(0)" onclick='EditCountry("<?php echo $key['id']?>","<?php echo $key['name']?>","<?php echo $key['country_code']?>","<?php echo $key['currency_short_name']?>","<?php echo $key['currency_standard_name']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" onclick='DeleteCountry("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a>
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
    
    function AddCountry()
    {
        document.getElementById('country_name').value = "";
        document.getElementById('country_code').value = "";
        document.getElementById('currency_short_name').value = "";
        document.getElementById('currency_standard_name').value = "";
        document.getElementById('country_id').value = "";
        document.getElementById('country_flag_div').style.display = "block";
        document.getElementById('myDropdown').value = "";
        $('#CountryModal').modal('show');
        $('#CountryModalLabel').html('Add Country');

        document.getElementById('CountryModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('CountryModalDialog').style.paddingTop="0px";
        document.getElementById('CountryModalData').style.padding="20px 20px 0px 20px";

        // $.ajax({
        //     type: "GET",
        //     url: "{{ env('APP_URL')}}admin/get-country-flag-icons",
        //     success: function(data) {

        //         $('#myDropdown').html(data);
        //     },
        //     error: function(jqXHR, textStatus, errorThrown) {
        //         alert('Exception:' + errorThrown);
        //     }
        // });


    }

    function EditCountry(id,name,code,currency_short,currency_standard)
    {
        document.getElementById('country_name').value = name;
        document.getElementById('country_code').value = code;
        document.getElementById('currency_short_name').value = currency_short;
        document.getElementById('currency_standard_name').value = currency_standard;
        document.getElementById('country_id').value = id;
        document.getElementById('country_flag_div').style.display = "none";
        

        $('#CountryModal').modal('show');
        $('#CountryModalLabel').html('Edit Country');

        document.getElementById('CountryModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('CountryModalDialog').style.paddingTop="0px";
        document.getElementById('CountryModalData').style.padding="20px 20px 0px 20px";
    }

    function SearchCountry(value)
    {   
        var val = value.trim();

        if (!val) 
        {
            val = "0";
        }

        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/get-country-list-AJAX/"+val,
        success: function(data) {

            $('#countryTBody').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }

    function DeleteCountry(id)
    {

        var r = confirm("Are You Sure?");
        if (r == false) 
        {
          return;
        }

        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}admin/delete-country/" + id,
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
                                    document.getElementById("country"+id).style.display="none";
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



    function CountryStatus(id,status)
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
        url: "{{ env('APP_URL')}}admin/change-country-availability/"+id +"/"+ status,
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
