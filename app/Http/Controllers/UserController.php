<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\MemberType;
use App\Models\MembersCompany;
use App\Models\Modules;
use App\Models\MemberRoles;
use DB;
use App\Traits\CommonTrait;



class UserController extends Controller
{
    use CommonTrait;

    public function __construct(Request $request)
    {   
    }



    public function Index(Request $request)
    {	
        $user_id = session("login")["user_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);
        
        $company_id = session("login")["company_id"];

        $get_user_id = MembersCompany::where('company_id',$company_id)->pluck('member_id');


        $users = Members::whereIn('id',$get_user_id)->where('member_type','!=',0)->get();

        return view('users',compact("users"));
    }



    public function AddUser(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $company_id = session("login")["company_id"];
            
            $input = $request->all();


             

            //check email Already
            if (Members::where('email',strtolower(trim($input['user_email'])))->count() > 0) 
            {
                return array("status"=>"0","msg"=>"Requested User Email Address Already Exist");

            }

            // $code = rand(1111,9999);
            $code = 1234;
            $text_notes = "Thank you for Registering on ......";
            // $this->SendMailVerification("0",$code,$input['user_email'],$text_notes);


                $data = array(  
                            'username'          => $input['user_name'],
                            'email'             => strtolower(trim($input['user_email'])),
                            'password'          => Hash::make($input['user_password']),
                            'user_image'        => "user/default_user_icon.png",
                            'verification_code' => $code,
                            'is_verified'       => 0,
                            'is_set_profile'    => 1,
                            'is_blocked'        => 0,
                            'temp_password'     => $input['user_password'],
                            'member_type'       => $input['user_type'],
                            'parent_id'         => $user_id,
                            'created_at'        => date("Y-m-d H:i:s"),
                            'updated_at'        => date("Y-m-d H:i:s"),
                         );


                $id  = Members::insertGetId($data);
                if($id)
                {   

                     MembersCompany::insert(array(
                            'member_id'     => $id,
                            'company_id'    => $company_id,
                        ));
                    return array("status"=>"1","msg"=>"User Added Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Failed");

                }


           
            
        } catch (Exception $e) 
        {
            
        }
    }

    public function UserListAJAX(Request $request,$search_text)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $company_id = session("login")["company_id"];
            
            $get_user_id = MembersCompany::where('company_id',$company_id)->pluck('member_id');


            if (empty($search_text)) 
            {       
                    $get_user_list =  Members::whereIn('id',$get_user_id)
                                      ->where('member_type','!=',0)
                                      ->get();
            }
            else
            {   
                    $get_user_list =  Members::whereIn('id',$get_user_id)
                                      ->where('username','like','%'.$search_text.'%')
                                      ->where('member_type','!=',0)
                                      ->get();

            }
            
            if (count($get_user_list)==0) 
            {
                ?>
                <tr><td colspan="6" class="text-center tx-18">No User Found</td></tr>
                <?php
            }
            else
            {

                $count= 1;
                foreach ($get_user_list as $key) 
                {
                ?>

                    <tr id="user<?php echo $key['id']?>">
                      <td scope="row"><b><?php echo $count; ?></b></td>
                      <td><?php echo $key['username']?></td>
                      <td><?php echo $key['email']?></td>
                      <td><?php echo isset($key->member_type_name['name'])?$key->member_type_name['name']:"Super Admin"?></td>
                      <td>
                        <?php if ($key['is_verified'] == 0):?>
                            <span class="badge badge-danger">Not Verified!</span>
                        <?php else:  ?>
                            <span class="badge badge-success">Verified!</span>
                        <?php endif; ?>
                          
                      </td>
                      <td><a data-toggle="tooltip" title="Block/Unblock User" id="status-btn-color<?php echo $key['id']?>" class="<?php if ($key['is_blocked'] == 0): ?>btn btn-danger<?php else: ?>btn btn-success<?php endif ?>" href="javascript:void(0)" onclick='BlockUnblockUser("<?php echo $key['id']?>")'><i id="status-btn-icon<?php echo $key['id']?>"  class="
                                                    <?php if ($key['is_blocked'] == 0): ?>
                                                    fa fa-lock tx-15
                                                    <?php else: ?>
                                                    fa fa-unlock tx-15
                                                    <?php endif ?>

                                                    "></i></a>&nbsp;&nbsp;&nbsp;<!-- <a class="btn btn-danger" onclick='DeleteUser("<?php // echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a> --></td>
                    </tr>

                <?php
                $count++;
                } 
            }

        } 
        catch (Exception $e) 
        {
            
        }
    }



    public function DeleteUser(Request $request,$id)
    {
        try 
        {

            if(Members::where('id',$id)->delete())
            {
                return array("status"=>"1","msg"=>"User Deleted Successfully.");
            }
            else
            {
                return array("status"=>"0","msg"=>"Failed to Delete Category.");
            }
        } catch (Exception $e) 
        {
            
        }
    }


    public function MemberType(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $company_id = session("login")["company_id"];
                 
            $get_type = MemberType::where('company_id',$company_id)->where('is_show',1)->orderBy('id','asc')->get();
           
            
            if (count($get_type)==0) 
            {
                ?>
                <option value="" disabled="" selected="">Select Member Type</option>
                <?php
            }
            else
            {

                foreach ($get_type as $key) 
                {
                ?>

                <option value="<?php echo $key['id'] ?>"><?php echo $key['name'] ?></option>

                <?php
                } 
            }

        } 
        catch (Exception $e) 
        {
            
        }
    }

    public function BlockUnblockUser(Request $request,$id)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $status =Members::where('id',$id)->first()->is_blocked;

            if($status == 0)
            {
                Members::where('id',$id)->update(array('is_blocked' =>1));
                return array("status"=>"1","msg"=>"User Blocked Successfully.");
            }
            else
            {
                Members::where('id',$id)->update(array('is_blocked' => 0));
                return array("status"=>"1","msg"=>"User Unblocked Successfully.");
            }

           
        } catch (Exception $e) 
        {
            
        }
    }




    public function MemberTypeList(Request $request)
    {
        try 
        {   
            $user_id = session("login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $company_id = session("login")["company_id"];

            $member_type = MemberType::where('company_id',$company_id)->orderBy('id','asc')->get();
            return view('member_type',compact('member_type'));
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
            $user_id = session("login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $company_id = session("login")["company_id"];
            
            $input = $request->all();
            
            if (empty($input['member_type_id'])) 
            {   
                $data = array(
                        "name"                  => $input['member_type_name'],
                        "is_show"               => 1,
                        "company_id"            => $company_id,
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
            $user_id = session("login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
                
            $company_id = session("login")["company_id"];

            

            if (empty($search_text)) 
            {       
                    $get_member_type_list = MemberType::where('company_id',$company_id)->orderBy('id','asc')->get();
            }
            else
            {   
                    $get_member_type_list = MemberType::where('company_id',$company_id)->orderBy('id','asc')->where('name','like','%'.$search_text.'%')
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
            $user_id = session("login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

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
            $user_id = session("login.user_id");

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


    public function MemberRoles(Request $request)
    {
        try 
        {   
            $user_id = session("login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $company_id = session("login")["company_id"];

            $member_type = MemberType::where('company_id',$company_id)->orderBy('id','asc')->get();
            if (count($member_type) == 0) 
            {
                return redirect()->route('member-type-list')->with('failed','Kindly Add User Type First.');
            }


            $all_modules = Modules::where('parent_id',0)->get();
            foreach ($all_modules as $key) 
            {
                $all_sub_modules[] = Modules::where('parent_id',$key['id'])->get();
            }

            $member_role = MemberRoles::where('member_type',$member_type[0]['id'])->pluck('module_id')->toArray();

            // print_r($member_role);
            // exit();

            return view('member_role',compact('member_type','all_modules','all_sub_modules','member_role'));
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
            $user_id = session("login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $company_id = session("login")["company_id"];

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
            $user_id = session("login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $company_id = session("login")["company_id"];

            $input = $request->all();    
           
            MemberRoles::where('member_type',$input['member_type_id'])->delete();
            
            if (isset($input['main_module-cb'])) 
            {

                foreach ($input['main_module-cb'] as $key) 
                {
                    MemberRoles::insert(array(
                            'member_type' => $input['member_type_id'],
                            'module_id'   => $key,
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
    