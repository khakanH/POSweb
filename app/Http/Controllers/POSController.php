<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\Category;
use App\Models\Product;
use App\Models\CompanyInfo;


class POSController extends Controller
{


    public function __construct(Request $request)
    {

    }


    public function Index(Request $request)
    {	
        $user_id = session("login")["user_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

    	$company  = CompanyInfo::where('member_id',$user_id)->get();
    	$category = Category::where('member_id',$user_id)->where('is_deleted',0)->get();
    	$product  = Product::where('member_id',$user_id)->where('is_deleted',0)->limit(20)->get();

        return view('pos',compact('company','category','product'));
    }



    public function GetPOSProductList(Request $request,$cate_id,$search)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($cate_id)) 
            {       
            	if (empty($search)) 
            	{
                    $get_prod_list = Product::where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->limit(20)
                                      ->get();
            	}
            	else 
            	{
            		$get_prod_list = Product::where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->where(function($query) use ($search)
                                            {
                                                $query->where('name','like','%'.$search.'%')
                                                ->orWhere('product_code','like','%'.$search.'%');
                                            })
                                      ->limit(20)
                                      ->get();
            	}
            }
            else
            {   
                if (empty($search)) 
            	{    
            		$get_prod_list = Product::where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->where('category_id',$cate_id)
                                      ->limit(20)
                                      ->get();
                }
                else 
                {
                	$get_prod_list = Product::where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->where('category_id',$cate_id)
                                      ->where(function($query) use ($search)
                                            {
                                                $query->where('name','like','%'.$search.'%')
                                                ->orWhere('product_code','like','%'.$search.'%');
                                            })
                                      ->limit(20)
                                      ->get();
                }

            }
            
            if (count($get_prod_list)==0) 
            {
                ?>
                <div class="col-12"><p class="text-sm-center">No Product Found</p></div>
                <?php
            }
            else
            {

                foreach ($get_prod_list as $prod) 
                {
                ?>

                  <div class="col-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mx-auto d-block">
                                            <img class="rounded-circle mx-auto d-block" src="<?php echo env('IMG_URL').$prod['image'] ?>" width="100" height="100" alt="{{$prod['name']}}">
                                            <hr>
                                            <h5 class="text-sm-center mt-2 mb-1"><?php echo $prod['name']?></h5>
                                            <div class="location text-sm-center">
                                                <?php echo $prod['price']?> /-</div>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>

                <?php
                } 
            }

        } 
        catch (Exception $e) 
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
    