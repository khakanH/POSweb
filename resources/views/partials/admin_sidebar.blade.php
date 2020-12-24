 <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar2">
            <div class="logo">
                    logo here
            </div>
            <div class="menu-sidebar2__content js-scrollbar1 ps ps--active-y">
                <div class="account2">
                    
                    <a href="{{route('admin_account')}}"><h4 class="name">{{session('admin_login.user_name')}}</h4></a>
                    <a href="{{route('sign-out')}}">Sign out</a>
                </div>
                <nav class="navbar-sidebar2">
                    <ul class="list-unstyled navbar__list">
                        
                       
                        <li>
                            <a href="{{route('admin_dashboard')}}">
                            <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>

                        <li>
                            <a href="{{route('country-list')}}">
                            <i class="fa fa-globe"></i>Countries</a>
                        </li>

                        <li>
                            <a href="{{route('payment-method-list')}}">
                            <i class="fas fa-dollar"></i>Payment Methods</a>
                        </li>

                        <li>
                            <a href="{{route('company-type-list')}}">
                            <i class="fas fa-th-list"></i>Company Types</a>
                        </li>

                        <li>
                            <a href="{{route('members-list')}}">
                            <i class="fas fa-users"></i>Members</a>
                        </li>
                        
                        <li>
                            <a href="{{route('website-modules')}}">
                            <i class="fas fa-th"></i>Website Modules</a>
                        </li>
                       
                      
                    </ul>
                </nav>
           
        </aside>
        <!-- END MENU SIDEBAR-->