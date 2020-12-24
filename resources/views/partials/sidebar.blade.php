

 <div class="col-md-3  side-bar">
            <div class="list-group" id="myList" role="tablist">
                <div class="mb-3 dashboard-logo active-class">
                    <a href="#"><span><img src="" alt="">Logo here</span><i class="fa-icon fa fa-plus"></i></a>
                </div>
                <a class="list-group-item list-group-item-action <?php if (Request::is('dashboard')): ?>
        active
      <?php endif ?>"  href="{{route('dashboard')}}" role="tab">
                    <i class="fa  fa-dashboard"></i><span>Dashboard</span>
                </a>
               




                        <?php

                            $menus = (new App\Helpers\MenuHelper)->getMenu();
                        ?>
                            <hr>

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