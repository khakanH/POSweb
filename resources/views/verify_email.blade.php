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
                        <h3>Account Verification</h3>
                        <br>
                        <h4>Enter Code Below:</h4>
                        <br>
                         <div class="row" style="display: inline-flex;">
                            
                            <input type="hidden" name="email" id="email" value="{{ $email }}">
                            <!--    new account creation verification 
                                    and forgot password verification 
                                    1 for new acount
                                    2 for forgot password

                            -->
                            <input type="hidden" name="verification_type" id="verification_type" value="{{ $verification_type }}">

                            <input type="number" maxlength="1" autofocus="" value="" pattern="/^-?\d+\.?\d*$/" id="pin1" name="pin1" class="form-control inp pin" onKeyPress="if(this.value.length==1) return false;" placeholder="X" style="width: 60px; height: 60px; text-align: center; font-size: 20px;" onfocus="ToNormal()">&nbsp;<input type="number" maxlength="1" value="" pattern="/^-?\d+\.?\d*$/" id="pin2" name="pin2" class="form-control inp pin" onKeyPress="if(this.value.length==1) return false;" placeholder="X" style="width: 60px; height: 60px; text-align: center; font-size: 20px;" onfocus="ToNormal()">&nbsp;<input type="number" maxlength="1" value="" pattern="/^-?\d+\.?\d*$/" id="pin3" name="pin3" class="form-control inp pin" onKeyPress="if(this.value.length==1) return false;" placeholder="X" style="width: 60px; height: 60px; text-align: center; font-size: 20px;" onfocus="ToNormal()">&nbsp;<input type="number" maxlength="1" value="" pattern="/^-?\d+\.?\d*$/" id="pin4" name="pin4" class="form-control inp pin" onKeyPress="if(this.value.length==1) return false;" placeholder="X" style="width: 60px; height: 60px; text-align: center; font-size: 20px;" onfocus="ToNormal()">
                            </div>
                            <br><br>
                            <p id="base-timer-label"></p>
                            <button class="btn btn-default-pos" onclick="VerifyNumber()" type="button" style="display: inline-block; width: 69%; text-align: center;">Confirm</button>
                        </center>
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

    const TIME_LIMIT = 60;

    let timePassed = 0;
    let timeLeft = TIME_LIMIT;
    
    $( document ).ready(function() {
        
        setInterval(() => {
    
    // The amount of time passed increments by one
    timePassed = timePassed += 1;
    timeLeft = TIME_LIMIT - timePassed;
    
        // The time left label is updated
        if (timeLeft > 0) 
        {
            document.getElementById("base-timer-label").innerHTML = "Resend Code in: "+timeLeft+" sec";
        }
        else if(timeLeft == 0)
        {   
            document.getElementById("base-timer-label").innerHTML = "Resend Code in: "+timeLeft+" sec";

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
                                
                                document.getElementById('toast').style.visibility = "visible";
                                document.getElementById('toast').className = "alert alert-success alert-rounded fadeIn animated";
                                document.getElementById('toastMsg').innerHTML = get_msg;


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

      }, 1000);

    });


    function ToNormal()
    {
        document.getElementById("pin1").style.border="solid #e9ecef 1px";
        document.getElementById("pin2").style.border="solid #e9ecef 1px";
        document.getElementById("pin3").style.border="solid #e9ecef 1px";
        document.getElementById("pin4").style.border="solid #e9ecef 1px";
    }



    function VerifyNumber()
    {
            pin1 = document.getElementById("pin1").value;
            pin2 = document.getElementById("pin2").value;
            pin3 = document.getElementById("pin3").value;
            pin4 = document.getElementById("pin4").value;

            if (!(pin1 && pin2 && pin3 && pin4)) 
            {
                

                if (!pin1) 
                {
                    document.getElementById("pin1").style.border="solid red 2px";
                }
                if (!pin2) 
                {
                    document.getElementById("pin2").style.border="solid red 2px";
                }
                if (!pin3) 
                {
                    document.getElementById("pin3").style.border="solid red 2px";
                }
                 if (!pin4) 
                {
                    document.getElementById("pin4").style.border="solid red 2px";
                }

                return;

            }  


            email = document.getElementById("email").value.trim();
            pin   = pin1+pin2+pin3+pin4;


           
                $.ajax({
                        type: "POST",
                        url : "{{env('APP_URL')}}check-verification-code",

                        data: { 
                                'email'  : email,
                                'pin_code'  : pin,
                                "_token" : $('meta[name="csrf-token"]').attr('content') },
                        success: function(data) {
                            
                            get_status = data['status'];
                            get_msg    = data['msg'];

                            if (get_status == "0") 
                            {

                              document.getElementById('sendCodeMsg').style.display = "none";
                              document.getElementById('alertMsg2').style.display = "block";
                              document.getElementById('alertMsg2').className = "text-danger pulse animated";
                              document.getElementById('alertMsg2').innerHTML = get_msg;

                            }
                            else
                            {
                                //success
                                if (document.getElementById("verification_type").value == 1) 
                                {
                                    window.location.href = "{{ route('index')}}";
                                }
                                else
                                {
                                    window.location.href = "{{ env('APP_URL')}}reset-password/"+email;
                                }

                            }




                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('Exception:' + errorThrown);
                        }
                    });
           

    }




    $(".pin").keyup(function () {
    if (this.value.length == this.maxLength) {
      var $next = $(this).next('.pin');
      if ($next.length)
          $(this).next('.pin').focus();
      else
          $(this).blur();
    }
    });




    $('#pin1,#pin2,#pin3,#pin4').keydown(function (e) {
        
          var key = e.keyCode;
          
          if (!((key == 8) || (key == 9) || (key == 65) || (key == 67) || (key == 86) || (key == 88) || (key == 46) ||(key == 116) || (key == 35) || (key == 36) || (key == 37) || (key == 39) || (key >= 48 && key <= 57))) {
             e.preventDefault();


            }

        
      });

     function hideToast(id)
        {
            document.getElementById(id).style.visibility = "hidden";
        }

    
    </script>

  </body>

</html>
