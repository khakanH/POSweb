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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    </head>
    <body class="">     

        @if(session('login.is_set_profile') == 1 && session('login.user_type')==0)

<header class="side-bar" style="background-color: #3a3e97; border-bottom: solid black 2px;">
                    <select class="form-control" name="company_list" style="width: 21%;" id="company_list" onchange="ChangeCompany(this.value)">
                        <option selected="" value="{{session('login.company_id')}}">{{session('login.company_name')}}</option>
                    </select>
        </header>

@endif
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

<script type="text/javascript">
     document.addEventListener("DOMContentLoaded", function(event) { 
     setTimeout(GetCompanyList,1000);  
     // setTimeout(NewNotification,1000);  
     // setInterval(NewNotification,15000);  
     // setTimeout(NotificationAlert,1000);  
     // setInterval(NotificationAlert,15000);  
    });

       function GetCompanyList()
    {   
        id = <?php echo session('login.user_id'); ?>;

        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}get-company-list/"+id,
            success: function(data) {
              $('#company_list').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });   
    }

    function ChangeCompany(id)
    {
        $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}change-company/"+id,
            success: function(data) {


                get_status = data['status'];
                get_msg    = data['msg'];

                                if (get_status == "0") 
                                {
                                    alert(get_msg);
                                }
                                else
                                {
                                    location.reload();
                                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });   
    }

</script>

</html>