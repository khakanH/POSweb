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

use DB;

class DashboardController extends Controller
{

    public function __construct(Request $request)
    {   
    }



    public function Index(Request $request)
    {	
        $user_id = session("login")["user_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $customer_count = Customers::where('member_id',$user_id)->where('is_deleted',0)->count();
        $category_count = Category::where('member_id',$user_id)->where('is_deleted',0)->count();
        $product_count = Product::where('member_id',$user_id)->where('is_deleted',0)->count();
        $total_sale    = Sales::where('member_id',$user_id)->sum('total_bill');
        $total_item    = Sales::where('member_id',$user_id)->sum('total_item');

        $get_top_prod = SalesItems::select('product_id', DB::raw('COUNT(id) as count'))
        ->groupBy('product_id')
        ->orderBy(DB::raw('COUNT(id)'), 'DESC')
        ->where('member_id',$user_id)
        ->whereYear('created_at',date("Y"))
        ->take(5)
        ->get();



    	$monthly_sale = array();
        for ($i=1; $i <= 12  ; $i++) 
        {   
            $monthly_sale[] = Sales::where('member_id',$user_id)->whereMonth('created_at',$i)->sum('total_bill');
        }

        $top_prod_name = array();
        $top_prod_count = array();
        foreach ($get_top_prod as $key) 
        {   
            $top_prod_name[] =  Product::where('id',$key['product_id'])->first()->name;
            $top_prod_count[] = $key['count'];
        }
        

        return view('dashboard',compact("monthly_sale","customer_count","category_count","product_count","total_sale","total_item","top_prod_name","top_prod_count"));
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
    