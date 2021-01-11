 <style type="text/css">
     @media (min-width:1150px) {
    .d-lg-none {
        display: none!important
    }
    .d-lg-block {
        display: block!important
    }
     }
 </style>

 <header class="header-mobile d-block d-lg-none side-bar-mobile">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                    <center><a href="#"><span><img src="{{env('IMG_URL')}}icon/Tradvalley-Logo-02.png"  width="70" alt=""></span></a></center>

                  @if(session('login.is_set_profile') == 1 && session('login.user_type')==0)
                    <select class="form-control inp" name="company_list-mobile" style="cursor: pointer; min-width: 20%; width: 200px;" id="company_list-mobile" onchange="ChangeCompany(this.value)">
                        <option selected="" value="{{session('login.company_id')}}">{{session('login.company_name')}}</option>
                    </select>
                @endif
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile" style="top: 90px; border: solid black 1px; background: white; padding: 10px;">
               


                        <a class="list-group-item list-group-item-action <?php if (Request::is('dashboard')): ?>
        active
      <?php endif ?>"  href="{{route('dashboard')}}" role="tab">
                    <i class="fa  fa-dashboard"></i><span>Dashboard</span>
                </a>
                        <?php

                            $menus = (new App\Helpers\MenuHelper)->getMenu();
                        ?>


                   
                <a class="list-group-item list-group-item-action <?php if (Request::is('edit-profile')): ?>
        active
      <?php endif ?>"  href="{{route('edit-profile')}}" role="tab">
                    <i class="fa fa-user-circle-o"></i><span>Profile</span>
                </a>
                <a class="list-group-item list-group-item-action" href="{{route('signout')}}" role="tab">
                    <i class="fa fa-sign-out"></i><span>logout</span>
                </a>

                  
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block side-bar">
                    <div class="mb-3 dashboard-logo active-class">
                    <center><a href="#"><span><img src="{{env('IMG_URL')}}icon/Tradvalley-Logo-02.png"  width="100" alt=""></span></a></center>
                </div>

                  @if(session('login.is_set_profile') == 1 && session('login.user_type')==0)
                <hr style="border: solid white 1px;">
                <br>
                    <select class="form-control inp" name="company_list" style="cursor: pointer;" id="company_list" onchange="ChangeCompany(this.value)">
                        <option selected="" value="{{session('login.company_id')}}">{{session('login.company_name')}}</option>
                    </select>
                <br>
                <hr style="border: solid white 1px;">
                <br>
                @endif
            <div class="menu-sidebar__content">
                 <div class="list-group" id="myList" role="tablist">
                    <a class="list-group-item list-group-item-action <?php if (Request::is('dashboard')): ?>
        active
      <?php endif ?>"  href="{{route('dashboard')}}" role="tab">
                    <i class="fa  fa-dashboard"></i><span>Dashboard</span>
                </a>
                        <?php

                            $menus = (new App\Helpers\MenuHelper)->getMenu();
                        ?>


                    <br><br>
                            <hr style="border: solid white 1px;">
                        <br>
                <a class="list-group-item list-group-item-action <?php if (Request::is('edit-profile')): ?>
        active
      <?php endif ?>"  href="{{route('edit-profile')}}" role="tab">
                    <i class="fa fa-user-circle-o"></i><span>Profile</span>
                </a>
                <a class="list-group-item list-group-item-action" href="{{route('signout')}}" role="tab">
                    <i class="fa fa-sign-out"></i><span>logout</span>
                </a>
            </div>
            </div>
        </aside>


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
              $('#company_list-mobile').html(data);
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