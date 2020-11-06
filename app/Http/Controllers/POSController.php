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
                                        "tax_percentage"           => 0,
                                        "tax_amount"           => 0,
                                        "discount_percentage"      => 0,
                                        "discount_amount"      => 0,
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


                           <!-- ______________________________________________ -->
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <input type="hidden" name="current_bill_id" id="current_bill_id" value="<?php echo $id; ?>">
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <!-- ______________________________________________ -->


                           </div>
                    <div class="table-responsive" style="min-height: 200px; max-height: 200px;">
                                    <table class="table table-data2">
                                        <thead class="text-sm-center">
                                            <tr>
                                                <th width="5%">
                                                    
                                                </th>
                                                <th width="40%">Product</th>
                                                <th width="10%">Price</th>
                                                <th width="25%">Qty</th>
                                                <th width="20%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm-center" id="bill-prod-list<?php echo $id; ?>">
                                            
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
                                                    <i class="fa fa-times-circle text-danger" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
                                                </td>
                                                <td><?php echo $bill_item['product_name']; ?></td>
                                                <td><?php echo $bill_item['product_price']; ?></td>
                                                <td><div class="row" style="margin: auto; vertical-align: middle;">
                                                  <button class="btn btn-primary qty-btn" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>-</button><input class="form-control qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onfocusout='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><button class="btn btn-primary qty-btn" onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>+</button>
                                                  </div></td>
                                                <td><?php echo $bill_item['product_subtotal']; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                          
                                        </tbody>
                                    </table>
                                </div>      
                                <br>

                                <table width="100%" class="table">
                                  <tbody id="bill-summary-total<?php echo $id; ?>">
                                    <tr><td width="50%" style="background: #ecf0f1;">SubTotal: </td><td>
                                      <span style="float: left;"><?php echo $pending_bill->subtotal ?></span><i style="float: right;"><b><?php echo $pending_bill->total_item ?></b> items</i>
                                    </td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Order Tax: </td><td><div class="d-flex"><span style="float: left;"><input type="text" class="form-control tax-dis-input" onfocusout='ApplyBillTax(this.value,"<?php echo $pending_bill->id ?>")' id="bill-tax-input<?php echo $pending_bill->id ?>" value="<?php echo $pending_bill->tax_percentage ?>%"></span><i style="float: right;"><?php echo $pending_bill->tax_amount ?></i></div></td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Discount: </td><td><?php echo $pending_bill->discount_percentage ?></td></tr>
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
                                "tax_percentage"       => 0,
                                "tax_amount"           => 0,
                                "discount_percentage"  => 0,
                                "discount_amount"      => 0,
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
                           

                           <!-- ______________________________________________ -->
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <input type="hidden" name="current_bill_id" id="current_bill_id" value="<?php echo $new_bill; ?>">
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <!-- ______________________________________________ -->
                           

                           </div>
                    <div class="table-responsive" style="min-height: 200px; max-height: 200px;">
                                    <table class="table table-data2">
                                        <thead class="text-sm-center">
                                            <tr>
                                                <th width="5%">
                                                    
                                                </th>
                                                <th width="40%">Product</th>
                                                <th width="10%">Price</th>
                                                <th width="25%">Qty</th>
                                                <th width="20%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm-center" id="bill-prod-list<?php echo $new_bill; ?>">
                                         
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
                                  <tbody id="bill-summary-total<?php echo $new_bill; ?>">
                                    <tr><td width="50%" style="background: #ecf0f1;">SubTotal: </td><td>
                                      <span style="float: left;">0</span><i style="float: right;"><b>0</b> items</i>
                                    </td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Order Tax: </td><td><div class="d-flex"><span style="float: left;"><input type="text" class="form-control tax-dis-input" onfocusout='ApplyBillTax(this.value,"<?php echo $new_bill; ?>")' id="bill-tax-input<?php echo $new_bill; ?>" value="0%"></span><i style="float: right;">0</i></div></td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Discount: </td><td>0%</td></tr>
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

                           <!-- ______________________________________________ -->
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <input type="hidden" name="current_bill_id" id="current_bill_id" value="<?php echo $pending_bill->id; ?>">
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <!-- ______________________________________________ -->


                           </div>
                    <div class="table-responsive" style="min-height: 200px; max-height: 200px;">
                                    <table class="table table-data2">
                                        <thead class="text-sm-center">
                                            <tr>
                                                <th width="5%">
                                                    
                                                </th>
                                                <th width="40%">Product</th>
                                                <th width="10%">Price</th>
                                                <th width="25%">Qty</th>
                                                <th width="20%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm-center" id="bill-prod-list<?php echo $pending_bill->id; ?>">
                                            
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
                                                    <i class="fa fa-times-circle text-danger" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
                                                </td>
                                                <td><?php echo $bill_item['product_name']; ?></td>
                                                <td><?php echo $bill_item['product_price']; ?></td>
                                                <td><div class="row" style="margin: auto; vertical-align: middle;">
                                                  <button class="btn btn-primary qty-btn" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>-</button><input class="form-control qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onfocusout='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><button class="btn btn-primary qty-btn" onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>+</button>
                                                  </div></td>
                                                <td><?php echo $bill_item['product_subtotal']; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                          
                                        </tbody>
                                    </table>
                                </div>      
                                <br>
                                <table width="100%" class="table">
                                  <tbody id="bill-summary-total<?php echo $pending_bill->id; ?>">
                                    <tr><td width="50%" style="background: #ecf0f1;">SubTotal: </td><td>
                                      <span style="float: left;"><?php echo $pending_bill->subtotal ?></span><i style="float: right;"><b><?php echo $pending_bill->total_item ?></b> items</i>
                                    </td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Order Tax: </td><td><div class="d-flex"><span style="float: left;"><input type="text" class="form-control tax-dis-input" onfocusout='ApplyBillTax(this.value,"<?php echo $pending_bill->id ?>")' id="bill-tax-input<?php echo $pending_bill->id ?>" value="<?php echo $pending_bill->tax_percentage ?>%"></span><i style="float: right;"><?php echo $pending_bill->tax_amount ?></i></div></td></tr>
                                    <tr><td width="50%" style="background: #ecf0f1;">Discount: </td><td><?php echo $pending_bill->discount_percentage ?></td></tr>
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
                                                                        'tax_percentage'       => 0,
                                                                        'tax_amount'       => 0,
                                                                        'discount_percentage'  => 0,
                                                                        'discount_amount'  => 0,
                                                                        'subtotal'  => 0,
                                                                        'total_bill'=> 0,
                                                                        'total_item'=> 0,
                                                                    ));
        return array("status"=>"1","msg"=>"Bill Cancelled Successfully.");
    }




    //_____________ B I L L -- P R O D U C T S -- M E T H O D S ________________

    public function AddProductToBill(Request $request)
    {
        $user_id = session("login")["user_id"];
        $user_info = $this->checkUserAvailbility($user_id,$request);
        
        $input = $request->all();

        $get_bill = PendingBills::where('member_id',$user_id)->where('id',$input['bill_id'])->first();

        if ($get_bill == "") 
        {
          ?>
              <tr class="tr-shadow"><td colspan="5">Something went wrong.</td></tr>
          <?php
        }
        else
        {
          $check_prod = PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$input['bill_id'])->where('product_id',$input['prod_id'])->first();

          if ($check_prod == "") 
          {
              PendingBillItems::insert(array(
                                        'pending_bill_id'   => $input['bill_id'],
                                        "bill_code"         => $get_bill->bill_code,
                                        "member_id"         => $get_bill->member_id,
                                        "customer_id"       => $get_bill->customer_id,
                                        "product_id"        => $input['prod_id'],
                                        "product_name"      => $input['prod_name'],
                                        "product_price"     => $input['prod_price'],
                                        "product_quantity"  => 1,
                                        "product_subtotal"  => $input['prod_price'],
                                        "created_at"        => date("Y-m-d H:i:s"),
                                        "updated_at"        => date("Y-m-d H:i:s"),
                                      ));
          }
          else
          {
              PendingBillItems::where('member_id',$user_id)
                              ->where('pending_bill_id',$input['bill_id'])
                              ->where('product_id',$input['prod_id'])
                              ->update(array(
                                      "product_quantity" => $check_prod->product_quantity + 1,
                                      "product_subtotal" => $input['prod_price'] * ($check_prod->product_quantity + 1),
                                ));
          }

          $pending_bill_item = PendingBillItems::where('member_id',$user_id)
                          ->where('pending_bill_id',$input['bill_id'])
                          ->get();

          if(count($pending_bill_item)==0): 

          ?>
              <tr class="spacer"></tr>
              <tr class="spacer"></tr>
              <tr class="tr-shadow">
              <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
              </tr>

          <?php else: 
                foreach($pending_bill_item as $bill_item):
          ?>
              <tr class="tr-shadow" id="bill_item<?php echo $bill_item['id'] ?>">
              <td>
                <i class="fa fa-times-circle text-danger" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
              </td>
              <td><?php echo $bill_item['product_name']; ?></td>
              <td><?php echo $bill_item['product_price']; ?></td>
              <td><div class="row" style="margin: auto; vertical-align: middle;">
                                                  <button class="btn btn-primary qty-btn" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>-</button><input class="form-control qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onfocusout='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><button class="btn btn-primary qty-btn" onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>+</button>
                                                  </div></td>
              <td><?php echo $bill_item['product_subtotal']; ?></td>
              </tr>
          <?php endforeach; 
                endif; 



        }

    } 


    public function DeleteProductFromBill(Request $request)
    {
        $user_id = session("login")["user_id"];
        $user_info = $this->checkUserAvailbility($user_id,$request);
        
        $input = $request->all();

        $get_bill = PendingBills::where('member_id',$user_id)->where('id',$input['bill_id'])->first();

        if ($get_bill == "") 
        {
          ?>
              <tr class="tr-shadow"><td colspan="5">Something went wrong.</td></tr>
          <?php
        }
        else
        {
          $check_prod = PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$input['bill_id'])->where('id',$input['item_id'])->delete();


          $pending_bill_item = PendingBillItems::where('member_id',$user_id)
                          ->where('pending_bill_id',$input['bill_id'])
                          ->get();

          if(count($pending_bill_item)==0): 

          ?>
              <tr class="spacer"></tr>
              <tr class="spacer"></tr>
              <tr class="tr-shadow">
              <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
              </tr>

          <?php else: 
                foreach($pending_bill_item as $bill_item):
          ?>
              <tr class="tr-shadow" id="bill_item<?php echo $bill_item['id'] ?>">
              <td>
                <i class="fa fa-times-circle text-danger" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
              </td>
              <td><?php echo $bill_item['product_name']; ?></td>
              <td><?php echo $bill_item['product_price']; ?></td>
              <td><div class="row" style="margin: auto; vertical-align: middle;">
                                                  <button class="btn btn-primary qty-btn" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>-</button><input class="form-control qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onfocusout='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><button class="btn btn-primary qty-btn" onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>+</button>
                                                  </div></td>
              <td><?php echo $bill_item['product_subtotal']; ?></td>
              </tr>
          <?php endforeach; 
                endif; 



        }

    }     

    public function DecreaseBillProductItem(Request $request)
    {
      $user_id = session("login")["user_id"];
      $user_info = $this->checkUserAvailbility($user_id,$request);
        
      $input = $request->all();

      $get_bill_item = PendingBillItems::where('member_id',$user_id)->where('id',$input['bill_item_id'])->first();
      if ($get_bill_item == "") 
      {
        ?>
              <tr class="tr-shadow"><td colspan="5">Something went wrong.</td></tr>
        <?php
      }
      else
      {
        PendingBillItems::where('id',$input['bill_item_id'])->update(array(
                                        'product_quantity'=>$get_bill_item->product_quantity -1,
                                        'product_subtotal' => $get_bill_item->product_subtotal - $get_bill_item->product_price,
                                                              ));
         $pending_bill_item = PendingBillItems::where('member_id',$user_id)
                          ->where('pending_bill_id',$input['bill_id'])
                          ->get();

          if(count($pending_bill_item)==0): 

          ?>
              <tr class="spacer"></tr>
              <tr class="spacer"></tr>
              <tr class="tr-shadow">
              <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
              </tr>

          <?php else: 
                foreach($pending_bill_item as $bill_item):
          ?>
              <tr class="tr-shadow" id="bill_item<?php echo $bill_item['id'] ?>">
              <td>
                <i class="fa fa-times-circle text-danger" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
              </td>
              <td><?php echo $bill_item['product_name']; ?></td>
              <td><?php echo $bill_item['product_price']; ?></td>
              <td><div class="row" style="margin: auto; vertical-align: middle;">
                                                  <button class="btn btn-primary qty-btn" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>-</button><input class="form-control qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onfocusout='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><button class="btn btn-primary qty-btn" onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>+</button>
                                                  </div></td>
              <td><?php echo $bill_item['product_subtotal']; ?></td>
              </tr>
          <?php endforeach; 
                endif; 

      }
    }

    public function IncreaseBillProductItem(Request $request)
    {
      $user_id = session("login")["user_id"];
      $user_info = $this->checkUserAvailbility($user_id,$request);
        
      $input = $request->all();

      $get_bill_item = PendingBillItems::where('member_id',$user_id)->where('id',$input['bill_item_id'])->first();
      if ($get_bill_item == "") 
      {
        ?>
              <tr class="tr-shadow"><td colspan="5">Something went wrong.</td></tr>
          <?php
      }
      else
      {
        PendingBillItems::where('id',$input['bill_item_id'])->update(array(
                                        'product_quantity'=>$get_bill_item->product_quantity + 1,
                                        'product_subtotal' => $get_bill_item->product_subtotal + $get_bill_item->product_price,
                                                              ));
        $pending_bill_item = PendingBillItems::where('member_id',$user_id)
                          ->where('pending_bill_id',$input['bill_id'])
                          ->get();

          if(count($pending_bill_item)==0): 

          ?>
              <tr class="spacer"></tr>
              <tr class="spacer"></tr>
              <tr class="tr-shadow">
              <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
              </tr>

          <?php else: 
                foreach($pending_bill_item as $bill_item):
          ?>
              <tr class="tr-shadow" id="bill_item<?php echo $bill_item['id'] ?>">
              <td>
                <i class="fa fa-times-circle text-danger" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
              </td>
              <td><?php echo $bill_item['product_name']; ?></td>
              <td><?php echo $bill_item['product_price']; ?></td>
              <td><div class="row" style="margin: auto; vertical-align: middle;">
                                                  <button class="btn btn-primary qty-btn" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>-</button><input class="form-control qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onfocusout='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><button class="btn btn-primary qty-btn" onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>+</button>
                                                  </div></td>
              <td><?php echo $bill_item['product_subtotal']; ?></td>
              </tr>
          <?php endforeach; 
                endif; 

      }
    }

    public function ChangeBillProductQuantity(Request $request)
    {
      $user_id = session("login")["user_id"];
      $user_info = $this->checkUserAvailbility($user_id,$request);
        
      $input = $request->all();

      $get_bill_item = PendingBillItems::where('member_id',$user_id)->where('id',$input['bill_item_id'])->first();
      if ($get_bill_item == "") 
      {
        ?>
              <tr class="tr-shadow"><td colspan="5">Something went wrong.</td></tr>
        <?php
      }
      else
      {
        PendingBillItems::where('id',$input['bill_item_id'])->update(array(
                                        'product_quantity'=>$input['prod_qty'],
                                        'product_subtotal' => $get_bill_item->product_price * $input['prod_qty'],
                                                              ));
        $pending_bill_item = PendingBillItems::where('member_id',$user_id)
                          ->where('pending_bill_id',$input['bill_id'])
                          ->get();

          if(count($pending_bill_item)==0): 

          ?>
              <tr class="spacer"></tr>
              <tr class="spacer"></tr>
              <tr class="tr-shadow">
              <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
              </tr>

          <?php else: 
                foreach($pending_bill_item as $bill_item):
          ?>
              <tr class="tr-shadow" id="bill_item<?php echo $bill_item['id'] ?>">
              <td>
                <i class="fa fa-times-circle text-danger" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
              </td>
              <td><?php echo $bill_item['product_name']; ?></td>
              <td><?php echo $bill_item['product_price']; ?></td>
              <td><div class="row" style="margin: auto; vertical-align: middle;">
                                                  <button class="btn btn-primary qty-btn" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>-</button><input class="form-control qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onfocusout='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><button class="btn btn-primary qty-btn" onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'>+</button>
                                                  </div></td>
              <td><?php echo $bill_item['product_subtotal']; ?></td>
              </tr>
          <?php endforeach; 
                endif; 

      }
    }

    // _________________________________________________________________________
    


    //_____________ B I L L -- T A X -- M E T H O D S ________________

    public function ApplyBillTax(Request $request)
    {
      $user_id = session("login")["user_id"];

      $user_info = $this->checkUserAvailbility($user_id,$request);

      $input = $request->all();

      $get_bill = PendingBills::where('member_id',$user_id)->where('id',$input['bill_id'])->first();
      if ($get_bill == "") 
      {
            return array("status"=>"0","msg"=>"Something went wrong.");
      }
      else
      {

        PendingBills::where('member_id',$user_id)
                    ->where('id',$input['bill_id'])
                    ->update(array(
                          "tax_percentage"  => $input['tax'],
                      ));
      }
    }

    // _________________________________________________________________________

    public function CalculateTotalBill(Request $request, $id)
    {
        $user_id = session("login")["user_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $get_bill = PendingBills::where('member_id',$user_id)->where('id',$id)->first();

        if ($get_bill == "") 
        {
          ?>
          <tr><td colspan="2">Something went wrong.</td></tr>
          <?php
        }
        else
        {
          $subtotal = PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$id)->sum('product_subtotal');
          $total_item = PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$id)->count();

          $tax_amount = $subtotal * ((int)$get_bill->tax_percentage/100);


          $total_bill = $subtotal + $tax_amount;

          PendingBills::where('member_id',$user_id)
                      ->where('id',$id)
                      ->update(array(
                          "subtotal"    => $subtotal,
                          "total_bill"  => $total_bill,
                          "tax_amount"  => $tax_amount,
                          "total_item"  => $total_item,
                        ));




          ?>
            <tr>
              <td width="50%" style="background: #ecf0f1;">SubTotal: </td>
              <td>
                <span style="float: left;"><?php echo $subtotal; ?></span>
                <i style="float: right;"><b><?php echo $total_item; ?></b> items</i>
              </td>
            </tr>
            
            <tr>
              <td width="50%" style="background: #ecf0f1;">Order Tax: </td>
              <td><div class="d-flex"><span style="float: left;"><input type="text" class="form-control tax-dis-input" onfocusout='ApplyBillTax(this.value,"<?php echo $id ?>")' id="bill-tax-input<?php echo $id ?>" value="<?php echo $get_bill->tax_percentage ?>%"></span><i style="float: right;"><?php echo $tax_amount ?></i></div></td>
            </tr>
            
            <tr>
              <td width="50%" style="background: #ecf0f1;">Discount: </td>
              <td>0%</td>
            </tr>
            <tr>
              <td width="50%" style="background: #ecf0f1;">Total: </td>
              <td><?php echo number_format($total_bill,2) ?></td>
            </tr>
          
          <?php


        }

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
    