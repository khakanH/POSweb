<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS</title>
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/css/login-resgister.css?'.time().'')}}">
</head>
<body>


    <div class="container">
        <div class="row" style="padding-top: 50px;" >
          <div class="logo-box col-4 col-md-12 col-sm-12">
            <a href="#"><img src="{{env('IMG_URL')}}SPROD-POS-logo.png" alt="POS" class="logo-img"></a>
          </div>
           @if(session('success'))
                        <p class="text-success pulse animated">{{ session('success') }}</p>
                        {{ session()->forget('success') }}
                        @elseif(session('failed'))
                        <p class="text-danger pulse animated">{{ session('failed') }}</p>
                        {{ session()->forget('failed') }}
                        @endif
            <div id="tab-button" class="col-12 col-md-7 col-sm-12" >
                <a href="#" class="login active">Log In</a>
                <a href="#" class="register">Register</a>
            </div>

            <div class="content col-12 col-md-6 col-sm-6" >
                
                <form action="{{route('login')}}" method="post" name="loginForm" id="login-box">
                     @csrf
                     <input type="hidden" name="form_type" value="1">

                                <span class='arrow'>
                                <label class='error'></label>
                                </span>
                                <br>
                    <div class="mb-3 mt-4">
                      <label for="exampleInputEmail1" class="form-label">Email address</label><br>
                      <input type="email" class="form-control inp" value="{{old('email')}}" id="email" name="email"  aria-describedby="emailHelp" required>
                    </div>
                    <div class="">
                      <label for="exampleInputPassword1" class="form-label">Password</label><br>
                      <input type="password" class="form-control inp" value="{{old('password')}}" id="password" name="password" required>
                    </div>
                    <div><a href="#" id="forgot-password">Forgotten Password?</a></div>
                    
                    <button type="submit" class="btn btn-primary sub-btn" id="login-btn">Sign In</button>
                    <p class="already-acc">Don't have an account? <a href="#login-box" class="signup">Register</a> now</p>
                  </form>
            </div>

            <div class="other col-12 col-sm-6 col-md-6" >
                <form action="{{route('register')}}" method="post" name="signupForm" id="login-box">
                     @csrf
                     <input type="hidden" name="form_type" value="2">
                                <span class='arrow'>
                                <label class='error'></label>
                                </span>
                                <br>
                    <div class="mb-3 mt-4">
                      <label for="username" class="form-label">Username</label><br>
                      <input type="text" class="form-control inp" id="username" name="username" >
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label><br>
                        <input type="email" class="form-control inp" id="email" name="email" aria-describedby="emailHelp">
                      </div>
                    <div class="mb-3">
                      <label for="password" class="form-label">Password</label><br>
                      <input type="password" class="form-control inp" id="password_reg" name="password">
                    </div>
                    <div class="">
                        <label for="confirm_password" class="form-label">Confirm Password</label><br>
                        <input type="password" class="form-control inp" id="confirm_password" name="confirm_password">
                      </div>
                    
                    <div class="mb-3 ">
                        <input type="checkbox" id="aggree" name="aggree"> <span>Agree terms and policies</span>
                    </div>
                    <button type="submit" class="btn btn-primary sub-btn" id="register-btn">Register</button>
                    <p class="already-acc">Already have account? <a href="#login-box" class="signin">Sign in</a> now</p>
                  </form>
            </div>


            <!-- <div class="confrim-code col-8 col-sm-12 col-md-5 offset-2">
              <h2 class="verification">Verification</h2>
              <h4 class="enter-code-text">Enter code for verification</h4>
              <p class="code-p">Code:</p>
          </div> -->
          
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
          $(".register").click(function(){
            $(".other").show();
            $(".content").hide();
            $(".register").addClass('active');
            $(".login").removeClass('active');
           
          });
    
          $(".login").click(function(){
            $(".content").show();
            $(".other").hide();
            $(".login").addClass('active');
            $(".register").removeClass('active');
          });

          $(".signup").click(function(){
            $(".other").show();
            $(".content").hide();
            $(".register").addClass('active');
            $(".login").removeClass('active');
          });

          $(".signin").click(function(){
            $(".content").show();
            $(".other").hide();
            $(".login").addClass('active');
            $(".register").removeClass('active');
          });
    
        });
        



          $.validator.addMethod('emailFormat', function(value, element) {
        return this.optional(element) || (value.match(/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/));
    },
    'Please enter a valid email address.');


     $(function() {
    $("form[name='signupForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertBefore(element);
    },
    wrapper: 'span',
            
    rules: {
      
     username: {
        required:true,

      },
      email: {
        required: true,
        emailFormat: true,
           remote: {
                    url: "{{ env('APP_URL')}}check-email-registration",
                    type: "get",
                  }
      },
      password: {
        required: true,
        minlength: 6,



      },
      confirm_password: {
        required : true,
        equalTo : '#password_reg',


      },
      aggree :{
        required: true,
      },

    },
    messages: {
     
      username: {
        required:"Please Provide a Username",

      },
      email: {
        required: "Please Provide an Email Address",
         remote: "Email already in use!",
      },
      password: {
        required: "Please Provide a Password",
        minlength: "Password Must be at Least 6 Characters Long",
      },
      confirm_password: {
        required : "Please Provide a Confirm Password",
        equalTo:"Password Mismatch",

      },
      aggree:{
        required:"Please Check This Box If You Want To Proceed",
      }
      
    },
    submitHandler: function(form) {

      document.getElementById("register-btn").disabled = true;  
      form.submit();
    }
  });
});





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

      document.getElementById("login-btn").disabled = true;  
      form.submit();
    }
  });
});



    </script>
</body>
</html>