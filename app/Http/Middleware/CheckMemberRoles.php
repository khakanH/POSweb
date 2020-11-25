<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\MemberRoles;
use App\Models\Modules;

class CheckMemberRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        $module_id = Modules::where('route',\Request::route()->getName())->first();
        if ($module_id == "") 
        {
            return $next($request);
        }
        else
        {
            $check = MemberRoles::where('member_type',session('login.user_type'))->where('module_id',$module_id->id)->first();
            if ($check == "") 
            { 

              if($request->ajax()) 
              {
                  return response()->json(['status'=>"0",'msg' => 'Restricted Page'],403);
                  
              }
              else
              {


                 echo 'Sorry, You\'re not allowed to visit requested page.';

                 ?>
                 <br>
                 <br>
                 <br>
                  <a href="<?php echo route('dashboard'); ?>">Goto Home</a>
                 <?php

                 exit();
              }
            }
            else
            {
                return $next($request);
            }
        }
        

           
    }
}
