<?php
namespace App\Helpers;

use App\Models\MemberRoles;
use App\Models\Modules;

use DB;
class MenuHelper{

	

  	function getMenu()
  	{	
 		$get_module_id = MemberRoles::where('member_type',session('login.user_type'))->pluck('module_id');

 		$get_module = Modules::whereIn('id',$get_module_id)->where('parent_id',0)->get();


 		foreach ($get_module as $key) 
 		{
 			?>
 			
 			<li class="<?php if (\Request::route()->getName() == $key['route']): ?>
 				active
 			<?php endif ?>">
                <a href="<?php echo route($key['route']) ?>">
                    <i class="<?php echo $key['icon'] ?>"></i>
                   	<span class="bot-line"></span><?php echo $key['name']; ?>
                </a>
            </li>
 			
 			<?php
 		}

	}

	
}
