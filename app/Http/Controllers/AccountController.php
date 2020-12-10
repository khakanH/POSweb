<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\MembersCompany;
use App\Models\CompanyMemberType;
use App\Models\CompanyInfo;
use App\Models\Countries;

use File;


use App\Traits\CommonTrait;

class AccountController extends Controller
{

    protected $member_model;
    use CommonTrait;

    public function __construct(Request $request)
    {   
        $this->member_model         = new Members();
    }



    

    public function Register(Request $request)
    {

        try{

            $validator = \Validator::make($request->all(), [
                                                                'username'     => 'required',
                                                                'email'     => 'required',
                                                                'password'  => 'required', 
                                                            ]);
            

            if ($validator->fails()) 
            {   
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();
           
           

            $get_email = $this->member_model->where('email',strtolower(trim($input['email'])))->first();

             //check email Already
            if ($get_email != "") 
            {
                return redirect()->back()->withInput()->with('failed','Email Address Already Exist');
            }
           

            $code = rand(1111,9999);
            // $code = 1234;
            $text_notes = "Thank you for Registering on ......";
            $this->SendMailVerification("0",$code,$input['email'],$text_notes);

            $data = array(  
                            'username'          => $input['username'],
                            'email'             => strtolower(trim($input['email'])),
                            'password'          => Hash::make($input['password']),
                            'user_image'        => "user/default_user_icon.png",
                            'verification_code' => $code,
                            'is_verified'       => 0,
                            'is_set_profile'    => 0,
                            'is_blocked'        => 0,
                            'temp_password'     => $input['password'],
                            'member_type'       => 0,
                            'parent_id'         => 0,
                            'created_at'        => date("Y-m-d H:i:s"),
                            'updated_at'        => date("Y-m-d H:i:s"),
                         );


            $id =$this->member_model->insertGetId($data);

            if($id)
            {   
                 $company_info = array(
                                            'name'              => "",
                                            'email'             => "",
                                            'phone'             => "",
                                            'country_id'        => 0,
                                            'default_tax'       => 0,
                                            'default_discount'  => 0,
                                            'receipt_header'    => "",
                                            'receipt_footer'    => "",
                                            'logo'              => "choose_img.png",
                                            'member_id'         => $id,
                                            'fbr_invoice'       => 0,
                                            'created_at'        => date("Y-m-d H:i:s"),
                                            'updated_at'        => date("Y-m-d H:i:s"),

                                         );
                    $company_id = CompanyInfo::insertGetId($company_info);

                        MembersCompany::insert(array(
                            'member_id'     => $id,
                            'company_id'    => $company_id,
                        )); 

                    $title       = "New User Registered. Username: ".$input['username'];
                    $description = $input['username']." has created a new account with Email: ".strtolower(trim($input['email']))." on ".date("d-M-Y h:i a");
                    $this->SaveNotification(1,$id,0,$title,$description,1);


                    $request->session()->put("success","Account Created Successfully!");
                    return view('verify_email',["email"=>strtolower(trim($input['email'])),'verification_type'=>1]);
            }
            else
            {

                return redirect()->back()->withInput()->with('failed','Something went wrong!');
               
            }


        }catch (Exception $e){

            return response()->json($e,500);
        }
    }


    public function SendVerificationCode(Request $request)
    {
        try
        {
            $input = $request->all();
            // $code = 1234;
            $code = rand(1111,9999);
            
            $get_account = $this->member_model->where('email',strtolower(trim($input['email'])))->first();


            if($get_account == "")
            {
                return array("status"=>"0","msg"=>"Sorry, This Email is not Associated with any Account.");
            }               

            if($this->member_model->where('email',strtolower(trim($input['email'])))->update(array('verification_code'=>$code,
                                                                            "is_verified" =>0,

                                                                      )))
            {   


                $username = empty($get_account->username)?"0":$get_account->username;

                $text_notes = "Account Verification.";
                $this->SendMailVerification($username,$code,$get_account->email,$text_notes);
                // $msg = "Your Dazaran Verification Code is";
                // $this->TwilioSendSMS($input['phone'],$msg,$code);
                    return array("status"=>"1","msg"=>"Verification Code Successfully Send to your Email Address");
            }
            else
            {
                return array("status"=>"0","msg"=>"Ops, Something Went Wrong");
            }
           


   
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function CheckVerificationCode(Request $request)
    {
         try
        {
            $input = $request->all();
            


            if($this->member_model->where('email',strtolower(trim($input['email'])))
                                  ->where('verification_code',(int)$input['pin_code'])
                                  ->count() == 1)
                                 
            {   

                $this->member_model->where('email',strtolower(trim($input['email'])))
                                   ->update(array('verification_code'=>0,'is_verified'=>1));


                
                $request->session()->put("success","Email Address Verified Successfully. Now You Can Login.");
                                   

                return array("status"=>"1","msg"=>"Verification Code is Valid.");
            }
            else
            {
                return array("status"=>"0","msg"=>"Verification Code is Invalid.");
            }
           
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


     public function ForgotPassword(Request $request,$email)
    {
        try 
        {
            $input = $request->all();
            $request->session()->put("success","Verification Code Successfully Send to your Email Address.");
                    return view('verify_email',["email"=>$email,'verification_type'=>2]);
            
        } 
        catch (Exception $e) 
        {
            
        }
    }


    public function ResetPassword(Request $request,$email)
    {
        try 
        {
            $input = $request->all();
            $request->session()->put("success","Email Address Verified Successfully. Kindly Reset Your Password.");
            return view('reset_password',["email"=>$email]);
            
        } 
        catch (Exception $e) 
        {
            
        }
    }

    public function SaveNewPassword(Request $request)
    {
        try 
        {
            $input = $request->all();
            
            if(Members::where('email',strtolower(trim($input['email'])))->update(array('password'=>Hash::make($input['new_password']),'temp_password'=>$input['new_password'])))
            {
                $request->session()->put("success","Password Reset Successfully.");
                return redirect()->route('index');
            }
            else
            {
                $request->session()->put("failed","Something Went Wrong Try Again Later.");
                return redirect()->route('index');
            }


        } 
        catch (Exception $e) 
        {
            
        }
    }


    public function Login(Request $request)
    {
         try 
        {
            $validator = \Validator::make($request->all(), 
                                        [
                                            'password'    => 'required',
                                            'email'       => 'required',
                                         
                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            
            $input = $request->all();    





           
                $check_account = $this->member_model->where('email',strtolower(trim($input['email'])))
                                                    ->first();
                if ($check_account == "") 
                {
                    return redirect()->back()->withInput()->with('failed','Sorry, This Email Address is not associated with any account');

                }

           
                if ($check_account->is_blocked == 1) 
                {
                    return redirect()->back()->withInput()->with('failed','Sorry, Your Account Has Been Blocked');

                }


                if (!Hash::check($input['password'],$check_account->password)) 
                {
                    return redirect()->back()->withInput()->with('failed','Sorry, Invalid Password Entered');
                }
                
                if ($check_account->is_verified == 0) 
                {
                    $code = rand(1111,9999);
                    // $code = 1234;
                    $username = empty($check_account->username)?"0":$check_account->username;

                    $text_notes = "Account Verification.";
                    $this->SendMailVerification($username,$code,$check_account->email,$text_notes);
                                   
                    $this->member_model->where('id',$check_account->id)->update(array('verification_code'=>$code));


                    $request->session()->put('failed','Kindly Verify your Account First. Verification code has been successfully sent to your Email Address.');

                    return view('verify_email',["email"=>$check_account->email,"verification_type"=>1]);
                }


           
            
            $company_id  = MembersCompany::where('member_id',$check_account->id)->first();
           

            $result = array(    
                                "user_id"           => $check_account->id,
                                "user_name"         => $check_account->username,
                                "user_email"        => $check_account->email,
                                "user_image"        => $check_account->user_image,
                                "user_type"         => $check_account->member_type,
                                "company_id"        => $company_id->company_id,
                                "company_name"      => $company_id->company_name->name,
                                "is_set_profile"    => $check_account->is_set_profile,
                           );

            $request->session()->put("login",$result);
            return redirect("dashboard");

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function CountryNameList(Request $request)
    {
        try 
        {
            $user_id = isset(session("login")['user_id'])?session("login")['user_id']:0;

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $country  =Countries::where('is_show',1)->get();

            foreach ($country as $key) 
            {
                ?>

                    <option value="<?php echo $key['id'] ?>"><?php echo $key['name'] ?></option>

                <?php
            }

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }    

    public function Settings(Request $request)
    {
        try 
        {
            $user_id = isset(session("login")['user_id'])?session("login")['user_id']:0;

            $user_info = $this->checkUserAvailbility($user_id,$request);

            // $country  =Countries::where('is_show',1)->get();
            $company  = CompanyInfo::where('member_id',$user_id)->get();

            return view('settings',compact('company'));

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function AddCompanyInfo(Request $request)
    {
        try 
        {
            $user_id = isset(session("login")['user_id'])?session("login")['user_id']:0;

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $input = $request->all();


            if (empty($input['company_id'])) 
            {

                $image= $request->file('company_logo');
            
                if (empty($image)) 
                {

                    $path = "choose_img.png";
                    
                }
                else
                {

                    $input['imagename'] =  uniqid().'.webp';
                   
                    $destinationPath = public_path('/images/companylogo');

                    if($image->move($destinationPath, $input['imagename']))
                    {
                            $path =  'companylogo/'.$input['imagename'];
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Image Uploading");
                    }

                }
                

                $result = CompanyInfo::insert(array(
                                        'name'              => $input['company_name'],
                                        'email'             => strtolower(trim($input['company_email'])),
                                        'phone'             => $input['company_phone'],
                                        'logo'              => $path,
                                        'country_id'        => $input['company_country'],
                                        'default_discount'  => isset($input['company_default_discount'])?$input['company_default_discount']:0,
                                        'default_tax'       => isset($input['company_default_tax'])?$input['company_default_tax']:0,
                                        'receipt_header'    => $input['company_receipt_header'],
                                        'receipt_footer'    => $input['company_receipt_footer'],
                                        'fbr_invoice'       => isset($input['company_fbr'])?$input['company_fbr']:0,
                                        'pos_id'            => isset($input['company_pos_id'])?$input['company_pos_id']:"",
                                        'member_id'         => $user_id,
                                        'created_at'        => date("Y-m-d H:i:s"),
                                        'updated_at'        => date("Y-m-d H:i:s"),
                                     ));

                if ($result) 
                {
                    
                    Members::where('id',$user_id)->update(array('is_set_profile'=>1));

                    $request->session()->put("login.is_set_profile",1);  


                    return redirect()->route('settings')->with("success","Company Information Saved Successfully");
                }
                else
                {
                    return redirect()->route('settings')->with("failed","Filed To Save Company Information");

                }



            }
            else
            {
                $old_logo = CompanyInfo::where('id',$input['company_id'])->first();

                $image= $request->file('company_logo');
                if (empty($image)) 
                {

                    $path = $old_logo->logo;
                    
                }
                else
                {

                    if ($old_logo->logo != "choose_img.png") 
                    {
                        $image_path = public_path('images/'.$old_logo->logo);  // Value is not URL but directory file path

                        if(File::exists($image_path)) {
                            File::delete($image_path);
                        }
                    }

                    $input['imagename'] =  uniqid().'.webp';
                   
                    $destinationPath = public_path('/images/companylogo');

                    if($image->move($destinationPath, $input['imagename']))
                    {
                            $path =  'companylogo/'.$input['imagename'];
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Image Uploading");
                    }

                }

                $result = CompanyInfo::where('id',$input['company_id'])
                                     ->update(array(
                                        'name'              => $input['company_name'],
                                        'email'             => strtolower(trim($input['company_email'])),
                                        'phone'             => $input['company_phone'],
                                        'logo'              => $path,
                                        'country_id'        => $input['company_country'],
                                        'default_discount'  => isset($input['company_default_discount'])?$input['company_default_discount']:0,
                                        'default_tax'       => isset($input['company_default_tax'])?$input['company_default_tax']:0,
                                        'receipt_header'    => $input['company_receipt_header'],
                                        'receipt_footer'    => $input['company_receipt_footer'],
                                        'fbr_invoice'       => isset($input['company_fbr'])?$input['company_fbr']:0,
                                        'pos_id'            => isset($input['company_pos_id'])?$input['company_pos_id']:"",
                                     ));

                if ($result) 
                {
                    
                    Members::where('id',$user_id)->update(array('is_set_profile'=>1));
                    


                    $request->session()->put("login.is_set_profile",1);                

                    if ($input['company_id'] == session('login.company_id')) 
                    {
                        $request->session()->put("login.company_name",$input['company_name']);              
                    }

                    return redirect()->route('settings')->with("success","Company Information Saved Successfully");
                }
                else
                {
                    return redirect()->route('settings')->with("failed","Filed To Save Company Information");

                }
            }





             

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }



    public function EditProfile(Request $request)
    {
        try 
        {
            $user_id = session("login")['user_id'];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            return view('edit_profile',compact('user_info'));

        } 
        catch (Exception $e) 
        {
            
        }
    }

     public function SaveProfile(Request $request)
    {
        try 
        {
            $user_id = session("login")['user_id'];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $input = $request->all();

            $image= $request->file('profile_image');
            if (empty($image)) 
            {

                $path = $user_info['user_image'];
                
            }
            else
            {

                if ($user_info['user_image'] != "user/default_user_icon.png") 
                {
                    $image_path = public_path('images/'.$user_info['user_image']);  // Value is not URL but directory file path

                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }

                $input['imagename'] =  uniqid().'.webp';
               
                $destinationPath = public_path('/images/user');

                if($image->move($destinationPath, $input['imagename']))
                {
                        $path =  'user/'.$input['imagename'];
                }
                else
                {
                        return redirect()->back()->withInput()->with("failed","Something Went Wrong for Image Uploading");
                }

            }
             

            $result = Members::where('id',$user_id)
                                 ->update(array(
                                    'username'              => $input['name'],
                                    'user_image'        => $path,
                                 ));

            if ($result) 
            {

                $request->session()->put("login.user_name",$input['name']);                
                $request->session()->put("login.user_image",$path);                


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

            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $input = $request->all();
            
            $new_email_add = strtolower(trim($input['new_email']));

            if (!Hash::check($input['password'],$user_info->password)) 
            {
                return array("status"=>"0","msg"=>"Sorry, Invalid Account Password Entered.");
            }
            else
            {
                if (Members::where('email',$new_email_add)->count() != 0) 
                {
                    return array("status"=>"0","msg"=>"Sorry, This Email Address (".$new_email_add.") Already Exist.");
                }
                else
                {   
                    $code = rand(1111,9999);
                    // $code = 1234;
                    $username = empty($user_info->username)?"0":$user_info->username;
                    $text_notes = "Change Email Address Verification.";
                    $this->SendMailVerification($username,$code,$new_email_add,$text_notes);
                   
                    if(Members::where('id',$user_id)->update(array('email'=>$new_email_add,'is_verified'=>0,'verification_code'=>$code)))
                    {   
                        $request->session()->forget(['login']); 
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

    public function VerifyEmail(Request $request,$email)
    {
        try 
        {
            $input = $request->all();
                        $request->session()->put("success","Eamil Address Updated Successfully. Kindly Verify Your New Email Address.");
                    return view('verify_email',["email"=>$email,'verification_type'=>1]);
            
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

            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $input = $request->all();
            
            if (!Hash::check($input['current_pass'],$user_info->password)) 
            {
                return array("status"=>"0","msg"=>"Sorry, Invalid Current Password Entered.");
            }
            else
            {
                if (Members::where('id',$user_id)->update(array('password'=>Hash::make($input['new_pass']),'temp_password'=>$input['new_pass']))) 
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


    public function GetCompanyList(Request $request,$id)
    {
        try 
        {

            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $company = CompanyInfo::where('member_id',$id)->get();

            foreach ($company as $key) 
            {
                ?>
                <option <?php if (session('login.company_id') == $key['id']): ?>
                    selected
                <?php endif ?> value="<?php echo $key['id'] ?>"><?php echo $key['name'] ?></option>
                <?php 
            }

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
            
        }
    
    }


    public function GetCompanyInfo(Request $request,$id)
    {
        try 
        {

            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $country  =Countries::where('is_show',1)->get();
            $company = CompanyInfo::where('id',$id)->first();

            ?>
                        <input type="hidden" id="company_id" name="company_id" value="<?php echo $id; ?>">

                                        <div class="row form-group">
                                          <div class="col-lg-6">
                                            <label for="name" class=" form-control-label">Company Name:</label>
                                            <input type="text" id="company_name" name="company_name" placeholder="Enter your company name" value="<?php echo $company->name; ?>" class="form-control">
                                          </div>
                                          <div class="col-lg-6">
                                            <label for="phone" class=" form-control-label">Company Phone:</label>
                                            <input type="text" id="company_phone" name="company_phone" placeholder="Enter your company phone" value="<?php echo $company->phone; ?>" class="form-control">
                                          </div>
                                        </div>

                                        <div class="row form-group">
                                          <div class="col-lg-6">
                                            <label for="email" class=" form-control-label">Company Email:</label>
                                            <input type="email" id="company_email" name="company_email" placeholder="Enter your company email address" value="<?php echo $company->email; ?>" class="form-control">
                                          </div>
                                          <div class="col-lg-6">
                                            <label for="country" class=" form-control-label">Company Country:</label>
                                            <select class="form-control" name="company_country" id="company_country">
                                              <option value="" disabled="" selected="">Select Country:</option>
                                              <?php foreach($country as $key): ?>
                                                <option <?php if ($company->country_id == $key['id']): ?>
                                                    selected
                                                <?php endif ?> value="<?php echo $key['id']; ?>"><?php echo $key['name']; ?></option>
                                              <?php endforeach; ?>
                                            </select>
                                          </div>
                                        </div>

                                        <div class="row form-group">
                                          <div class="col-lg-6">
                                            <label for="discount" class=" form-control-label">Default Discount %</label>
                                            <input type="number" id="company_default_discount" name="company_default_discount" placeholder="Enter your company default discount" value="<?php echo $company->default_discount; ?>" min="0" max="100" class="form-control">
                                          </div>
                                          <div class="col-lg-6">
                                            <label for="tax" class=" form-control-label">Default Tax %</label>
                                            <input type="number" id="company_default_tax" name="company_default_tax" placeholder="Enter your company default tax" value="<?php echo $company->default_tax; ?>" min="0" max="100" class="form-control">
                                          </div>
                                        </div>

                                        <div class="row form-group" style="background: #333; border: solid gray 2px; border-radius: 3px; color: white; padding: 25px;">
                                          <div class="col-lg-6">
                                            <label class="form-control-label">FBR Invoice Data:</label>
                                            &nbsp;&nbsp;&nbsp;
                                            <label class="switch switch-3d switch-primary switch-lg mr-3">
                                              <input onclick="FBRToggle(this.value)" type="checkbox" class="switch-input" id="company_fbr" name="company_fbr" <?php if ($company->fbr_invoice ==1): ?>
                                                  checked
                                              <?php endif ?> value="<?php echo $company->fbr_invoice; ?>">
                                              <span class="switch-label"></span>
                                              <span class="switch-handle"></span>
                                            </label>
                                          </div>
                                          <div class="col-lg-6" id="company_pos_id_div" <?php if ($company->fbr_invoice ==1): ?>
                                            style="display: block;"
                                          <?php else: ?>
                                            style="display: none;"
                                          
                                          <?php endif ?> >

                                            
                                            <label class="form-control-label" style="">POS ID:</label>&nbsp;&nbsp;&nbsp;
                                            <input  type="text" class="form-control" name="company_pos_id" id="company_pos_id" value="<?php echo $company->pos_id; ?>">


                                          </div>
                                        </div>

                                        <div class="row form-group">
                                          <div class="col-lg-12">
                                            <label for="company_logo_output" class="form-control-label">Company Logo:</label><br>
                                            <img id="company_logo_output" src="<?php echo  env('IMG_URL'); ?><?php echo $company->logo; ?>" width="130" height="130" style="border-radius: 2%; border: solid gray 1px; object-position: top; object-fit: cover;">&nbsp;&nbsp;&nbsp;<input type="file" onchange="logo_loadFile(event)" onclick="clearImage()"   name="company_logo" id="company_logo" accept="image/*" >
                                          </div>
                                        </div>

                                        <div class="row form-group">
                                          <div class="col-lg-12">
                                            <label for="receipt_header" class="form-control-label">Text in the Receipt Header:</label>
                                            <!-- <div id="editor">
                                            <div id='edit' style="margin-top: 10px;"><p id="receipt_header">{!! $company->receipt_header !!}</p>
                                            </div>
                                            </div> -->
                                            <textarea id="company_receipt_header" placeholder="Enter receipt header text" name="company_receipt_header" class="form-control" rows="4"><?php echo $company->receipt_header; ?></textarea>
                                          </div>
                                        </div>

                                        <div class="row form-group">
                                          <div class="col-lg-12">
                                            <label for="receipt_footer" class="form-control-label">Text in the Receipt Footer:</label>
                                            <input type="text" id="company_receipt_footer" name="company_receipt_footer" placeholder="Enter receipt footer text" value="<?php echo $company->receipt_footer; ?>" class="form-control">
                                            
                                          </div>
                                        </div>

            <?php

        } 
        catch (Exception $e) 
        {
            
            return response()->json($e,500);
        }
    
    }


     public function ChangeCompany(Request $request,$id)
    {
        try 
        {

            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $company = CompanyInfo::where('id',$id)->first();

            if ($company == "0") 
            {
                return array("status"=>"0","msg"=>"Ops, Something Went Wrong, Try Again Later");
            }
            else
            {
                    $request->session()->put("login.company_id",$id);                
                    $request->session()->put("login.company_name",$company->name);

                return array("status"=>"1","msg"=>"success");

            }



        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
            
        }
    
    }






    public function SignOut(Request $request)
    {   
        $request->session()->forget(['login']); 
        return redirect()->route('index');
    }






    
    public function checkUserAvailbility($id,$request)
    {   

        $user = Members::where('id',$id)->where('is_blocked',0)->first();


        if ($user == "") 
        {   
            $request->session()->put("failed","Something went wrong.");
            header('Location:'.url('signout'));
            
            exit();
        }
        else
        {   
            return $user;
        }
    }

}
    