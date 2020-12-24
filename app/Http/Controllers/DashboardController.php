<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\Sales;
use App\Models\SalesItems;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customers;
use App\Models\Notification;

use App\Traits\CommonTrait;

use DB;

class DashboardController extends Controller
{

    use CommonTrait;
    public function __construct(Request $request)
    {   
    }



    public function Index(Request $request)
    {	
        $user_id = session("login")["user_id"];

        $company_id = session("login")["company_id"];


        $user_info = $this->checkUserAvailbility($user_id,$request);

        // $customer_count = Customers::where('company_id',$company_id)->where('is_deleted',0)->count();
        // $category_count = Category::where('company_id',$company_id)->where('is_deleted',0)->count();
        // $product_count = Product::where('company_id',$company_id)->where('is_deleted',0)->count();
        // $total_sale    = Sales::where('company_id',$company_id)->sum('total_bill');
        // $total_item    = Sales::where('company_id',$company_id)->sum('total_item');


        $total_sale_today    = Sales::whereDate('created_at',date("Y-m-d"))->where('company_id',$company_id)->sum('total_bill');
        $total_sale_yesterday    = Sales::whereDate('created_at',date("Y-m-d",strtotime("-1 days")))->where('company_id',$company_id)->sum('total_bill');
        $total_sale_month    = Sales::whereMonth('created_at',date("m"))->where('company_id',$company_id)->sum('total_bill');



        $get_top_prod = SalesItems::select('product_id', DB::raw('COUNT(id) as count'))
        ->groupBy('product_id')
        ->orderBy(DB::raw('COUNT(id)'), 'DESC')
        ->where('company_id',$company_id)
        ->whereYear('created_at',date("Y"))
        ->take(5)
        ->get();



    	$monthly_sale = array();
        for ($i=1; $i <= 12  ; $i++) 
        {   
            $monthly_sale[] = Sales::where('company_id',$company_id)->whereMonth('created_at',$i)->whereYear('created_at',date("Y"))->sum('total_bill');
        }

        $top_prod_name = array();
        $top_prod_count = array();
        foreach ($get_top_prod as $key) 
        {   
            $top_prod_name[] =  Product::where('id',$key['product_id'])->first()->name;
            $top_prod_count[] = $key['count'];
        }
        

        return view('dashboard',compact("monthly_sale","top_prod_name","top_prod_count","total_sale_today","total_sale_yesterday","total_sale_month"));
    }



    public function NotificationAlert(Request $request)
    {
        try 
        {   
            $user_id = isset(session("login")["user_id"])?session("login")["user_id"]:0;

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $get_notifications = Notification::where('receiver_type',2)->where('receiver_id',$user_id)->where('is_deleted',0)->where('is_new',1)->first();
            if ($get_notifications == "") 
            {
                return array("status"=>"0","msg"=>"");
            }
            else
            {
                return array("status"=>"1","msg"=>"");
            }                                              
            
        } 
        catch (Exception $e) 
        {
            
        }
    }



    public function NewNotification(Request $request)
    {
        try 
        {
            $user_id = isset(session("login")["user_id"])?session("login")["user_id"]:0;

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $notifications = Notification::where('receiver_type',2)->where('receiver_id',$user_id)->where('is_deleted',0)->orderBy('created_at','desc')->get();

            if(count($notifications) == 0):
            ?>
            <br>
            <center><p class="tx-danger tx-16">No New Notification Found</p></center>
            <br>
            <?php 
                endif; 
            
                foreach($notifications as $key):
                    $icon_class = ($key['type'] == 1)?"fa fa-info-circle": (($key['type'] == 2)?"fa fa-warning":(($key['type'] == 3)?"fas fa-skull-crossbones":"fas fa-circle"));
                    $icon_bg = ($key['type'] == 1)?"bg-info": (($key['type'] == 2)?"bg-warning":(($key['type'] == 3)?"bg-danger":"bg-other"));

            ?>
            <div class="notifi__item" onclick='MarkNotificationRead("<?php echo $key['id']; ?>")'>
                                    <div class="<?php echo $icon_bg; ?> img-cir img-40">
                                        <i class="<?php echo $icon_class; ?>"></i>
                                    </div>
                                    <div class="content">

                                        <?php if($key['is_new'] == 1): ?>
                                        <span id="newNotifiDot<?php echo $key['id']; ?>" class="badge badge-primary" style="float: right; border-radius: 50%; height: 10px;width: 10px;">&nbsp;</span>
                                        <?php  endif; ?>
                                        <p><?php echo $key['title']; ?></p>
                                        <span class="date"><?php echo $this->getTimeLapse($key['created_at']) ?></span>
                                    </div>

                                </div>
            <?php endforeach;

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
            
            $user_id = isset(session("login")["user_id"])?session("login")["user_id"]:0;

            $user_info = $this->checkUserAvailbility($user_id,$request);

            Notification::where('id',$id)->update(array('is_new'=>0));

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

            if($request->ajax()) 
            {
                return response()->json(['status'=>"0",'msg' => 'Session expired'],401);
            }
            else
            {
                $request->session()->put("failed","Something went wrong.");
                header('Location:'.url('signout'));
            }
            
            exit();
        }
        else
        {   
            return $user;
        }
    }
    


}
    