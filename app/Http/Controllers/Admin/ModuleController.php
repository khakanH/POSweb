<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Members;
use App\Models\CompanyInfo;
use App\Models\MemberRoles;
use App\Models\Modules;

use DB;

class ModuleController extends Controller
{

    public function __construct(Request $request)
    {   
    }

    public function WebsiteModule(Request $request)
    {
        
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $modules= Modules::where('parent_id',0)->orderBy('id','asc')->get();

            foreach ($modules as $key) 
            {
                $sub_modules[] = Modules::where('parent_id',$key['id'])->get();
            }

            return view('admin.website_module',compact("modules","sub_modules"));            

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }


    }

    public function AddUpdateModule(Request $request)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();
            
            if (empty($input['module_id'])) 
            {   

                if (empty($input['module_parent_id'])) 
                {   
                    //adding module
                    $data = array(
                            "name"                  => $input['module_name'],
                            "route"                 => $input['module_route'],
                            "icon"                  => isset($input['icon'])?$input['icon']:"fas fa-circle",
                            "parent_id"             => 0,
                            "created_at"            => date('Y-m-d H:i:s'),
                            "updated_at"            => date('Y-m-d H:i:s'),
                            );
                    if(Modules::insert($data))
                    {
                        return array("status"=>"1","msg"=>"Module Added Successfully.");
                    }
                    else
                    {
                        return array("status"=>"0","msg"=>"Failed");

                    }
                }
                else
                {
                    //adding sub-module
                    $data = array(
                            "name"                  => $input['module_name'],
                            "route"                 => $input['module_route'],
                            "icon"                  => isset($input['icon'])?$input['icon']:"fas fa-circle",
                            "parent_id"             => $input['module_parent_id'],
                            "created_at"            => date('Y-m-d H:i:s'),
                            "updated_at"            => date('Y-m-d H:i:s'),
                            );
                    if(Modules::insert($data))
                    {
                        return array("status"=>"1","msg"=>"Sub-Module Added Successfully.");
                    }
                    else
                    {
                        return array("status"=>"0","msg"=>"Failed");

                    }
                }
            }
            else
            {

                $data = array(
                        "name"                  => $input['module_name'],
                        "route"                 => $input['module_route'],
                        "icon"                  => $input['icon'],
                        );
                if(Modules::where('id',$input['module_id'])->update($data))
                {
                    return array("status"=>"1","msg"=>"Module Updated Successfully.");
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

    public function ModuleListAJAX(Request $request,$search_text)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($search_text)) 
            {       
                    $get_module_list = Modules::where('parent_id',0)->get();
            }
            else
            {   
                    $get_module_list = Modules::where('parent_id',0)
                                        ->where('name','like','%'.$search_text.'%')
                                        ->get();

            }
            
            if (count($get_module_list)==0) 
            {
                ?>
                <tr><td colspan="4" class="text-center tx-18">No Module Found</td></tr>
                <?php
            }
            else
            {

                foreach ($get_module_list as $key) 
                { 

                $sub_modules = Modules::where('parent_id',$key['id'])->get();

                ?>

                    <tr id="module<?php echo $key['id']?>">
                        <td><?php echo $key['name']?></td>
                        <td><?php echo $key['route']?></td>
                        <td><i class="<?php echo $key['icon']?> tx-24"></i></td>
                        <td>
                        <?php foreach($sub_modules as $key_): ?>
                            <li id="sub_modules<?php echo $key_['id']?>"><a href="javascript:void(0)" onclick='EditSubModule("<?php echo $key_['id']?>","<?php echo $key_['name']?>","<?php echo $key_['route']?>","<?php echo $key_['icon']?>")'> <?php echo $key_['name']; ?></a><a onclick='DeleteSubModule("<?php echo $key_['id'] ?>")' data-toggle="tooltip" title="Delete Sub-Module" class="tx-danger" style="float: right;" href="javascript:void(0)"><i class="fa fa-times-circle"></i></a></li>
                        <?php endforeach; ?>
                        </td>

                       <td class="tx-center">
                                                         <a data-toggle="tooltip" title="Add Sub-Module" class="btn btn-success" href="javascript:void(0)" onclick='AddSubModule("<?php echo $key['id']?>")'><i class="fa fa-plus tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" href="javascript:void(0)" onclick='EditModule("<?php echo $key['id']?>","<?php echo $key['name']?>","<?php echo $key['route']?>","<?php echo $key['icon']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" onclick='DeleteModule("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a>
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



    public function DeleteModule(Request $request,$id)
    {
        try 
        {
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);


            if(Modules::where('id',$id)->delete())
            {
                Modules::where('parent_id',$id)->delete();
                return array("status"=>"1","msg"=>"Module Deleted Successfully.");
            }
            else
            {
                return array("status"=>"0","msg"=>"Failed to Delete Module.");
            }
        } catch (Exception $e) 
        {
            
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
    