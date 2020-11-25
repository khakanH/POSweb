<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin;
use DB;

class AdminController extends Controller
{

    public function __construct(Request $request)
    {   
    }



    public function Login(Request $request)
    {	
         try 
        {
            $validator = \Validator::make($request->all(), 
                                        ['email' => 'required|email',
                                         'password'    => 'required',
                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            
            $input = $request->all();    

          

            $check_account =Admin::where('email',$input['email'])
                                                        ->first();

            if ($check_account == "") 
            {
                return redirect()->back()->withInput()->with('failed','Sorry, Invalid Email Address');

            }

             if ($check_account->is_blocked == 1) 
                {
                    return redirect()->back()->withInput()->with('failed','Sorry, This Account is Blocked By Admin');

                }


                if (!Hash::check($input['password'],$check_account->password)) 
                {
                    return redirect()->back()->withInput()->with('failed','Sorry, Invalid Password Entered');
                }
                

            $result = array(    
                                "user_id"           => $check_account->id,
                                "user_name"         => $check_account->name,
                                "user_email"        => $check_account->email,
                                "user_type"         => $check_account->user_type,
                           );

            $request->session()->put("admin_login",$result);
            return redirect()->route('admin_dashboard');

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }


    }


     public function Dashboard(Request $request)
    {
        
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            // if (session('admin_login.user_type') ==1) 
            // {
            //     //for admin users
            //       return view('admin.dashboard');
            // }
            // else
            // {
            //     //for other users
            //       return view('admin.dashboard_other');
            // }
            

            return view('admin.dashboard');


        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }


    }


    public function Logout(Request $request)
    {   
        $request->session()->forget(['admin_login']); 
        return redirect()->route('admin_index');
    }




    public function checkUserAvailbility($id,$request)
    {   

       
        $user = Admin::where('id',$id)->where('is_blocked',0)->first();


        if ($user == "") 
        {   
            $request->session()->put("failed","Something went wrong.");
            header('Location:'.url('sign-out'));
            
            exit();
        }
        else
        {   
            return $user;
        }
    }
    


}
    