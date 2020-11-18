@extends('layouts.app')
@section('content')

  <style type="text/css">
    
    input[type="file"] {
    display: none;
}
  </style>  
  
<div class="page-content--bgf7">

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
      
      <h3 class="pb-4 display-5"> Greetings {{$user_info['username']}}</h3>




      <div class="col-12">
          <div class="card">
            <div class="card-header">
              <strong class="card-title">Profile Settings</strong>
            </div>
            <div class="card-body">


              <form name="profileForm" id="profileForm" method="post" enctype="multipart/form-data">
              @csrf
              <span class='arrow'>
              <label class='error'></label>
              </span> 

              <div class="row" style="text-align: center;">
                <div class="col-12">

                  <img id="profile_output" src="{{ env('IMG_URL')}}{{$user_info['user_image'] }}" width="100" height="100" style=" min-width: 100px; min-height: 100px; border-radius: 50%; object-position: top; object-fit: cover;">&nbsp;&nbsp;<label style="cursor: pointer; vertical-align: middle;" onchange="profile_loadFile(event)"><i style="font-size: 30px;" class="fas fa-camera"></i><br> Choose Photo<input type="file"  name="profile_image"  accept="image/*"></label>
                  @if($errors->has('profile_image'))
                    <br><div style="margin-top: 10px; color: red; font-size: 12px; width: 100%; font-weight: bold;">{{ $errors->first('profile_image') }}</div>
                  @endif
                </div>
              </div>

              <br>
                        
              <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="name" class="control-label mb-1">Name:</label>
                        <div class="input-group">
                          <input type="text"  value="{{ $user_info['username'] }}" id="name" name="name" class="form-control" placeholder="Enter Name"  autocomplete="off" >
                                                      
                        </div>
                    </div>
                  </div>
                            
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="type" class="control-label mb-1">Account Type:</label>
                    
                    <input type="text" id="type" class="form-control" disabled="" readonly="" value="{{$user_info->member_type_name['name']}}">                          
                                
                  </div>
                </div>
              </div>
                    

            <button type="submit" class="btn btn-primary" style="width: 20%; float: right;">Save</button>
    
            </form>


            </div>
          </div>
      </div>




























































      <div class="col-12">
          <div class="card">
            <div class="card-header">
              <strong class="card-title">Change Email Address</strong>
            </div>
            <div class="card-body">
              

              <form name="changeEmailAddress" method="post" enctype="multipart/form-data" id="emailForm">
              @csrf
              <span class='arrow'>
              <label class='error'></label>
              </span> 

              <div class="form-group">
                <label class="control-label mb-1">Current Email Address:</label>
                  
                  <input  type="text" class="form-control" readonly="" disabled="" value="{{$user_info->email}}">
              </div>
              
              <div class="form-group">
                <label for="new_email" class="control-label mb-1">New Email Address:</label>
                              
                <input type="email" value=""  id="new_email" name="new_email" class="form-control" placeholder="example@gmail.com">
              </div>

              <div class="form-group">
                <label for="password" class="control-label mb-1">Password:</label>
                <i id="pass-eye" style="font-size: 18px;  z-index: 1; display: flex; position: absolute; margin: 12px 0px 0px 12px; cursor: pointer;" class="fa fa-eye" onclick="ShowPassword('password','pass-eye')"></i>
                <input style="padding-left: 45px;" type="password" id="password" name="password" placeholder="********" class="form-control">
                                                    
              </div>

            <button type="submit" class="btn btn-primary" style="width: 20%; float: right;">Save</button>
    
            <br><br>
            </form>    


            </div>
          </div>
      </div>

      <div class="col-12">
          <div class="card">
            <div class="card-header">
              <strong class="card-title">Change Password</strong>
            </div>
            <div class="card-body">
              
               <form name="changePasswordForm" method="post" enctype="multipart/form-data" id="passForm">
                            @csrf
                            <span class='arrow'>
                            <label class='error'></label>
                            </span> 
                            
                            <div class="form-group">
                                <label for="current_pass" class="control-label mb-1">Current Password:</label>
                                                    <i id="cur_pass-eye" style="font-size: 18px;  z-index: 1; display: flex; position: absolute; margin: 12px 0px 0px 12px; cursor: pointer;" class="fa fa-eye" onclick="ShowPassword('current_pass','cur_pass-eye')"></i>
                                                    <input style="padding-left: 45px;" type="password" id="current_pass" name="current_pass" placeholder="********" class="form-control">
                                                    
                            </div>
                            <div class="form-group">
                                <label for="new_pass" class="control-label mb-1">New Password:</label>
                                                     <i id="new_pass-eye" style="font-size: 18px;  z-index: 1; display: flex; position: absolute; margin: 12px 0px 0px 12px; cursor: pointer;" class="fa fa-eye" onclick="ShowPassword('new_pass','new_pass-eye')"></i>
                                                    <input style="padding-left: 45px;" type="password" id="new_pass" name="new_pass" placeholder="********" class="form-control">
                                                    
                            </div>
                            <div class="form-group">
                                <label for="confirm_new_pass" class="control-label mb-1">Confirm New Password:</label>              
                                                     <i id="con_new_pass-eye" style="font-size: 18px;  z-index: 1; display: flex; position: absolute; margin: 12px 0px 0px 12px; cursor: pointer;" class="fa fa-eye" onclick="ShowPassword('confirm_new_pass','con_new_pass-eye')"></i>
                                                    <input style="padding-left: 45px;" type="password" id="confirm_new_pass" name="confirm_new_pass" placeholder="********" class="form-control">
                                                    
                            </div>
                            
                         

<!-- -------------------------------------------------------------------------------------------------------- -->

          <button type="submit" class="btn btn-primary" id="change_password_loader" style="width: 20%; float: right;">Save</button>
    
<!-- -------------------------------------------------------------------------------------------------------- -->   
<br><br>

                            </form>

            </div>
          </div>
      </div>




    </div>
  </section>
           

</div>
  

  <script type="text/javascript">
    var profile_loadFile = function(event) {
    var profile_output = document.getElementById('profile_output');
    profile_output.src = URL.createObjectURL(event.target.files[0]);
    profile_output.onload = function() {
      URL.revokeObjectURL(profile_output.src) // free memory
    }
  };

  function ShowPassword(id,eye_id)
    {
        var i = document.getElementById(id);

        if (i.type === "password") 
        {
            i.type = "text";
            document.getElementById(eye_id).className="fas fa-eye-slash";
        }
        else
        {
            i.type = "password";
            document.getElementById(eye_id).className="fas fa-eye";

        }
    }




   $.validator.addMethod('emailFormat', function(value, element) {
        return this.optional(element) || (value.match(/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/));
    },
    'Please enter a valid email address.');
  

    $(function() {
        $("form[name='profileForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      name: {
        required: true,
      },
    },
    messages: {
      name: {
        required: "Please Provide a User Name",
      },
     
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('profileForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}save-profile",
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



     $(function() {
        $("form[name='changeEmailAddress']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',
            
    rules: {
     
     new_email: {
        required: true,
        emailFormat: true,

      },
      password: {
        required: true,
      },
     
    },
    messages: {
     
      new_email:{
        required: "Please Provide a New Email Address",

      },
      password: {
        required: "Please Provide a Password",
      },
    },
    submitHandler: function(form) {
            
                let myForm = document.getElementById('emailForm');
                let formData = new FormData(myForm);

                 $.ajax({
                type: "POST",
                url: "{{ env('APP_URL')}}change-email-address-check",
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
                                        document.getElementById('toastMsg').innerHTML = get_msg;


                                         setTimeout(function() {
                                        document.getElementById('toast').className = "alert alert-danger alert-rounded fadeOut animated";

                                    }, 5000);

                                    }
                                    else
                                    {

                                       let email = document.getElementById("new_email").value;
                                       //verify new number
                                       window.location.href = "{{ env('APP_URL')}}verify-email/"+email;


                                    }
                      }
                    });

    }
  });
});


$(function() {
        $("form[name='changePasswordForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',
            
    rules: {
        current_pass: {
            required:true,
        },
        new_pass: {
            required:true,
            minlength:6,
        },
        confirm_new_pass: {
            required:true,
            equalTo: "#new_pass"

        },

    },
    messages: {
      current_pass: {
            required:"Please Provide Current Password",
        },
        new_pass: {
            required:"Please Provide New Password",
            minlength:"New Password Must be at Least 6 Characters Long",
        },
        confirm_new_pass: {
            required:"Please Provide Confirm New Password",
            equalTo: "New Password Mismatch"

        },
      
    },
    submitHandler: function(form) {


                let myForm = document.getElementById('passForm');
                let formData = new FormData(myForm);

                 $.ajax({
                type: "POST",
                url: "{{ env('APP_URL')}}change-password",
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
                                        document.getElementById('toastMsg').innerHTML = get_msg;


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
