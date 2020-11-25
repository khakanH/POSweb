 <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar2">
            <div class="logo">
                <a href="#">
                    logo here
                </a>
            </div>
            <div class="menu-sidebar2__content js-scrollbar1 ps ps--active-y">
                <div class="account2">
                    
                    <h4 class="name">{{session('admin_login.user_name')}}</h4>
                    <a href="{{route('sign-out')}}">Sign out</a>
                </div>
                <nav class="navbar-sidebar2">
                    <ul class="list-unstyled navbar__list">
                        
                       
                        <li>
                            <a href="#">
                            <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>

                        <li>
                            <a href="">
                            <i class="fas fa-circle"></i>Countries</a>
                        </li>

                        <li>
                            <a href="">
                            <i class="fas fa-circle"></i>Payment Methods</a>
                        </li>

                        <li>
                            <a href="{{route('members-list')}}">
                            <i class="fas fa-users"></i>Members</a>
                        </li>

                        <!-- <li>
                            <a href="#">
                            <i class="fas fa-user"></i>User</a>
                        </li> -->
                       
                      
                    </ul>
                </nav>
           
        </aside>
        <!-- END MENU SIDEBAR-->