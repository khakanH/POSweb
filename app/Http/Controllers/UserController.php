<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\MemberType;
use DB;

class UserController extends Controller
{

    public function __construct(Request $request)
    {   
    }



    public function Index(Request $request)
    {	
        $user_id = session("login")["user_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);


        $users = Members::where('parent_id',$user_id)->get();

        return view('users',compact("users"));
    }



    public function AddUser(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();


             

             //check email Already
            if (Members::where('email',strtolower(trim($input['user_email'])))->where('member_type',$input['user_type'])->where('parent_id',$user_id)->count() > 0) 
            {
                return redirect()->route('users')->with('failed','Requested User Email Address Already Exist');
            }

            // $code = rand(1111,9999);
            $code = 1234;
            $text_notes = "Thank you for Registering on ......";
            // $this->SendMailVerification("0",$code,$input['email'],$text_notes);


                $data = array(  
                            'username'          => $input['user_name'],
                            'email'             => strtolower(trim($input['user_email'])),
                            'password'          => Hash::make($input['user_password']),
                            'user_image'        => "/default_user_icon.png",
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



                if(Members::insert($data))
                {
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
            

            if (empty($search_text)) 
            {       
                    $get_user_list =  Members::where('parent_id',$user_id)
                                      ->get();
            }
            else
            {   
                    $get_user_list =  Members::where('parent_id',$user_id)
                                      ->where('username','like','%'.$search_text.'%')
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
                      <td><?php echo $key->member_type_name['name']?></td>
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
            

                 
            $get_type = MemberType::where('id','!=',1)->orderBy('id','asc')->get();
           
            
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
    