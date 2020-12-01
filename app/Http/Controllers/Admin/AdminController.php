<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Members;
use App\Models\CompanyInfo;
use App\Models\Notification;
use DB;
use Illuminate\Foundation\Inspiring;

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

            $notifications = Notification::where('receiver_type',1)->where('is_new',1)->where('is_deleted',0)->limit(3)->orderBy('created_at','desc')->get();

            $member_count = array();
            for ($i=1; $i <= 12  ; $i++) 
            {   
                $member_count[] = Members::whereMonth('created_at',$i)->whereYear('created_at',date("Y"))->count();
            }

            $company_count = array();
            for ($i=1; $i <= 12  ; $i++) 
            {   
                $company_count[] = CompanyInfo::whereMonth('created_at',$i)->whereYear('created_at',date("Y"))->count();
            }

            $quote = Inspiring::quote();

            
            return view('admin.dashboard',compact("member_count","company_count","notifications","quote"));


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
            
            return response()->json($e,500);
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
            return response()->json($e,500);
            
        }
    
    }








    public function MarkNotificationRead(Request $request,$id)
    {
        try 
        {
            $user_id = session("admin_login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            Notification::where('id',$id)->update(array('is_new'=>0));

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
            
        }
    
    }


    public function NewNotification(Request $request)
    {
        try 
        {
            $user_id = session("admin_login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $notifications = Notification::where('receiver_type',1)->where('is_new',1)->where('is_deleted',0)->limit(3)->orderBy('created_at','desc')->get();
            $quote = Inspiring::quote();

            if(count($notifications) == 0):
            ?>
            <br>
            <br>
            <center><span class="fa fa-quote-left" style="position: absolute;
    margin: 3px 0px 0px -20px;"></span><i class="tx-26"><?php echo $quote ?></i><span class="fa fa-quote-right" style="position: absolute;
    margin: 3px 0px 0px 10px;"></span></center>
            <?php 
                endif; 
            
                foreach($notifications as $key):
                    $alert_color = ($key['type'] == 1)?"primary": (($key['type'] == 2)?"warning":(($key['type'] == 3)?"danger":"secondary"));
            ?>
            <div class="sufee-alert alert with-close alert-<?php echo $alert_color; ?> alert-dismissible fade show">
                  <?php echo $key['title']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick='MarkNotificationRead("<?php echo $key['id']; ?>")'>
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            <?php endforeach;

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
            
        }
    
    }


    public function SendNotificationToUsers(Request $request)
    {
        try 
        {
            $user_id = session("admin_login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $input = $request->all();

            if($this->SaveNotification(2,$user_id,$input['receiver_id'],$input['notifi_title'],$input['notifi_description'],$input['notifi_type']))
            {
                    return array("status"=>"1","msg"=>"Notification Send Successfully.");
            }
            else
            {
                    return array("status"=>"0","msg"=>"Failed To Send Notification.");
            }
           
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
    