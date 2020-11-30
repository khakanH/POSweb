<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Members;
use App\Models\CompanyInfo;
use DB;

use App\Traits\CommonTrait;

class AdminController extends Controller
{
    use CommonTrait;
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


            $member_count = array();
            for ($i=1; $i <= 12  ; $i++) 
            {   
                $member_count[] = Members::whereMonth('created_at',$i)->count();
            }

            $company_count = array();
            for ($i=1; $i <= 12  ; $i++) 
            {   
                $company_count[] = CompanyInfo::whereMonth('created_at',$i)->count();
            }
            

            return view('admin.dashboard',compact("member_count","company_count"));


        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }


    }









    public function Account(Request $request)
    {
        
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            return view('admin.account',compact("user_info"));


        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }


    }


    public function SaveProfile(Request $request)
    {
        try 
        {
            $user_id = session("admin_login")['user_id'];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $input = $request->all();

             

            $result = Admin::where('id',$user_id)
                                 ->update(array(
                                    'name'              => $input['name'],
                                 ));

            if ($result) 
            {

                $request->session()->put("admin_login.user_name",$input['name']);                


                return array("status"=>"1","msg"=>"Profile Information Saved Successfully.");
            }
            else
            {
                return array("status"=>"0","msg"=>"Failed.");

            }
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }



    public function ChangeEmailAddressCheck(Request $request)
    {
        try 
        {

            $user_id = session("admin_login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $input = $request->all();
            
            $new_email_add = strtolower(trim($input['new_email']));

            if (!Hash::check($input['password'],$user_info->password)) 
            {
                return array("status"=>"0","msg"=>"Sorry, Invalid Account Password Entered.");
            }
            else
            {
                if (Admin::where('email',$new_email_add)->count() != 0) 
                {
                    return array("status"=>"0","msg"=>"Sorry, This Email Address (".$new_email_add.") Already Exist.");
                }
                else
                {   
                   
                    if(Admin::where('id',$user_id)->update(array('email'=>$new_email_add)))
                    {   
                        return array("status"=>"1","msg"=>"Email Address Updated Successfully");
                    }
                }
            }



        } 
        catch (Exception $e) 
        {
            
        }
    
    }


    public function ChangePassword(Request $request)
    {
        try 
        {

            $user_id = session("admin_login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $input = $request->all();
            
            if (!Hash::check($input['current_pass'],$user_info->password)) 
            {
                return array("status"=>"0","msg"=>"Sorry, Invalid Current Password Entered.");
            }
            else
            {
                if (Admin::where('id',$user_id)->update(array('password'=>Hash::make($input['new_pass'])))) 
                {
                    return array("status"=>"1","msg"=>"Password Updated Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Ops, Something Went Wrong, Try Again Later");
                }
            }



        } 
        catch (Exception $e) 
        {
            
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
    