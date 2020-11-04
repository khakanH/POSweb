<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\CompanyInfo;
use App\Models\Countries;

use File;

class AccountController extends Controller
{

    protected $member_model;

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
           
           

            $get_email = $this->member_model->where('email',$input['email'])->first();

             //check email Already
            if ($get_email != "") 
            {
                return redirect()->back()->withInput()->with('failed','Email Address Already Exist');
            }
           

            // $code = rand(1111,9999);
            $code = 1234;
            $text_notes = "Thank you for Registering on ......";
            // $this->SendMailVerification("0",$code,$input['email'],$text_notes);

            $data = array(  
                            'username'          => $input['username'],
                            'email'             => $input['email'],
                            'password'          => Hash::make($input['password']),
                            'user_image'        => "/default_user_icon.png",
                            'verification_code' => $code,
                            'is_verified'       => 0,
                            'is_set_profile'    => 0,
                            'is_blocked'        => 0,
                            'temp_password'     => $input['password'],
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
                                            'created_at'        => date("Y-m-d H:i:s"),
                                            'updated_at'        => date("Y-m-d H:i:s"),

                                         );
                    CompanyInfo::insert($company_info);

                    $request->session()->put("success","Account Created Successfully!");
                    return view('verify_email',["email"=>$input['email'],'verification_type'=>1]);
            }
            else
            {

                return redirect()->route('signup')->with('failed','Something went wrong!');
               
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
            $code = 1234;
            // $code = rand(1111,9999);
            
            $get_account = $this->member_model->where('email',$input['email'])->first();


            if($get_account == "")
            {
                return array("status"=>"0","msg"=>"Sorry, This Email is not Associated with any Account.");
            }               

            if($this->member_model->where('email',$input['email'])->update(array('verification_code'=>$code,
                                                                            "is_verified" =>0,

                                                                      )))
            {   


                $username = empty($get_account->username)?"0":$get_account->username;

                $text_notes = "Account Verification.";
                // $this->SendMailVerification($username,$code,$get_account->email,$text_notes);
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
            


            if($this->member_model->where('email',$input['email'])
                                  ->where('verification_code',(int)$input['pin_code'])
                                  ->count() == 1)
                                 
            {   

                $this->member_model->where('email',$input['email'])
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





           
                $check_account = $this->member_model->where('email',$input['email'])
                                                    ->first();
                if ($check_account == "") 
                {
                    return redirect()->back()->withInput()->with('failed','Sorry, This Email Address is not associated with any account');

                }

           



                if (!Hash::check($input['password'],$check_account->password)) 
                {
                    return redirect()->back()->withInput()->with('failed','Sorry, Invalid Password Entered');
                }
                
                if ($check_account->is_verified == 0) 
                {
                    // $code = rand(1111,9999);
                    $code = 1234;
                    $username = empty($check_account->username)?"0":$check_account->username;

                    $text_notes = "Account Verification.";
                    // $this->SendMailVerification($username,$code,$check_account->email,$text_notes);
                                   
                    $this->member_model->where('id',$check_account->id)->update(array('verification_code'=>$code));


                    $request->session()->put('failed','Kindly Verify your Account First. Verification code has been successfully sent to your Email Address.');

                    return view('verify_email',["email"=>$check_account->email,"verification_type"=>1]);
                }



           

            $result = array(    
                                "user_id"           => $check_account->id,
                                "user_name"         => $check_account->username,
                                "user_email"        => $check_account->email,
                                "user_image"        => $check_account->user_image,
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




    public function Settings(Request $request)
    {
        try 
        {
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $country  =Countries::where('is_show',1)->get();
            $company  = CompanyInfo::where('member_id',$user_id)->first();

            return view('settings',compact('company','country'));

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
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $input = $request->all();

            $old_logo = CompanyInfo::where('member_id',$user_id)->first();

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
             

            $result = CompanyInfo::where('member_id',$user_id)
                                 ->update(array(
                                    'name'              => $input['name'],
                                    'email'             => $input['email'],
                                    'phone'             => $input['phone'],
                                    'logo'             =>  $path,
                                    'country_id'        => $input['country'],
                                    'default_discount'  => $input['discount'],
                                    'default_tax'       => $input['tax'],
                                    'receipt_header'    => $input['receipt_header'],
                                    'receipt_footer'    => $input['receipt_footer'],
                                 ));

            if ($result) 
            {
                    return array("status"=>"1","msg"=>"Company Information Saved Successfully.");
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









    public function SignOut(Request $request)
    {   
        $request->session()->forget(['login']); 
        return redirect()->route('index');
    }






    
    public function checkUserAvailbility($id,$request)
    {   

        $user = $this->member_model->where('id',$id)->first();


        if ($user == "") 
        {   
            $request->session()->put("failed","Session Time Out. You need to Login Again.");
            header('Location:'.url('/'));
            exit();
        }
        else
        {   
            return $user;
        }
    }

}
    