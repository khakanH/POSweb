<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\Sales;

use App\Models\SalesItems;



class DashboardController extends Controller
{

    public function __construct(Request $request)
    {   
    }



    public function Index(Request $request)
    {	
        $user_id = session("login")["user_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

    	$monthly_sale = array();
        for ($i=1; $i <= 12  ; $i++) 
        {   
            $monthly_sale[] = Sales::where('member_id',$user_id)->whereMonth('created_at',$i)->sum('total_bill');
        }

       

        return view('dashboard',compact("monthly_sale"));
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
    