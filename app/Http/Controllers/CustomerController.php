<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\Customers;



class CustomerController extends Controller
{

    public function __construct(Request $request)
    {   
    }



    public function Index(Request $request)
    {	
        $user_id = session("login")["user_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);
    	
    	$customer = Customers::where('member_id',$user_id)->where('is_deleted',0)->get();
        return view('customers',compact('customer'));
    }

    public function CustomerListAJAX(Request $request, $search_text)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($search_text)) 
            {       
                    $get_cust_list = Customers::where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->paginate(15);
            }
            else
            {   
                    $get_cust_list = Customers::where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->where('customer_name','like','%'.$search_text.'%')
                                      ->paginate(15);

            }
            
            if (count($get_cust_list)==0) 
            {
                ?>
                <tr><td colspan="5" class="text-center tx-18">No Customer Found</td></tr>
                <?php
            }
            else
            {

                $count= 1;
                foreach ($get_cust_list as $key) 
                {
                ?>

                    <tr id="cust<?php echo $key['id']?>">
                      <td scope="row"><b><?php echo $count; ?></b></td>
                      <td><?php echo $key['customer_name']?></td>
                      <td><?php echo $key['customer_email']?></td>
                      <td><?php echo $key['customer_phone']?></td>
                      <td class="text-center"><a class="btn btn-primary" href="javascript:void(0)" onclick='EditCustomer("<?php echo $key['id']?>","<?php echo $key['customer_name']?>","<?php echo $key['customer_email']?>","<?php echo $key['customer_phone']?>","<?php echo $key['customer_discount']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" onclick='DeleteCustomer("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a></td>
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
    
    public function DeleteCustomer(Request $request,$id)
    {
        try 
        {

            if(Customers::where('id',$id)->update(array('is_deleted'=>1)))
            {
                return array("status"=>"1","msg"=>"Customer Deleted Successfully.");
            }
            else
            {
                return array("status"=>"0","msg"=>"Failed to Delete Customer.");
            }
        } catch (Exception $e) 
        {
            
        }
    }


    public function checkUserAvailbility($id,$request)
    {   

        $user = Members::where('id',$id)->first();


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
    