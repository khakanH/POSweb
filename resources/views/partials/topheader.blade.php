



@if(session('login.is_set_profile') == 1 && session('login.user_type')==0)

<header class="header-desktop4" style="background-color: #000000;
background-image: linear-gradient(147deg, #000000 0%, #04619f 74%);color: #000;">
            <div class="container">
                <div class="header4-wrap">
                    <div class="header__logo" style="width: 100%;">
                    <select class="form-control" name="company_list" style="width: 21%;" id="company_list" onchange="ChangeCompany(this.value)">
                        <option selected="" value="{{session('login.company_id')}}">{{session('login.company_name')}}</option>
                    </select>
                    </div>
                    
                </div>
            </div>
        </header>

@endif




















 <header class="header-desktop3 d-none d-lg-block">
            <div class="section__content section__content--p35">
                <div class="header3-wrap">
                    <div class="header__logo">
                        <a href="{{route('dashboard')}}">
                            <img src="{{env('IMG_URL')}}icon/logo-white.png" alt="Logo Here" />
                        </a>
                    </div>
                    <div class="header__navbar">
                        <ul class="list-unstyled">
                           












                        <?php

                            $menus = (new App\Helpers\MenuHelper)->getMenu();
                        ?>
                            














                            
                           
                        </ul>
                    </div>
                    <div class="header__tool">
                        
                        <div class="account-wrap">
                        <div id="bell_icon" class="header-button-item js-item-menu">
                            <i class="zmdi zmdi-notifications"></i>
                            <div class="notifi-dropdown notifi-dropdown--no-bor js-dropdown" >
                                
                                <div id="notifications-div" style="max-height: 400px; overflow: auto;">
                                   &nbsp;
                                    <br>
                                <!-- <div class="notifi__footer">
                                    <a href="#">All notifications</a>
                                </div> -->
                                </div>
                            </div>
                        </div>
                            <div class="account-item account-item--style2 clearfix js-item-menu">
                       
                                <div class="image">
                                    <img style="min-width: 50px; min-height: 50px; " src="{{env('IMG_URL')}}{{session('login')['user_image']}}" alt="John Doe" />
                                </div>
                                <div class="content">
                                    <a class="js-acc-btn" href="#">{{explode(" ",session('login')['user_name'])[0]}}</a>
                                </div>
                                <div class="account-dropdown js-dropdown">
                                    <div class="info clearfix">
                                        <div class="image">
                                            <a href="#">
                                                <img style="min-width: 70px; min-height: 70px; " src="{{env('IMG_URL')}}{{session('login')['user_image']}}" alt="John Doe" />
                                            </a>
                                        </div>
                                        <div class="content">
                                            <h5 class="name">
                                                <a href="#">{{explode(" ",session('login')['user_name'])[0]}}</a>
                                            </h5>
                                            <span class="email">{{session('login')['user_email']}}</span>


                                        </div>
                                    </div>



                                    <div class="account-dropdown__body">

                                         
                                        <div class="account-dropdown__item">
                                            <a href="{{route('edit-profile')}}">
                                                <i class="zmdi zmdi-account"></i>Account</a>
                                        </div>
                                        <!-- <div class="account-dropdown__item">
                                            <a href="#">
                                                <i class="zmdi zmdi-settings"></i>Setting</a>
                                        </div>
                                        <div class="account-dropdown__item">
                                            <a href="#">
                                                <i class="zmdi zmdi-money-box"></i>Billing</a>
                                        </div> -->
                                    </div>
                                    <div class="account-dropdown__footer">
                                        <a href="{{route('signout')}}">
                                            <i class="zmdi zmdi-power"></i>Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- END HEADER DESKTOP-->

        <!-- HEADER MOBILE-->
        <header class="header-mobile header-mobile-2 d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="{{route('dashboard')}}">
                            <img src="{{env('IMG_URL')}}icon/logo-white.png" alt="CoolAdmin" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
               
                            











                           <?php

                            $menus = (new App\Helpers\MenuHelper)->getMenu();
                        ?>















                    </ul>
                </div>
            </nav>
        </header>
        <div class="sub-header-mobile-2 d-block d-lg-none">
            <div class="header__tool">
                
                <div class="account-wrap">
                     <div id="bell_icon_mob-dev" class="header-button-item js-item-menu">
                            <i class="zmdi zmdi-notifications"></i>
                            <div class="notifi-dropdown notifi-dropdown--no-bor js-dropdown" >
                                
                                <div id="notifications-div_mob-dev" style="max-height: 400px; overflow: auto;">
                                    <br>
                                    <center><p class="tx-danger tx-16">No New Notification Found</p></center>
                                    <br>
                                <!-- <div class="notifi__footer">
                                    <a href="#">All notifications</a>
                                </div> -->
                                </div>
                            </div>
                        </div>
                    <div class="account-item account-item--style2 clearfix js-item-menu">
                      
                        <div class="content">
                            <a class="js-acc-btn" href="#">{{session('login.user_name')}}</a>
                        </div>
                        <div class="account-dropdown js-dropdown">
                            <div class="info clearfix">
                                <div class="image">
                                    <a href="#">
                                        <img src="{{env('IMG_URL')}}{{session('login')['user_image']}}" alt="John Doe" />
                                    </a>
                                </div>
                                <div class="content">
                                    <h5 class="name">
                                        <a href="#">{{session('login')['user_name']}}</a>
                                    </h5>
                                    <span class="email">{{session('login')['user_email']}}</span>
                                </div>
                            </div>
                            <div class="account-dropdown__body">
                                <div class="account-dropdown__item">
                                    <a href="{{route('edit-profile')}}">
                                    <i class="zmdi zmdi-account"></i>Account</a>
                                </div>
                            </div>
                            <div class="account-dropdown__footer">
                                <a href="{{route('signout')}}">
                                    <i class="zmdi zmdi-power"></i>Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>





<script type="text/javascript">
    function NewNotification()
    {
      $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}new-notifications-users",
            success: function(data) {
            
              $('#notifications-div_mob-dev').html(data);
              $('#notifications-div').html(data);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
          });
    }


    function NotificationAlert()
    {
      $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}notification-alert",
            success: function(data) {

              get_status = data['status'];

              if (get_status == "0") 
              {
                document.getElementById("bell_icon").classList.remove("has-noti");
                document.getElementById("bell_icon_mob-dev").classList.remove("has-noti");
              }
              else
              {
                document.getElementById("bell_icon").classList.add("has-noti");
                document.getElementById("bell_icon_mob-dev").classList.add("has-noti");
              }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
          });
    }

    document.addEventListener("DOMContentLoaded", function(event) { 
     setTimeout(GetCompanyList,1000);  
     setTimeout(NewNotification,1000);  
     setInterval(NewNotification,15000);  
     setTimeout(NotificationAlert,1000);  
     setInterval(NotificationAlert,15000);  
    });

    function MarkNotificationRead(id)
    {
      $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}mark-notification-read-user/"+id,
        success: function(data) {
            document.getElementById("newNotifiDot"+id).style.display = "none";
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }

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