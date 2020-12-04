<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Members;
use App\Models\MemberType;
use App\Models\MemberRoles;
use App\Models\Modules;
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

            $members= Members::where('member_type',0)->get();
            return view('admin.member',compact('members'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }


    public function MemberListAJAX(Request $request,$search_text)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($search_text)) 
            {       
                    $get_member_list = Members::where('member_type',0)->get();
            }
            else
            {   
                    $get_member_list = Members::where('member_type',0)->where('username','like','%'.$search_text.'%')
                                            ->get();

            }
            
            if (count($get_member_list)==0) 
            {
                ?>
                <tr><td colspan="5" class="text-center tx-18">No Member Found</td></tr>
                <?php
            }
            else
            {

                foreach ($get_member_list as $key) 
                {
                ?>

                     <tr id="row<?php echo $key['id'] ?>">
                                                    <!-- <td>
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td> -->
                                                    <td>
                                                        <?php echo $key['username'] ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $key['email'] ?>
                                                        
                                                    </td>
                                                    <td>
                                                        <?php if($key['is_verified'] == 0): ?>
                                                        <span class="role admin">Not Verified</span>
                                                        <?php else: ?>
                                                        <span class="role member">Verified</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($key['is_set_profile'] == 0): ?>
                                                        <span class="role admin">Not Updated</span>
                                                        <?php else: ?>
                                                        <span class="role member">Updated</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                         <a data-toggle="tooltip" title="Block/Unblock Member" id="status-btn-color<?php echo $key['id']?>" class="<?php if ($key['is_blocked'] == 0): ?>btn btn-danger<?php else: ?>btn btn-success<?php endif ?>" href="javascript:void(0)" onclick='BlockUnblockMember("<?php echo $key['id']?>")'><i id="status-btn-icon<?php echo $key['id']?>"  class="
                                                    <?php if ($key['is_blocked'] == 0): ?>
                                                    fa fa-lock tx-15
                                                    <?php else: ?>
                                                    fa fa-unlock tx-15
                                                    <?php endif ?>

                                                    "></i></a>

                                                    <a data-toggle="tooltip" title="View Member Details" class="btn btn-primary" href="javascript:void(0)" onclick='ViewMember("<?php echo $key['id']?>")'><i class="fa fa-eye tx-15"></i></a>
                                                    </td>
                                                </tr>

                <?php
                } 
            }

        } 
        catch (Exception $e) 
        {
            
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
            $customer   = Customers::where('company_id',$company->id)->where('is_deleted',0)->count();
            $sale       = Sales::where('company_id',$company->id)->count();
            $sub_member = Members::where('parent_id',$id)->get();




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
                                <td style="word-break: break-all;"><?php echo $member->email; ?></td>
                            </tr>
                            <tr>
                                <th>Account Type:</th>
                                <td><?php echo isset($member->member_type_name->name)?$member->member_type_name->name:"Admin"; ?></td>
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
            <table class="table tx-14">
                <tbody>
                    <tr>
                        <td>Total Categories: <?php echo $category; ?> </td>
                        <td>Total Products: <?php echo $product; ?></td>
                        <td>Total Customers: <?php echo $customer; ?></td>
                    </tr>
                    <tr>
                        <td>Total Sub-Member: <?php echo count($sub_member); ?></td>
                        <td>Total Sale Receipt:  <?php echo $sale; ?> </td>
                        <td>Account Created:  <?php echo date("d-M-Y h:i a",strtotime($member->created_at)); ?></td>
                    </tr>
                </tbody>
            </table>

            <hr>
            <center><h5>Sub-Member Details</h5></center>
            <br>

            <?php if(count($sub_member) == 0): ?>
            <p class="tx-danger tx-center">No Sub-Member Found</p>
            <?php else: ?>
                    <table class="table tx-12">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Account Type</th>
                                <th>Status</th>
                            </tr>                                
                        </thead>
                        <tbody>
                            <?php foreach ($sub_member as $key): ?>
                            <tr>
                                <td><?php echo $key['username']; ?> </td>
                                <td><?php echo $key['email']; ?></td>
                                <td><?php echo $key->member_type_name['name']; ?></td>
                                <td>
                                    <?php if($key['is_verified'] == 0): ?>
                                        <span class="badge badge-danger">Not Verified</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Verified</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
            <?php endif; ?>


            <?php

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }


    public function BlockUnblockMember(Request $request,$id)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $status =Members::where('id',$id)->first();

            if($status->is_blocked == 0)
            {
                Members::where('id',$id)->update(array('is_blocked' =>1));
                Members::where('parent_id',$status->id)->update(array('is_blocked' =>1));
                return array("status"=>"1","msg"=>"User Blocked Successfully.");
            }
            else
            {
                Members::where('id',$id)->update(array('is_blocked' => 0));
                Members::where('parent_id',$status->id)->update(array('is_blocked' =>0));
                return array("status"=>"1","msg"=>"User Unblocked Successfully.");
            }

           
        } catch (Exception $e) 
        {
            
        }
    }



    //_________________________________________________________________________________
    //_________________________________________________________________________________
    //_________________________________________________________________________________




    public function MemberTypes(Request $request)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $member_type = MemberType::orderBy('id','asc')->get();
            return view('admin.member_type',compact('member_type'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }


    public function AddUpdateMemberType(Request $request)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();
            
            if (empty($input['member_type_id'])) 
            {   
                $data = array(
                        "name"                  => $input['member_type_name'],
                        "is_show"               => 1,
                        "created_at"            => date('Y-m-d H:i:s'),
                        "updated_at"            => date('Y-m-d H:i:s'),
                        );
                if(MemberType::insert($data))
                {
                    return array("status"=>"1","msg"=>"Member Type Added Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Failed");

                }
            }
            else
            {

                if ($input['member_type_id'] == 1) 
                {
                    return array("status"=>"0","msg"=>"Unable to Update this One.");
                }

                $data = array(
                        "name"                  => $input['member_type_name'],
                        );
                if(MemberType::where('id',$input['member_type_id'])->update($data))
                {
                    return array("status"=>"1","msg"=>"Member Type Updated Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Failed");
                }


            }

            
        } catch (Exception $e) 
        {
            
        }
    }

    public function MemberTypeListAJAX(Request $request,$search_text)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($search_text)) 
            {       
                    $get_member_type_list = MemberType::orderBy('id','asc')->get();
            }
            else
            {   
                    $get_member_type_list = MemberType::orderBy('id','asc')->where('name','like','%'.$search_text.'%')
                                            ->get();

            }
            
            if (count($get_member_type_list)==0) 
            {
                ?>
                <tr><td colspan="3" class="text-center tx-18">No Member Type Found</td></tr>
                <?php
            }
            else
            {

                foreach ($get_member_type_list as $key) 
                {
                ?>

                    <tr id="member_type<?php echo $key['id']?>">
                        <td><?php echo $key['name']?></td>
                        <td class="tx-center">
                                                         <label  class="switch switch-3d switch-primary mr-3">
                                                          <input  id="visibility_value<?php echo $key['id']?>" onclick='MemberTypeStatus("<?php echo $key['id']?>","<?php echo $key['is_show']; ?>")' type="checkbox" class="switch-input"  <?php if ($key['is_show'] ==1): ?>
                                                              checked
                                                          <?php endif ?> value="<?php echo $key['is_show'] ?>">
                                                          <span class="switch-label"></span>
                                                          <span class="switch-handle"></span>
                                                        </label>
                                                    </td>
                                                    <td class="tx-center">
                                                         <a class="btn btn-primary" href="javascript:void(0)" onclick='EditEMemberType("<?php echo $key['id']?>","<?php echo $key['name']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<!-- <a class="btn btn-danger" onclick='DeleteMemberType("<?php //echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a> -->
                                                    </td>                        

                    </tr>

                <?php
                } 
            }

        } 
        catch (Exception $e) 
        {
            
        }
    }

    public function DeleteMemberType(Request $request,$id)
    {
        try 
        {
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            if ($id == 1) 
            {
                return array("status"=>"0","msg"=>"Unable to Delete this One.");
            }

            if(MemberType::where('id',$id)->delete())
            {
                return array("status"=>"1","msg"=>"Member Type Deleted Successfully.");
            }
            else
            {
                return array("status"=>"0","msg"=>"Failed to Delete Member Type.");
            }
        } catch (Exception $e) 
        {
            
        }
    }

    

    public function ChangeMemberTypeAvailability(Request $request,$id,$status)
    {
        try 
        {
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            if($status == 0)
            {
                MemberType::where('id',$id)->update(array('is_show' =>1));
                return array("status"=>"1","msg"=>"Member Type Visible Successfully.");
            }
            else
            {
                MemberType::where('id',$id)->update(array('is_show' => 0));
                return array("status"=>"1","msg"=>"Member Type Hide Successfully.");
            }



        } catch (Exception $e) 
        {
            
        }
    }



    //_________________________________________________________________________________
    //_________________________________________________________________________________
    //_________________________________________________________________________________




    public function MemberRoles(Request $request)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $member_type = MemberType::orderBy('id','asc')->get();

            $all_modules = Modules::where('parent_id',0)->get();
            foreach ($all_modules as $key) 
            {
                $all_sub_modules[] = Modules::where('parent_id',$key['id'])->get();
            }

            $member_role = MemberRoles::where('member_type',$member_type[0]['id'])->pluck('module_id')->toArray();

            // print_r($member_role);
            // exit();

            return view('admin.member_role',compact('member_type','all_modules','all_sub_modules','member_role'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }


    
    public function MemberRolesAJAX(Request $request,$id)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $member_role = MemberRoles::where('member_type',$id)->pluck('module_id')->toArray();

            $all_modules = Modules::where('parent_id',0)->get();

            ?>
                <input type="hidden" name="member_type_id" id="member_type_id" value="<?php echo $id; ?>">

            <?php

            foreach ($all_modules as $key) 
            {
                $all_sub_modules = Modules::where('parent_id',$key['id'])->get();
                ?>
                 <tr id="member_role<?php echo $key['id'] ?>">
                                                    <td><li><input onclick='ToggleAllSubModule("<?php echo $key['id'] ?>")' id="main_module-cb<?php echo $key['id'] ?>"  type="checkbox" name="main_module-cb[]" value="<?php echo $key['id'] ?>" <?php if (in_array($key['id'], $member_role)): ?>
                                                        checked=""
                                                        <?php else: ?>
                                                    <?php endif ?>>&nbsp;&nbsp;&nbsp;<?php echo $key['name'] ?></li></td>
                                                    <td> 
                                                        <?php foreach($all_sub_modules as $key_): ?>
                                                           <li><input value="<?php echo $key_['id'] ?>" class="sub_module-cb<?php echo $key['id'] ?>" type="checkbox" name="main_module-cb[]" <?php if (in_array($key_['id'], $member_role)): ?>
                                                        checked=""
                                                    <?php endif ?>>&nbsp;&nbsp;&nbsp;<?php echo $key_['name'] ?></li>
                                                       <?php endforeach; ?>
                                                    </td>
                                                </tr>
                <?php


            }





            
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }



    public function SaveRoles(Request $request)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $input = $request->all();    
           
            MemberRoles::where('member_type',$input['member_type_id'])->delete();
            
            if (isset($input['main_module-cb'])) 
            {

                foreach ($input['main_module-cb'] as $key) 
                {
                    MemberRoles::insert(array(
                            'member_type' => $input['member_type_id'],
                            'module_id'   => $key,
                            'created_at'  => date("Y-m-d H:i:s"),
                            'updated_at'  => date("Y-m-d H:i:s"),
                    ));
                }
            }

            
            return array("status"=>"1","msg"=>"Member Roles Updated Successfully.");


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
    