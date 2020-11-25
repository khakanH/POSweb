<!DOCTYPE html>
<html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials/css')  

      <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">
    <title>Web POS</title>
       <!-- All css files link -->
    <script src="{{asset('vendor/jquery-3.2.1.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    </head>
    <body class="animsition">     
        @include('partials/admin_sidebar')  
                    


        <div class="page-container2">
            @include('partials/admin_header')  
            @yield('content')
        </div>

       
         <section class="p-t-30 p-b-20 bg-dark">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="copyright">
                                <p>Copyright © 2020 <a href="https://www.vconekt.com" target="_blank"> Vconekt </a>. All rights reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
 
        @include('partials/js')  
        @include('partials/modals')      
        @include('partials/admin_modals')      
        <!-- @include('partials/footer')       -->


</body>

</html>