<!DOCTYPE html>
<html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials/css')  

      <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">
    <title>Trad Valley</title>
       <!-- All css files link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    </head>
    <body class="">     

      
        <div class="container-fluid">
            <div class="row" style="min-height: 100vh;">
        
                    @include('partials/sidebar')  
        
                    <div class="col-md-9">

                        @yield('content')
                    </div>
            </div>
        </div>

       <!--   <section class="p-t-30 p-b-20 bg-dark">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="copyright">
                                <p>Copyright © 2020 <a href="https://www.vconekt.com" target="_blank"> Vconekt </a>. All rights reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section> -->
 
        @include('partials/js')  
        @include('partials/modals')      
        <!-- @include('partials/footer')       -->


</body>



</html>