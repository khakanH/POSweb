<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <!-- Title Page-->
    <title>Email Verification</title>

    <!-- Fontfaces CSS-->
    <link href="{{asset('css/font-face.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/font-awesome-4.7/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/font-awesome-5/css/fontawesome-all.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="{{asset('vendor/bootstrap-4.1/bootstrap.min.css')}}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{asset('vendor/animsition/animsition.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/wow/animate.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/css-hamburgers/hamburgers.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/slick/slick.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/select2/select2.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{asset('css/theme.css')}}" rel="stylesheet" media="all">
 <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/css/login-resgister.css?'.time().'')}}">
    <style type="text/css">
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
               -webkit-appearance: none;
               margin: 0;
            }
              body { 
    background: rgba(0,0,0,0.8);
} 
    </style> 
    
</head>

<body class="animsition">
    <div class="page-wrapper">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content" style="border-radius: 20px;">
                       

                        <center>

                        @if(session('success'))
                             <span id="sendCodeMsg">
                                <p class="text-success pulse animated">{{ session('success') }}</p>
                                {{ session()->forget('success') }}</span>
                            @elseif(session('failed'))
                            <span id="sendCodeMsg">
                                <p class="text-danger pulse animated">{{ session('failed') }}</p>
                                {{ session()->forget('failed') }}</span>
                        @endif
                        <p class="text-danger pulse animated" id="alertMsg2"></p>
                        <br>
                        <h3>Reset Password</h3>
                        <br>

                         <form action="{{ route('save-new-password') }}" method="post" name="resetPasswordForm">
                            @csrf
                            <span class='arrow'>
                            <label class='error'></label>
                            </span>
                            
                            <input type="hidden" name="email" id="email" value="{{ $email }}">

                        <div class="form-group">
                        <i id="pass-eye" style="font-size: 18px;  z-index: 1; display: flex; position: absolute; margin: 12px 0px 0px 12px; cursor: pointer;" class="fa fa-eye" onclick="ShowPassword('new_password','pass-eye')"></i>
                        <input type="password" style="padding-left: 45px;" class="form-control inp" name="new_password" id="new_password" placeholder="Enter New Password" autocomplete="off">
                      </div>
                       <div class="form-group">
                        <i id="c_pass-eye" style="font-size: 18px;  z-index: 1; display: flex; position: absolute; margin: 12px 0px 0px 12px; cursor: pointer;" class="fa fa-eye" onclick="ShowPassword('confirm_new_password','c_pass-eye')"></i>
                        <input type="password" style="padding-left: 45px;" class="form-control inp" name="confirm_new_password" id="confirm_new_password" placeholder="Confirm New password" autocomplete="off">
                      </div>

                        <br>
                            
                        <button class="btn btn-default-pos" type="submit" style="display: inline-block; width: 100%; text-align: center;">Confirm</button>
                        
                        </form>   

                </div>
        </div>
    </div>
</div>


        

    <div class="alert alert-success alert-rounded fadeIn animated" id="toast" style="visibility: hidden; position: fixed; bottom: 5px; left: 30px; z-index: 999; font-size: 15px;">
                                        <p id="toastMsg" style="float: left;"></p> 
                                            <button type="button" class="close" onclick="hideToast('toast')" aria-label="Close" style="float: right;"> &nbsp;&nbsp;&nbsp;<span aria-hidden="true">×</span> </button>
                                        </div>


    <!-- Jquery JS-->
    <script src="{{asset('vendor/jquery-3.2.1.min.js')}}"></script>
    <!-- Bootstrap JS-->
    <script src="{{asset('vendor/bootstrap-4.1/popper.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-4.1/bootstrap.min.js')}}"></script>
    <!-- Vendor JS       -->
    <script src="{{asset('vendor/slick/slick.min.js')}}">
    </script>
    <script src="{{asset('vendor/wow/wow.min.js')}}"></script>
    <script src="{{asset('vendor/animsition/animsition.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-progressbar/bootstrap-progressbar.min.js')}}">
    </script>
    <script src="{{asset('vendor/counter-up/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('vendor/counter-up/jquery.counterup.min.js')}}">
    </script>
    <script src="{{asset('vendor/circle-progress/circle-progress.min.js')}}"></script>
    <script src="{{asset('vendor/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('vendor/chartjs/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('vendor/select2/select2.min.js')}}">
    </script>

    <!-- Main JS-->
    <script src="{{asset('js/main.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

    <script type="text/javascript">

                        $(function() {
        $("form[name='resetPasswordForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',
            
    rules: {
      
     new_password: {
        required: true,
        minlength: 6,
      },
      confirm_new_password: {
                    required: true,
                    equalTo: "#new_password"
                },
     
    },
    messages: {
        
        new_password: {
        required: "Please Provide a Password",
        minlength: "Password Must be at Least 6 Characters Long",
      },
      confirm_new_password:{
          required: "Please Provide a Confirm Password",
          equalTo:"Password Mismatch",
      }
      
    },
    submitHandler: function(form) {
      form.submit();
    }
  });
});

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
    
    </script>

  </body>

</html>
