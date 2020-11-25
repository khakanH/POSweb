<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Members;
use App\Models\CompanyInfo;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sales;
use App\Models\Customers;
use DB;

class MemberController extends Controller
{

    public function __construct(Request $request)
    {   
    }


    public function MemberList(Request $request)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $members= Members::where('member_type',1)->get();
            return view('admin.member',compact('members'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }

    public function MemberDetails(Request $request,$id)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);


            $member= Members::where('id',$id)->first();
            $company = CompanyInfo::where('member_id',$id)->first();

            $category   = Category::where('company_id',$company->id)->count();
            $product    = Product::where('company_id',$company->id)->count();
            $customer   = Customers::where('company_id',$company->id)->count();
            $sale       = Sales::where('company_id',$company->id)->count();
            $sub_member = Members::where('parent_id',$id)->count();
            if ($member == "" || $company == "") 
            {
                ?>
                <center><h5>No Details Found</h5></center>
                <?php
                return;
            }
            ?>

            <div class="row">
                
                <div class="col-lg-6" style="border-right: solid lightgray 1px;">
                    <center>
                        <h5>User Info</h5>
                        <br>
                        <img src="<?php echo env('IMG_URL').$member->user_image ?>" width="60" height="60">
                    </center>
                        <br>
                    <table class="table" style="font-size: 13px;">
                        <tbody>
                            <tr>
                                <th>Username:</th>
                                <td><?php echo $member->username; ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo $member->email; ?></td>
                            </tr>
                            <tr>
                                <th>Account Type:</th>
                                <td><?php echo $member->member_type_name->name; ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>







                <div class="col-lg-6">
                    <center><h5>Company Info</h5>
                    <br>
                        <img src="<?php echo env('IMG_URL').$company->logo ?>" width="60" height="60">
                    </center>
                        <br>
                    <table class="table" style="font-size: 13px;">
                        <tbody>
                            <tr>
                                <th>Company Name:</th>
                                <td><?php echo $company->name; ?></td>
                            </tr>
                            <tr>
                                <th>Company Email:</th>
                                <td><?php echo $company->email; ?></td>
                            </tr>
                            <tr>
                                <th>Company Phone:</th>
                                <td><?php echo $company->phone; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <hr>
            <table class="table">
                <tbody>
                    <tr>
                        <td>Total Categories: <?php echo $category; ?> </td>
                        <td>Total Products: <?php echo $product; ?></td>
                        <td>Total Customers: <?php echo $customer; ?></td>
                    </tr>
                    <tr>
                        <td>Total Sub-Member: <?php echo $sub_member; ?></td>
                        <td>Total Sale Receipt:  <?php echo $sale; ?> </td>
                        <td>Account Created:  <?php echo date("d-M-Y h:i a",strtotime($member->created_at)); ?></td>
                    </tr>
                </tbody>
            </table>


            <?php

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

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
    