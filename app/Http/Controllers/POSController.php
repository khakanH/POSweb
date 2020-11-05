<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\Category;
use App\Models\Product;
use App\Models\CompanyInfo;
use App\Models\PendingBills;
use App\Models\PendingBillItems;
use App\Models\Sales;
use App\Models\Customers;


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

        

        if (PendingBills::where('member_id',$user_id)->count() == 0) 
        {
            PendingBills::insert(array(
                                        "bill_code"      => $this->getBillCode($user_id),
                                        "member_id"     => $user_id,
                                        "customer_id"   => 0,
                                        "tax"           => 0,
                                        "discount"      => 0,
                                        "subtotal"      => 0,
                                        "total_bill"    => 0,
                                        "total_item"    => 0,
                                        "created_at"    => date("Y-m-d H:i:s"),
                                        "updated_at"    => date("Y-m-d H:i:s"),
                                ));
        }
       
        $pending_bill      = PendingBills::where('member_id',$user_id)->get();
        $pending_bill_item = PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$pending_bill[0]['id'])->get();

        $customers = Customers::where('member_id',$user_id)->where('is_deleted',0)->get();

        return view('pos',compact('company','category','product','pending_bill','pending_bill_item','customers'));
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
                                            <img class="rounded-circle mx-auto d-block" src="<?php echo env('IMG_URL').$prod['image'] ?>" width="100" height="100" alt="<?php echo $prod['name']?> ">
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


    public function GetPendingBill(Request $request,$id)
    {
        $user_id = session("login")["user_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $pending_bill      = PendingBills::where('member_id',$user_id)->where('id',$id)->first();

        if ($pending_bill == "") 
        {
            return "<p>No Bill Found</p>";
        }

        $pending_bill_item = PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$pending_bill->id)->get();

        $customers = Customers::where('member_id',$user_id)->where('is_deleted',0)->get();


        ?> 

                    <h5>Choose Client</h5>
                           <div class=" row form-group">
                           <div class="col-7"><select class="form-control" name="customer_list" id="customer_list">
                             <option value="0">Walk in Customer</option>
                             <?php foreach($customers as $cust): ?>
                             <option <?php if ($pending_bill->customer_id == $cust['id']): ?>
                                 selected
                             <?php endif ?>
                              value="<?php echo $cust['id'] ?>"><?php echo $cust['customer_name'] ?></option>
                             <?php endforeach; ?>
                           </select></div>
                           <div class="col-5"> <input type="text" name="bar_code" class="form-control" placeholder="Enter Barcode"></div>
                           </div>
                    <div class="table-responsive" style="min-height: 200px; max-height: 200px;">
                                    <table class="table table-data2">
                                        <thead class="text-sm-center">
                                            <tr>
                                                <th>
                                                    
                                                </th>
                                                <th width="50%">Product</th>
                                                <th width="10%">Price</th>
                                                <th width="10%">Qty</th>
                                                <th width="30%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm-center">
                                            
                                        <?php if(count($pending_bill_item)==0): ?>
                                          <tr class="spacer"></tr>
                                          <tr class="spacer"></tr>
                                          <tr class="tr-shadow">
                                            <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
                                          </tr>

                                        <?php else: ?>

                                            <?php foreach($pending_bill_item as $bill_item):?>
                                            <tr class="tr-shadow" id="bill_item<?php echo $bill_item['id'] ?>">
                                                <td>
                                                    <i class="fa fa-times-circle text-danger"></i>
                                                </td>
                                                <td><?php echo $bill_item['product_name']; ?></td>
                                                <td><?php echo $bill_item['product_price']; ?></td>
                                                <td><?php echo $bill_item['product_quantity']; ?></td>
                                                <td><?php echo $bill_item['product_subtotal']; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                          
                                        </tbody>
                                    </table>
                                </div>      
                                <br>
                                <table width="100%" class="table">
                                  <tbody>
                                    <tr><td width="50%" style="background: #ecf0f1;">SubTotal: </td><td>
                                      <span style="float: left;"><?php echo $pending_bill->subtotal ?></span><i style="float: right;"><b><?php echo $pending_bill->total_item ?></b> items</i>
                                    </td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Order Tax: </td><td><?php echo $pending_bill->tax ?></td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Discount: </td><td><?php echo $pending_bill->discount ?></td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Total: </td><td><?php echo number_format($pending_bill->total_bill,2) ?></td></tr>
                                  </tbody>  
                                </table>
                                <div class="row" style="margin-top: 14px;">
                                  <div class="col-6"><button style="width: 100%;" class="btn btn-danger" onclick='CancelBill("<?php echo $id; ?>")'>Cancel</button></div>
                                  <div class="col-6"><button style="width: 100%;" class="btn btn-success">Payment</button></div>
                                </div>


        <?php

    }


    public function CreateNewBill(Request $request)
    {
        $user_id = session("login")["user_id"];
        $user_info = $this->checkUserAvailbility($user_id,$request);

        $new_bill = PendingBills::insertGetId(array(
                                "bill_code"     => $this->getBillCode($user_id),
                                "member_id"     => $user_id,
                                "customer_id"   => 0,
                                "tax"           => 0,
                                "discount"      => 0,
                                "subtotal"      => 0,
                                "total_bill"    => 0,
                                "total_item"    => 0,
                                "created_at"    => date("Y-m-d H:i:s"),
                                "updated_at"    => date("Y-m-d H:i:s"),

                    ));
        $customers = Customers::where('member_id',$user_id)->where('is_deleted',0)->get();


        ?>
                    <h5>Choose Client</h5>
                           <div class=" row form-group">
                           <div class="col-7"><select class="form-control" name="customer_list" id="customer_list">
                             <option value="0">Walk in Customer</option>
                             <?php foreach($customers as $cust): ?>
                             <option 
                              value="<?php echo $cust['id'] ?>"><?php echo $cust['customer_name'] ?></option>
                             <?php endforeach; ?>
                           </select></div>
                           <div class="col-5"> <input type="text" name="bar_code" class="form-control" placeholder="Enter Barcode"></div>
                           </div>
                    <div class="table-responsive" style="min-height: 200px; max-height: 200px;">
                                    <table class="table table-data2">
                                        <thead class="text-sm-center">
                                            <tr>
                                                <th>
                                                    
                                                </th>
                                                <th width="50%">Product</th>
                                                <th width="10%">Price</th>
                                                <th width="10%">Qty</th>
                                                <th width="30%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm-center">
                                         
                                          <tr class="spacer"></tr>
                                          <tr class="spacer"></tr>
                                          <tr class="tr-shadow">
                                            <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
                                          </tr>
                                        </tbody>
                                    </table>
                                </div>      
                                <br>
                                <table width="100%" class="table">
                                  <tbody>
                                    <tr><td width="50%" style="background: #ecf0f1;">SubTotal: </td><td>
                                      <span style="float: left;">0</span><i style="float: right;"><b>0</b> items</i>
                                    </td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Order Tax: </td><td>0</td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Discount: </td><td>0</td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Total: </td><td>0.00</td></tr>
                                  </tbody>  
                                </table>
                                <div class="row" style="margin-top: 14px;">
                                  <div class="col-6"><button style="width: 100%;" class="btn btn-danger">Cancel</button></div>
                                  <div class="col-6"><button style="width: 100%;" class="btn btn-success">Payment</button></div>
                                </div>

        <?php

    }



    public function DeleteLastBill(Request $request)
    {
        $user_id = session("login")["user_id"];
        $user_info = $this->checkUserAvailbility($user_id,$request);

        $last_bill = PendingBills::where('member_id',$user_id)->orderBy('bill_code','desc')->first();
        if ($last_bill == "") 
        {
            return "<p>Cannot Found Last Bill</p>";
        }

        PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$last_bill->id)->delete();
        PendingBills::where('member_id',$user_id)->where('id',$last_bill->id)->delete();

        $pending_bill = PendingBills::where('member_id',$user_id)->orderBy('bill_code','desc')->first();

        if ($pending_bill == "") 
        {
            return "<p>No Bill Found. Refresh Page</p>";
        }

        $pending_bill_item = PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$pending_bill->id)->get();


        $customers = Customers::where('member_id',$user_id)->where('is_deleted',0)->get();


        ?>
                    <h5>Choose Client</h5>
                           <div class=" row form-group">
                           <div class="col-7"><select class="form-control" name="customer_list" id="customer_list">
                             <option value="0">Walk in Customer</option>
                             <?php foreach($customers as $cust): ?>
                             <option <?php if ($pending_bill->customer_id == $cust['id']): ?>
                                 selected
                             <?php endif ?>
                              value="<?php echo $cust['id'] ?>"><?php echo $cust['customer_name'] ?></option>
                             <?php endforeach; ?>
                           </select></div>
                           <div class="col-5"> <input type="text" name="bar_code" class="form-control" placeholder="Enter Barcode"></div>
                           </div>
                    <div class="table-responsive" style="min-height: 200px; max-height: 200px;">
                                    <table class="table table-data2">
                                        <thead class="text-sm-center">
                                            <tr>
                                                <th>
                                                    
                                                </th>
                                                <th width="50%">Product</th>
                                                <th width="10%">Price</th>
                                                <th width="10%">Qty</th>
                                                <th width="30%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm-center">
                                            
                                        <?php if(count($pending_bill_item)==0): ?>
                                          <tr class="spacer"></tr>
                                          <tr class="spacer"></tr>
                                          <tr class="tr-shadow">
                                            <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
                                          </tr>

                                        <?php else: ?>

                                            <?php foreach($pending_bill_item as $bill_item):?>
                                            <tr class="tr-shadow" id="bill_item<?php echo $bill_item['id'] ?>">
                                                <td>
                                                    <i class="fa fa-times-circle text-danger"></i>
                                                </td>
                                                <td><?php echo $bill_item['product_name']; ?></td>
                                                <td><?php echo $bill_item['product_price']; ?></td>
                                                <td><?php echo $bill_item['product_quantity']; ?></td>
                                                <td><?php echo $bill_item['product_subtotal']; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                          
                                        </tbody>
                                    </table>
                                </div>      
                                <br>
                                <table width="100%" class="table">
                                  <tbody>
                                    <tr><td width="50%" style="background: #ecf0f1;">SubTotal: </td><td>
                                      <span style="float: left;"><?php echo $pending_bill->subtotal ?></span><i style="float: right;"><b><?php echo $pending_bill->total_item ?></b> items</i>
                                    </td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Order Tax: </td><td><?php echo $pending_bill->tax ?></td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Discount: </td><td><?php echo $pending_bill->discount ?></td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Total: </td><td><?php echo number_format($pending_bill->total_bill,2) ?></td></tr>
                                  </tbody>  
                                </table>
                                <div class="row" style="margin-top: 14px;">
                                  <div class="col-6"><button style="width: 100%;" class="btn btn-danger">Cancel</button></div>
                                  <div class="col-6"><button style="width: 100%;" class="btn btn-success">Payment</button></div>
                                </div>

        <?php

    }



    public function GetBillNavLinks(Request $request)
    {
        $user_id = session("login")["user_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $pending_bill      = PendingBills::where('member_id',$user_id)->get();
        $count = 1;
        foreach($pending_bill as $bill):
        ?>
                          <a class="nav-item nav-link <?php if ($count == count($pending_bill)): ?>
                              active
                          <?php endif ?>" id="nav-home-tab_" data-toggle="tab" href="#nav-home_" onclick='getBill("<?php echo $bill['id'] ?>")' role="tab" aria-controls="nav-home_" aria-selected="false"><?php echo $count; ?></a>
        <?php
        $count++;
        endforeach;
        ?>
                        &nbsp;&nbsp;
                          <a href="javascript:void(0)" class="nav-item nav-link btn btn-primary" onclick="createNewBill()">+</a>&nbsp;&nbsp;
                          <a href="javascript:void(0)" class="nav-item nav-link btn btn-danger" onclick="deleteLastBill()">-</a>
        <?php
    }




    public function CancelBill(Request $request,$id)
    {
        $user_id = session("login")["user_id"];
        $user_info = $this->checkUserAvailbility($user_id,$request);

        $get_bill = PendingBills::where('member_id',$user_id)->where('id',$id)->first();
        if ($get_bill == "") 
        {
            return array("status"=>"0","msg"=>"Something went wrong.");
            
        }

        PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$get_bill->id)->delete();

        PendingBills::where('member_id',$user_id)->where('id',$id)->update(array(
                                                                        'tax'       => 0,
                                                                        'discount'  => 0,
                                                                        'subtotal'  => 0,
                                                                        'total_bill'=> 0,
                                                                        'total_item'=> 0,
                                                                    ));
        return array("status"=>"1","msg"=>"Bill Cancelled Successfully.");
    }
















    public function getBillCode($id)
    {
        $last_bill = Sales::where('member_id',$id)->orderBy('bill_code','desc')->first();

        if ($last_bill == "") 
        {   

            $last_pending_bill = PendingBills::where('member_id',$id)->orderBy('bill_code','desc')->first();
            if ($last_pending_bill == "") 
            {
                return "00001";
            }
            else
            {
                return str_pad((int)$last_pending_bill->bill_code+1, 5, '0', STR_PAD_LEFT);
            }
        }
        else
        {
            return str_pad((int)$last_bill->bill_code+1, 5, '0', STR_PAD_LEFT);
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
    