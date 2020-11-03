 <header class="header-desktop3 d-none d-lg-block">
            <div class="section__content section__content--p35">
                <div class="header3-wrap">
                    <div class="header__logo">
                        <a href="#">
                            <img src="{{env('IMG_URL')}}icon/logo-white.png" alt="Logo Here" />
                        </a>
                    </div>
                    <div class="header__navbar">
                        <ul class="list-unstyled">
                           
                            <li class="">
                                <a href="#">
                                    <i class="fas fa-shopping-basket"></i>
                                    <span class="bot-line"></span>POS</a>
                            </li>
                            <li class="{{ Request::is('category-list')? 'active' : ''}}">
                                <a href="{{route('category-list')}}">
                                    <i class="fas fa-shopping-basket"></i>
                                    <span class="bot-line"></span>Categories</a>
                            </li>
                            <li class="{{ Request::is('product-list')? 'active' : ''}}">
                                <a href="{{route('product-list')}}">
                                    <i class="fas fa-shopping-basket"></i>
                                    <span class="bot-line"></span>Products</a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-shopping-basket"></i>
                                    <span class="bot-line"></span>Settings</a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-shopping-basket"></i>
                                    <span class="bot-line"></span>Customers</a>
                            </li>

                           <li>
                                <a href="#">
                                    <i class="fas fa-shopping-basket"></i>
                                    <span class="bot-line"></span>Sales</a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-shopping-basket"></i>
                                    <span class="bot-line"></span>Reports</a>
                            </li>
                            
                           
                        </ul>
                    </div>
                    <div class="header__tool">
                        
                        <div class="account-wrap">
                            <div class="account-item account-item--style2 clearfix js-item-menu">
                                <div class="image">
                                    <img src="{{env('IMG_URL')}}user{{session('login')['user_image']}}" alt="John Doe" />
                                </div>
                                <div class="content">
                                    <a class="js-acc-btn" href="#">{{session('login')['user_name']}}</a>
                                </div>
                                <div class="account-dropdown js-dropdown">
                                    <div class="info clearfix">
                                        <div class="image">
                                            <a href="#">
                                                <img src="{{env('IMG_URL')}}user{{session('login')['user_image']}}" alt="John Doe" />
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
                                            <a href="#">
                                                <i class="zmdi zmdi-account"></i>Account</a>
                                        </div>
                                        <div class="account-dropdown__item">
                                            <a href="#">
                                                <i class="zmdi zmdi-settings"></i>Setting</a>
                                        </div>
                                        <div class="account-dropdown__item">
                                            <a href="#">
                                                <i class="zmdi zmdi-money-box"></i>Billing</a>
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
            </div>
        </header>
        <!-- END HEADER DESKTOP-->

        <!-- HEADER MOBILE-->
        <header class="header-mobile header-mobile-2 d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="index.html">
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
               
                        <li>
                            <a href="$">
                                <i class="fas fa-chart-bar"></i>POS</a>
                        </li>
                        <li class="{{ Request::is('category-list')? 'active' : ''}}">
                            <a href="{{route('category-list')}}">
                                <i class="fas fa-chart-bar"></i>Categories</a>
                        </li>
                        <li class="{{ Request::is('product-list')? 'active' : ''}}">
                            <a href="{{route('product-list')}}">
                                <i class="fas fa-chart-bar"></i>Products</a>
                        </li>
                        <li>
                            <a href="$">
                                <i class="fas fa-chart-bar"></i>Settings</a>
                        </li>
                        <li>
                            <a href="$">
                                <i class="fas fa-chart-bar"></i>Customers</a>
                        </li>
                        <li>
                            <a href="$">
                                <i class="fas fa-chart-bar"></i>Sales</a>
                        </li>
                        <li>
                            <a href="$">
                                <i class="fas fa-chart-bar"></i>Reports</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="sub-header-mobile-2 d-block d-lg-none">
            <div class="header__tool">
                
                <div class="account-wrap">
                    <div class="account-item account-item--style2 clearfix js-item-menu">
                      
                        <div class="content">
                            <a class="js-acc-btn" href="#">john doe</a>
                        </div>
                        <div class="account-dropdown js-dropdown">
                            <div class="info clearfix">
                                <div class="image">
                                    <a href="#">
                                        <img src="{{env('IMG_URL')}}user{{session('login')['user_image']}}" alt="John Doe" />
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
                                    <a href="#">
                                        <i class="zmdi zmdi-account"></i>Account</a>
                                </div>
                                <div class="account-dropdown__item">
                                    <a href="#">
                                        <i class="zmdi zmdi-settings"></i>Setting</a>
                                </div>
                                <div class="account-dropdown__item">
                                    <a href="#">
                                        <i class="zmdi zmdi-money-box"></i>Billing</a>
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