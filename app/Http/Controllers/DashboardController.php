<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;



class DashboardController extends Controller
{

    protected $member_model;

    public function __construct(Request $request)
    {   
        $this->member_model         = new Members();
    }



    public function Index(Request $request)
    {
        return view('dashboard');
    }


    


}
    