<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <!-- Title Page-->
    <title>Login</title>

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

    <style type="text/css">
        body { 
    background: url('{{asset('images/parallax/3.jpg')}}') no-repeat center center fixed;
    -moz-background-size: cover;
    -webkit-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
} 
    </style>

</head>

<body class="animsition">
    <div class="page-wrapper">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="{{env('IMG_URL')}}icon/logo.png" alt="CoolAdmin">
                            </a>
                        </div>

                        <center>
                        @if(session('success'))
                        <p class="text-success pulse animated">{{ session('success') }}</p>
                        {{ session()->forget('success') }}
                        @elseif(session('failed'))
                        <p class="text-danger pulse animated">{{ session('failed') }}</p>
                        {{ session()->forget('failed') }}
                        @endif
                        </center>


                        <div class="login-form">
                            <form action="{{route('login')}}" method="post" name="loginForm">
                                @csrf
                                <span class='arrow'>
                                <label class='error'></label>
                                </span>
                                <br>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input class="au-input au-input--full" type="email" name="email" placeholder="Email" id="email" value="{{old('email')}}" required="">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="au-input au-input--full" type="password" name="password" placeholder="Password" required="">
                                </div>
                                <div class="form-group">
                                    
                                    <label>
                                        <a href="javascript:void(0)" onclick="ForgotPassword()">Forgotten Password?</a>
                                    </label>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">sign in</button>
                            </form>
                            <div class="form-group">
                                <p class="text-center">
                                    Don't you have account?
                                    <a href="{{route('signup')}}">Sign Up Here</a>
                                </p>
                            </div>
                        </div>
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


</body>

<script type="text/javascript">
      $.validator.addMethod('emailFormat', function(value, element) {
        return this.optional(element) || (value.match(/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/));
    },
    'Please enter a valid email address.');
     $(function() {
    $("form[name='loginForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertBefore(element);
    },
    wrapper: 'span',
            
    rules: {
      
      email: {
        required: true,
        emailFormat: true,
      },
      password: {
        required: true,
      },

    },
    messages: {
     
    
      email: {
        required: "Please Provide an Email Address",
      },
      password: {
        required: "Please Provide a Password",
      },
     
    },
    submitHandler: function(form) {

      form.submit();
    }
  });
});


     function ForgotPassword()
    {
        if (document.getElementById('email').value.trim() == "" ) 
        {
            document.getElementById("email").style.border="solid red 2px";
        }
        else
        {     

                        var email  = document.getElementById('email').value.trim();
                        $.ajax({
                        type: "POST",
                        url : "{{ env('APP_URL')}}send-verification-code",

                        data: { 
                                'email'  : email,
                                "_token" : $('meta[name="csrf-token"]').attr('content') },
                        success: function(data) {
                            
                            get_status = data['status'];
                            get_msg    = data['msg'];

                            if (get_status == "0") 
                            {
                                //failed
                                 document.getElementById('toast').style.visibility = "visible";
                                document.getElementById('toast').className = "alert alert-danger alert-rounded fadeIn animated";
                                document.getElementById('toastMsg').innerHTML = get_msg;


                                 setTimeout(function() {
                                document.getElementById('toast').className = "alert alert-danger alert-rounded fadeOut animated";

                            }, 5000);

                            }
                            else
                            {
                                //success
                                window.location.href = "{{ env('APP_URL')}}forgot-password/"+email;


                            }




                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('Exception:' + errorThrown);
                        }
                    });


        }
    }

</script>


</html>
<!-- end document-->