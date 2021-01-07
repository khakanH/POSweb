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

      $company_id = session("login")["company_id"];


      $user_info = $this->checkUserAvailbility($user_id,$request);

    	$company  = CompanyInfo::where('id',$company_id)->first();
    	$category = Category::where('company_id',$company_id)->where('is_deleted',0)->get();
    	$product  = Product::where('company_id',$company_id)->where('is_deleted',0)->limit(20)->get();

        

        if (PendingBills::where('company_id',$company_id)->count() == 0) 
        {
            PendingBills::insert(array(
                                        "bill_code"      => $this->getBillCode($company_id),
                                        "member_id"     => $user_id,
                                        "company_id"    => $company_id,
                                        "customer_id"   => 0,
                                        "tax_percentage"       => $company->default_tax,
                                        "tax_amount"           => 0,
                                        "discount_percentage"  => $company->default_discount,
                                        "discount_amount"      => 0,
                                        "subtotal"      => 0,
                                        "total_bill"    => 0,
                                        "total_item"    => 0,
                                        "created_at"    => date("Y-m-d H:i:s"),
                                        "updated_at"    => date("Y-m-d H:i:s"),
                                ));
        }
       
        $pending_bill      = PendingBills::where('company_id',$company_id)->get();
        $pending_bill_item = PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$pending_bill[0]['id'])->get();

        $customers = Customers::where('company_id',$company_id)->where('is_deleted',0)->get();

        return view('pos',compact('company','category','product','pending_bill','pending_bill_item','customers'));
    }



    public function GetPOSProductList(Request $request,$cate_id,$search)
    {
        try 
        {   
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($cate_id)) 
            {       
            	if (empty($search)) 
            	{
                    $get_prod_list = Product::where('company_id',$company_id)
                                      ->where('is_deleted',0)
                                      ->limit(20)
                                      ->get();
            	}
            	else 
            	{
            		$get_prod_list = Product::where('company_id',$company_id)
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
            		$get_prod_list = Product::where('company_id',$company_id)
                                      ->where('is_deleted',0)
                                      ->where('category_id',$cate_id)
                                      ->limit(20)
                                      ->get();
                }
                else 
                {
                	$get_prod_list = Product::where('company_id',$company_id)
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

                  <div class="col-lg-4">
                                              <div class="card" style="cursor: pointer;" onclick='AddProductToBill("<?php echo $prod['id'] ?>","<?php echo $prod['name'] ?>","<?php echo $prod['price'] ?>")'>
                                    <div class="">
                                        <div class="">
                                            <img style="height: 70px; margin: 5px;" class="rounded-circle mx-auto d-block" src="<?php echo env('IMG_URL') ?><?php echo $prod['image'] ?>" width="70" height="70" alt="<?php echo $prod['name'] ?>">
                                            <hr>
                                            <center><span><?php echo $prod['name'] ?></span></center>
                                            <center><span><?php echo $prod['price'] ?>/-</span></center>
                                            
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

    public function GetPOSProductByBarcode(Request $request,$barcode)
    {
        $user_id = session("login")["user_id"];
        $company_id = session("login")["company_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request); 


        $get_prod = Product::where('company_id',$company_id)
                                      ->where('is_deleted',0)
                                      ->where('product_code',(string)$barcode)
                                      ->first();

        if ($get_prod == "") 
        {
            return array("status"=>"0","msg"=>"Product Not Found");
        }
        else
        {
            return array("status"=>"1","msg"=>"Product Found","result"=>$get_prod);
        }
    }


    public function GetPendingBill(Request $request,$id)
    {
        $user_id = session("login")["user_id"];
        $company_id = session("login")["company_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $pending_bill      = PendingBills::where('company_id',$company_id)->where('id',$id)->first();

        if ($pending_bill == "") 
        {
            return "<p>No Bill Found</p>";
        }

        $pending_bill_item = PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$pending_bill->id)->get();

        $customers = Customers::where('company_id',$company_id)->where('is_deleted',0)->get();


        ?> 

                 <div class="row">
                           <div class="col-7">&nbsp;</div>
                           <div class="col-5"><i  style="float: right; padding-right: 5px; cursor: pointer;" onclick="CreateNewCustomer()" data-toggle="tooltip" title="Create Customer" class="fa fa-user"></i> <i  style="float: right; padding-right: 10px; cursor: pointer;" data-toggle="tooltip" title="Show Last Bill" onclick="ShowLastBill()" class="fa fa-list-alt"></i></div>
                          </div>


                           <div class=" row form-group">
                           <div class="col-12 mb-2 pos-right-search">
                            <input type="hidden" name="customer_list" id="customer_list" value="<?php echo $pending_bill->customer_id ?>">
                            <input type="text" id="cust_live_search_field" value='<?php echo isset($pending_bill->customer_name->customer_name)?$pending_bill->customer_name->customer_name:"Walk In Customer" ?>' autocomplete="off" class="form-control" onkeyup='LiveSearchCustomer(this.value)'  onfocusout="CheckSelectedCustomer(this.value)">
                            <div id="customer-search-list" style="position: absolute;border: solid lightgray 1px;width: 95%; height: auto; background: #fff;display: none;"></div>
                         </div>
                           <div class="col-12 pos-right-search"> <input type="text" autofocus="on" name="bar_code" id="bar_code" onkeypress="AddProductToBillBarCode(this.value)" class="form-control" placeholder="Enter Barcode"></div>


                           <!-- ______________________________________________ -->
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <input type="hidden" name="current_bill_id" id="current_bill_id" value="<?php echo $id; ?>">
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <!-- ______________________________________________ -->


                           </div>

                           <br>
                      <div class="table-responsive" style="min-height: 270px; max-height: 270px;">
                                    <table class="table table-1 table-sm">
                                         <thead class="">
                                            <tr>
                                                
                                                <th width="40%">Product</th>
                                                <th width="5%">Price</th>
                                                <th width="40%">Qty</th>
                                                <th width="10%">Total</th>
                                                <th width="15%">
                                                    <i class="fas fa-trash"></i>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody  id="bill-prod-list<?php echo $id; ?>">
                                            
                                        <?php if(count($pending_bill_item)==0): ?>
                                          <tr class="spacer"></tr>
                                          <tr class="spacer"></tr>
                                          <tr class="tr-shadow">
                                            <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
                                          </tr>

                                        <?php else: ?>

                                            <?php foreach($pending_bill_item as $bill_item):?>
                                            <tr class="tr-shadow" id="bill_item<?php echo $bill_item['id'] ?>">
                                                
                                                <td><?php echo $bill_item['product_name']; ?></td>
                                                <td><?php echo $bill_item['product_price']; ?></td>
                                                <td><div class="" style="margin: auto; vertical-align: middle;">
                                                  <span style="cursor: pointer;" class="fa fa-minus" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span><input class="qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onkeypress='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><span style="cursor: pointer;" class="fa fa-plus" onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span>
                                                  </div></td>
                                                <td><?php echo $bill_item['product_subtotal']; ?></td>
                                                <td>
                                                    <i class="fa fa-times" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                          
                                        </tbody>
                                    </table>
                                </div>      
                                <br>
                                <br>

                                <table width="100%" class="table-2 table table-sm bottom-table">
                                  <tbody id="bill-summary-total<?php echo $id; ?>">
                                    
                                    <tr class="tr-1">
                                      <td width="35%">Total Items: </td>
                                      <td width="25%" style="text-align: center;"><?php echo  $pending_bill->total_item ?></td>
                                      <td width="20%" style="text-align: center;">Subtotal: </td>
                                      <td width="20%" style="text-align: right;"><?php echo  $pending_bill->subtotal ?></td>
                                    </tr>

                                    <tr>
                                      <td >Total Tax: </td>
                                      <td style="text-align: center;" colspan="2"><input autocomplete="off" type="text" class="form-control tax-dis-input" onkeypress='ApplyBillTax(this.value,"<?php echo  $pending_bill->id ?>")' id="bill-tax-input<?php echo  $pending_bill->id ?>" value="<?php echo  number_format($pending_bill->tax_percentage,1) ?>%"></td>
                                        <td style="text-align: left;"><i style="float: right;"><?php echo  number_format($pending_bill->tax_amount,2) ?></i></td>
                                    </tr>


                                    <tr>
                                      <td >Bill Discount: </td>
                                      <td style="text-align: center;" colspan="2"><input autocomplete="off" type="text" class="form-control tax-dis-input" onkeypress='ApplyBillDiscount(this.value,"<?php echo $pending_bill->id ?>")' id="bill-discount-input<?php echo $pending_bill->id ?>" value="<?php echo number_format($pending_bill->discount_percentage,1) ?>%"></td>
                                        <td style="text-align: center;"><i style="float: right;"><?php echo number_format($pending_bill->discount_amount,2) ?></i></td>
                                    </tr>


                                     <tr>
                                    <td colspan="2"><b>Total Payable:</b> </td>
                                    <td colspan="2"><?php echo number_format($pending_bill->total_bill,2) ?></td>
                                   </tr>
                                  </tbody>  
                                </table>


                                <div class="row" style="margin-top: 14px;">
                                 
                                  <div class="col-6"><button style="width: 100%;" class="pos-btn btn-green btn btn-primary" id="payment_button<?php echo $id; ?>" <?php if ($pending_bill->total_item == 0): ?>
                                    disabled=""
                                  <?php endif ?> onclick='PayBill("<?php echo $id; ?>")'>Payment</button></div>
                                   <div class="col-6"><button style="width: 100%;" class="pos-btn btn-red  btn btn-primary" onclick='CancelBill("<?php echo $id; ?>")'>Delete Sale</button></div>
                                </div>


        <?php

    }


    public function CreateNewBill(Request $request)
    {
        $user_id = session("login")["user_id"];
        $company_id = session("login")["company_id"];
        $user_info = $this->checkUserAvailbility($user_id,$request);

        
        $company  = CompanyInfo::where('id',$company_id)->first();

        $new_bill = PendingBills::insertGetId(array(
                                "bill_code"     => $this->getBillCode($company_id),
                                "member_id"     => $user_id,
                                "company_id"    => $company_id,
                                "customer_id"   => 0,
                                "tax_percentage"       => $company->default_tax,
                                "tax_amount"           => 0,
                                "discount_percentage"  => $company->default_discount,
                                "discount_amount"      => 0,
                                "subtotal"      => 0,
                                "total_bill"    => 0,
                                "total_item"    => 0,
                                "created_at"    => date("Y-m-d H:i:s"),
                                "updated_at"    => date("Y-m-d H:i:s"),

                    ));
        $customers = Customers::where('company_id',$company_id)->where('is_deleted',0)->get();


        ?>
                  
                          <div class="row">
                           <div class="col-7">&nbsp;</div>
                           <div class="col-5"><i  style="float: right; padding-right: 5px; cursor: pointer;" onclick="CreateNewCustomer()" data-toggle="tooltip" title="Create Customer" class="fa fa-user"></i> <i  style="float: right; padding-right: 10px; cursor: pointer;" data-toggle="tooltip" title="Show Last Bill" onclick="ShowLastBill()" class="fa fa-list-alt"></i></div>
                          </div>

                           <div class=" row form-group">
                           <div class="col-12 mb-2 pos-right-search">
                             <input type="hidden" name="customer_list" id="customer_list" value="0">
                            <input type="text" id="cust_live_search_field" value='Walk In Customer' class="form-control" autocomplete="off" onkeyup='LiveSearchCustomer(this.value)' onfocusout="CheckSelectedCustomer(this.value)">
                            <div id="customer-search-list" style="position: absolute;border: solid lightgray 1px;width: 95%; height: auto; background: #fff;display: none;"></div>
                           </div>
                           <div class="col-12 pos-right-search"> <input type="text" autofocus="on" name="bar_code" id="bar_code" onkeypress="AddProductToBillBarCode(this.value)" class="form-control" placeholder="Enter Barcode"></div>
                           

                           <!-- ______________________________________________ -->
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <input type="hidden" name="current_bill_id" id="current_bill_id" value="<?php echo $new_bill; ?>">
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <!-- ______________________________________________ -->
                           

                           </div>
                        
                        <br>

                    <div class="table-responsive" style="min-height: 270px; max-height: 270px;">
                                     <table class="table table-1 table-sm">
                                         <thead class="">
                                            <tr>
                                                
                                                <th width="40%">Product</th>
                                                <th width="5%">Price</th>
                                                <th width="40%">Qty</th>
                                                <th width="10%">Total</th>
                                                <th width="15%">
                                                    <i class="fas fa-trash"></i>
                                                </th>
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
                                <br>


                                <table width="100%" class="table-2 table table-sm bottom-table">
                                  <tbody id="bill-summary-total<?php echo $new_bill; ?>">
                                    
                                    <tr class="tr-1">
                                      <td width="35%">Total Items: </td>
                                      <td width="25%" style="text-align: center;">0</td>
                                      <td width="20%" style="text-align: center;">Subtotal: </td>
                                      <td width="20%" style="text-align: right;">0</td>
                                    </tr>


                                    <tr>
                                      <td >Total Tax: </td>
                                      <td style="text-align: center;" colspan="2"><input autocomplete="off" type="text" class="form-control tax-dis-input" onkeypress='ApplyBillTax(this.value,"<?php echo $new_bill; ?>")' id="bill-tax-input<?php echo $new_bill; ?>" value="<?php echo number_format($company->default_tax,1); ?>%"></td>
                                        <td style="text-align: left;"><i style="float: right;">0.00</i></td>
                                    </tr>

                                    <tr>
                                      <td >Bill Discount: </td>
                                      <td style="text-align: center;" colspan="2"><input autocomplete="off" type="text" class="form-control tax-dis-input" onkeypress='ApplyBillDiscount(this.value,"<?php echo $new_bill; ?>")' id="bill-discount-input<?php echo $new_bill; ?>" value="<?php echo number_format($company->default_discount,1); ?>%"></td>
                                        <td style="text-align: center;"><i style="float: right;">0.00</i></td>
                                    </tr>
                                    
                                    
                                   <tr>
                                    <td colspan="2"><b>Total Payable:</b> </td>
                                    <td colspan="2">0.00</td>
                                   </tr>

                                  </tbody>  
                                </table>


                                <div class="row" style="margin-top: 14px;">
                                 
                                  <div class="col-6"><button style="width: 100%;" class="pos-btn btn-green btn btn-primary" disabled="" id="payment_button<?php echo $new_bill; ?>" onclick='PayBill("<?php echo $new_bill; ?>")'>Payment</button></div>
                                   <div class="col-6"><button style="width: 100%;" class="pos-btn btn-red  btn btn-primary" onclick='CancelBill("<?php echo $new_bill; ?>")'>Delete Sale</button></div>
                                </div>

        <?php

    }



    public function DeleteLastBill(Request $request)
    {
        $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];
        $user_info = $this->checkUserAvailbility($user_id,$request);

        $last_bill = PendingBills::where('company_id',$company_id)->orderBy('id','desc')->first();
        if ($last_bill == "") 
        {
            return "<p>Cannot Found Last Bill</p>";
        }

        PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$last_bill->id)->delete();
        PendingBills::where('company_id',$company_id)->where('id',$last_bill->id)->delete();

        $pending_bill = PendingBills::where('company_id',$company_id)->orderBy('bill_code','desc')->first();

        if ($pending_bill == "") 
        {
            return "<p>No Bill Found. Refresh Page</p>";
        }

        $pending_bill_item = PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$pending_bill->id)->get();


        $customers = Customers::where('company_id',$company_id)->where('is_deleted',0)->get();


        ?>
                   <div class="row">
                           <div class="col-7">&nbsp;</div>
                           <div class="col-5"><i  style="float: right; padding-right: 5px; cursor: pointer;" onclick="CreateNewCustomer()" data-toggle="tooltip" title="Create Customer" class="fa fa-user"></i> <i  style="float: right; padding-right: 10px; cursor: pointer;" data-toggle="tooltip" title="Show Last Bill" onclick="ShowLastBill()" class="fa fa-list-alt"></i></div>
                          </div>


                           <div class=" row form-group">
                           <div class="col-12 mb-2 pos-right-search">
                            <input type="hidden" name="customer_list" id="customer_list" value="<?php echo $pending_bill->customer_id ?>">
                            <input type="text" id="cust_live_search_field" value='<?php echo isset($pending_bill->customer_name->customer_name)?$pending_bill->customer_name->customer_name:"Walk In Customer" ?>' autocomplete="off" class="form-control" onkeyup='LiveSearchCustomer(this.value)'  onfocusout="CheckSelectedCustomer(this.value)">
                            <div id="customer-search-list" style="position: absolute;border: solid lightgray 1px;width: 95%; height: auto; background: #fff;display: none;"></div>
                         </div>
                           <div class="col-12 pos-right-search"> <input type="text" autofocus="on" name="bar_code" id="bar_code" onkeypress="AddProductToBillBarCode(this.value)" class="form-control" placeholder="Enter Barcode"></div>


                           <!-- ______________________________________________ -->
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <input type="hidden" name="current_bill_id" id="current_bill_id" value="<?php echo $pending_bill->id; ?>">
                           <!-- H I D D E N -- C U R R E N T -- B I L L -- I D -->
                           <!-- ______________________________________________ -->


                           </div>

                           <br>
                      <div class="table-responsive" style="min-height: 270px; max-height: 270px;">
                                    <table class="table table-1 table-sm">
                                         <thead class="">
                                            <tr>
                                                
                                                <th width="40%">Product</th>
                                                <th width="5%">Price</th>
                                                <th width="40%">Qty</th>
                                                <th width="10%">Total</th>
                                                <th width="15%">
                                                    <i class="fas fa-trash"></i>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody  id="bill-prod-list<?php echo $pending_bill->id; ?>">
                                            
                                        <?php if(count($pending_bill_item)==0): ?>
                                          <tr class="spacer"></tr>
                                          <tr class="spacer"></tr>
                                          <tr class="tr-shadow">
                                            <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
                                          </tr>

                                        <?php else: ?>

                                            <?php foreach($pending_bill_item as $bill_item):?>
                                            <tr class="tr-shadow" id="bill_item<?php echo $bill_item['id'] ?>">
                                                
                                                <td><?php echo $bill_item['product_name']; ?></td>
                                                <td><?php echo $bill_item['product_price']; ?></td>
                                                <td><div class="" style="margin: auto; vertical-align: middle;">
                                                  <span style="cursor: pointer;" class="fa fa-minus" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span><input class="qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onkeypress='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><span style="cursor: pointer;" class="fa fa-plus" onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span>
                                                  </div></td>
                                                <td><?php echo $bill_item['product_subtotal']; ?></td>
                                                <td>
                                                    <i class="fa fa-times" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                          
                                        </tbody>
                                    </table>
                                </div>      
                                <br>
                                <br>

                                <table width="100%" class="table-2 table table-sm bottom-table">
                                  <tbody id="bill-summary-total<?php echo $pending_bill->id; ?>">
                                    
                                    <tr class="tr-1">
                                      <td width="35%">Total Items: </td>
                                      <td width="25%" style="text-align: center;"><?php echo  $pending_bill->total_item ?></td>
                                      <td width="20%" style="text-align: center;">Subtotal: </td>
                                      <td width="20%" style="text-align: right;"><?php echo  $pending_bill->subtotal ?></td>
                                    </tr>

                                    <tr>
                                      <td >Total Tax: </td>
                                      <td style="text-align: center;" colspan="2"><input autocomplete="off" type="text" class="form-control tax-dis-input" onkeypress='ApplyBillTax(this.value,"<?php echo  $pending_bill->id ?>")' id="bill-tax-input<?php echo  $pending_bill->id ?>" value="<?php echo  number_format($pending_bill->tax_percentage,1) ?>%"></td>
                                        <td style="text-align: left;"><i style="float: right;"><?php echo  number_format($pending_bill->tax_amount,2) ?></i></td>
                                    </tr>


                                    <tr>
                                      <td >Bill Discount: </td>
                                      <td style="text-align: center;" colspan="2"><input autocomplete="off" type="text" class="form-control tax-dis-input" onkeypress='ApplyBillDiscount(this.value,"<?php echo $pending_bill->id ?>")' id="bill-discount-input<?php echo $pending_bill->id ?>" value="<?php echo number_format($pending_bill->discount_percentage,1) ?>%"></td>
                                        <td style="text-align: center;"><i style="float: right;"><?php echo number_format($pending_bill->discount_amount,2) ?></i></td>
                                    </tr>


                                     <tr>
                                    <td colspan="2"><b>Total Payable:</b> </td>
                                    <td colspan="2"><?php echo number_format($pending_bill->total_bill,2) ?></td>
                                   </tr>
                                  </tbody>  
                                </table>


                                <div class="row" style="margin-top: 14px;">
                                 
                                  <div class="col-6"><button style="width: 100%;" class="pos-btn btn-green btn btn-primary" id="payment_button<?php echo $pending_bill->id; ?>" <?php if ($pending_bill->total_item == 0): ?>
                                    disabled=""
                                  <?php endif ?> onclick='PayBill("<?php echo $pending_bill->id; ?>")'>Payment</button></div>
                                   <div class="col-6"><button style="width: 100%;" class="pos-btn btn-red  btn btn-primary" onclick='CancelBill("<?php echo $pending_bill->id; ?>")'>Delete Sale</button></div>
                                </div>

        <?php

    }



    public function GetBillNavLinks(Request $request)
    {
        $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $pending_bill      = PendingBills::where('company_id',$company_id)->orderBy('id','asc')->get();
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
            $company_id = session("login")["company_id"];
        $user_info = $this->checkUserAvailbility($user_id,$request);

        $company  = CompanyInfo::where('id',$company_id)->first();

        $get_bill = PendingBills::where('company_id',$company_id)->where('id',$id)->first();
        if ($get_bill == "") 
        {
            return array("status"=>"0","msg"=>"Something went wrong.");
            
        }

        PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$get_bill->id)->delete();

        PendingBills::where('company_id',$company_id)->where('id',$id)->update(array(
                                                                        'tax_percentage'       => $company->default_tax,
                                                                        'tax_amount'       => 0,
                                                                        'discount_percentage'  => $company->default_discount,
                                                                        'discount_amount'  => 0,
                                                                        'subtotal'  => 0,
                                                                        'total_bill'=> 0,
                                                                        'total_item'=> 0,
                                                                        'customer_id' => 0,
                                                                    ));
        return array("status"=>"1","msg"=>"Bill Cancelled Successfully.");
    }




    //_____________ B I L L -- P R O D U C T S -- M E T H O D S ________________

    public function AddProductToBill(Request $request)
    {
        $user_id = session("login")["user_id"];
        $user_info = $this->checkUserAvailbility($user_id,$request);
        
        $company_id = session("login")["company_id"];
        
        $input = $request->all();

        $get_bill = PendingBills::where('company_id',$company_id)->where('id',$input['bill_id'])->first();

        if ($get_bill == "") 
        {
          ?>
              <tr class="tr-shadow"><td colspan="5">Something went wrong.</td></tr>
          <?php
        }
        else
        {
          $check_prod = PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$input['bill_id'])->where('product_id',$input['prod_id'])->first();

          if ($check_prod == "") 
          {
              PendingBillItems::insert(array(
                                        'pending_bill_id'   => $input['bill_id'],
                                        "bill_code"         => $get_bill->bill_code,
                                        "member_id"         => $get_bill->member_id,
                                        "company_id"        => $get_bill->company_id,
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
              PendingBillItems::where('company_id',$company_id)
                              ->where('pending_bill_id',$input['bill_id'])
                              ->where('product_id',$input['prod_id'])
                              ->update(array(
                                      "product_quantity" => $check_prod->product_quantity + 1,
                                      "product_subtotal" => $input['prod_price'] * ($check_prod->product_quantity + 1),
                                ));
          }

          $pending_bill_item = PendingBillItems::where('company_id',$company_id)
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
            
              <td><?php echo $bill_item['product_name']; ?></td>
              <td><?php echo $bill_item['product_price']; ?></td>
              <td><div class="" style="margin: auto; vertical-align: middle;">
                                                  <span style="cursor: pointer;" class="fa fa-minus" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span><input class="qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onkeypress='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><span style="cursor: pointer;" class="fa fa-plus"  onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span>
                                                  </div></td>
              <td><?php echo $bill_item['product_subtotal']; ?></td>
                <td>
                <i class="fa fa-times" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
              </td>
              </tr>
          <?php endforeach; 
                endif; 



        }

    } 


    public function DeleteProductFromBill(Request $request)
    {
        $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];
        $user_info = $this->checkUserAvailbility($user_id,$request);
        
        $input = $request->all();

        $get_bill = PendingBills::where('company_id',$company_id)->where('id',$input['bill_id'])->first();

        if ($get_bill == "") 
        {
          ?>
              <tr class="tr-shadow"><td colspan="5">Something went wrong.</td></tr>
          <?php
        }
        else
        {
          $check_prod = PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$input['bill_id'])->where('id',$input['item_id'])->delete();


          $pending_bill_item = PendingBillItems::where('company_id',$company_id)
                          ->where('pending_bill_id',$input['bill_id'])
                          ->get();

          if(count($pending_bill_item)==0): 

          ?>
              <tr class="spacer"></tr>
              <tr class="spacer"></tr>
              <tr class="tr-shadow">
              <td colspan="5"><h5>Empty List</h5><pre>Select Product</pre></td>
              </tr>
              <script type="text/javascript">
                (function(){
                            document.getElementById("payment_button"+<?php echo $input['bill_id']; ?>).disabled =true;
                })();
              </script>

          <?php else: 
                foreach($pending_bill_item as $bill_item):
          ?>
              <tr class="tr-shadow" id="bill_item<?php echo $bill_item['id'] ?>">
              
              <td><?php echo $bill_item['product_name']; ?></td>
              <td><?php echo $bill_item['product_price']; ?></td>
              <td><div class="" style="margin: auto; vertical-align: middle;">
                                                  <span style="cursor: pointer;" class="fa fa-minus" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span><input class="qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onkeypress='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><span style="cursor: pointer;" class="fa fa-plus"  onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span>
                                                  </div></td>
              <td><?php echo $bill_item['product_subtotal']; ?></td>
              <td>
                <i class="fa fa-times" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
              </td>
              </tr>
          <?php endforeach; 
                endif; 



        }

    }     

    public function DecreaseBillProductItem(Request $request)
    {
      $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];
      $user_info = $this->checkUserAvailbility($user_id,$request);
        
      $input = $request->all();

      $get_bill_item = PendingBillItems::where('company_id',$company_id)->where('id',$input['bill_item_id'])->first();
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
         $pending_bill_item = PendingBillItems::where('company_id',$company_id)
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
             
              <td><?php echo $bill_item['product_name']; ?></td>
              <td><?php echo $bill_item['product_price']; ?></td>
              <td><div class="" style="margin: auto; vertical-align: middle;">
                                                  <span style="cursor: pointer;" class="fa fa-minus" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span><input class="qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onkeypress='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><span style="cursor: pointer;" class="fa fa-plus"  onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span>
                                                  </div></td>
              <td><?php echo $bill_item['product_subtotal']; ?></td>
               <td>
                <i class="fa fa-times" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
              </td>
              </tr>
          <?php endforeach; 
                endif; 

      }
    }

    public function IncreaseBillProductItem(Request $request)
    {
      $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];
      $user_info = $this->checkUserAvailbility($user_id,$request);
        
      $input = $request->all();

      $get_bill_item = PendingBillItems::where('company_id',$company_id)->where('id',$input['bill_item_id'])->first();
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
        $pending_bill_item = PendingBillItems::where('company_id',$company_id)
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
             
              <td><?php echo $bill_item['product_name']; ?></td>
              <td><?php echo $bill_item['product_price']; ?></td>
              <td><div class="" style="margin: auto; vertical-align: middle;">
                                                  <span style="cursor: pointer;" class="fa fa-minus" onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span><input class="qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onkeypress='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><span style="cursor: pointer;" class="fa fa-plus"  onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span>
                                                  </div></td>
              <td><?php echo $bill_item['product_subtotal']; ?></td>
               <td>
                <i class="fa fa-times" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
              </td>
              </tr>
          <?php endforeach; 
                endif; 

      }
    }

    public function ChangeBillProductQuantity(Request $request)
    {
      $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];
      $user_info = $this->checkUserAvailbility($user_id,$request);
        
      $input = $request->all();

      $get_bill_item = PendingBillItems::where('company_id',$company_id)->where('id',$input['bill_item_id'])->first();
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
                                        'product_subtotal' => $get_bill_item->product_price * round($input['prod_qty']),
                                                              ));
        $pending_bill_item = PendingBillItems::where('company_id',$company_id)
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
             
              <td><?php echo $bill_item['product_name']; ?></td>
              <td><?php echo $bill_item['product_price']; ?></td>
              <td><div class="" style="margin: auto; vertical-align: middle;">
                                                  <span style="cursor: pointer;" class="fa fa-minus"  onclick='DecreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span><input class="qty-input" type="number" value="<?php echo $bill_item['product_quantity']; ?>" onkeypress='ChangeBillProductQty(this.value,"<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")' step="1" min="1" name="prod-qty" id="prod-qty<?php echo $bill_item['id'] ?>"><span style="cursor: pointer;" class="fa fa-plus"  onclick='IncreaseBillItem("<?php echo $bill_item['id'] ?>","<?php echo $bill_item['product_id']; ?>")'></span>
                                                  </div></td>
              <td><?php echo $bill_item['product_subtotal']; ?></td>
               <td>
                <i class="fa fa-times" style="cursor: pointer;" onclick='DeleteBillProductItem("<?php echo $bill_item['id'] ?>")'></i>
              </td>
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
      
      $company_id = session("login")["company_id"];

      $user_info = $this->checkUserAvailbility($user_id,$request);

      $input = $request->all();

      $get_bill = PendingBills::where('company_id',$company_id)->where('id',$input['bill_id'])->first();
      if ($get_bill == "") 
      {
            return array("status"=>"0","msg"=>"Something went wrong.");
      }
      else
      {

        PendingBills::where('company_id',$company_id)
                    ->where('id',$input['bill_id'])
                    ->update(array(
                          "tax_percentage"  => $input['tax'],
                      ));
      }
    }


    public function ApplyBillDiscount(Request $request)
    {
      $user_id = session("login")["user_id"];

      $company_id = session("login")["company_id"];

      $user_info = $this->checkUserAvailbility($user_id,$request);

      $input = $request->all();

      $get_bill = PendingBills::where('company_id',$company_id)->where('id',$input['bill_id'])->first();
      if ($get_bill == "") 
      {
            return array("status"=>"0","msg"=>"Something went wrong.");
      }
      else
      {


        //apply discount as percentage
        if ($input['type'] == 1) 
        {
          PendingBills::where('company_id',$company_id)
                      ->where('id',$input['bill_id'])
                      ->update(array(
                            "discount_percentage"  => $input['discount'],
                        ));
        }
        //apply discount as amount
        else
        {
          $get_discount_percentage = ($get_bill->subtotal == 0)?0:(100*$input['discount'])/$get_bill->subtotal;

          PendingBills::where('company_id',$company_id)
                      ->where('id',$input['bill_id'])
                      ->update(array(
                            "discount_percentage"  => $get_discount_percentage,
                        ));
        }
     
      }
    }
    // _________________________________________________________________________

    public function CalculateTotalBill(Request $request, $id)
    {
        $user_id = session("login")["user_id"];
        
        $company_id = session("login")["company_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $get_bill = PendingBills::where('company_id',$company_id)->where('id',$id)->first();

        if ($get_bill == "") 
        {
          ?>
          <tr><td colspan="2">Something went wrong.</td></tr>
          <?php
        }
        else
        {
          $subtotal = PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$id)->sum('product_subtotal');
          $total_item = PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$id)->count();

          $tax_amount = $subtotal * ($get_bill->tax_percentage/100);

          $discount_amount = $subtotal * ($get_bill->discount_percentage/100);


          $total_bill = ($subtotal - $discount_amount) + $tax_amount;

          PendingBills::where('company_id',$company_id)
                      ->where('id',$id)
                      ->update(array(
                          "subtotal"    => $subtotal,
                          "total_bill"  => $total_bill,
                          "tax_amount"  => $tax_amount,
                          "total_item"  => $total_item,
                          "discount_amount"  => $discount_amount,
                        ));




          ?>

            <tr class="tr-1">
              <td width="35%">Total Items: </td>
              <td width="25%" style="text-align: center;"><?php echo $total_item; ?></td>
              <td width="20%" style="text-align: center;">Subtotal: </td>
              <td width="20%" style="text-align: right;"><?php echo $subtotal; ?></td>
            </tr>
            

            <tr>
              <td >Total Tax: </td>
              <td style="text-align: center;" colspan="2"><input autocomplete="off" type="text" class="form-control tax-dis-input" onkeypress='ApplyBillTax(this.value,"<?php echo $id ?>")' id="bill-tax-input<?php echo $id ?>" value="<?php echo number_format($get_bill->tax_percentage,1) ?>%"></td>
              <td style="text-align: left;"><i style="float: right;"><?php echo number_format($tax_amount,2) ?></i></td>
            </tr>

            <tr>
              <td >Bill Discount: </td>
              <td style="text-align: center;" colspan="2"><input autocomplete="off" type="text" class="form-control tax-dis-input" onkeypress='ApplyBillDiscount(this.value,"<?php echo $id ?>")' id="bill-discount-input<?php echo $id ?>" value="<?php echo number_format($get_bill->discount_percentage,1) ?>%"></td>
              <td style="text-align: center;"><i style="float: right;"><?php echo number_format($discount_amount,2) ?></i></td>
            </tr>

            <tr>
              <td colspan="2"><b>Total Payable:</b> </td>
              <td colspan="2"><?php echo number_format($total_bill,2) ?></td>
            </tr>
            
          
          <?php


        }

    }





    public function getBillCode($id)
    {
        $gen_code = str_pad(strtoupper(substr(session("login.company_name"), 0,2) )."_".substr(time(),4,9).strtoupper(substr(uniqid(), 7,12) ),15,'#',STR_PAD_LEFT);


        $last_bill = Sales::where('bill_code',$gen_code)->first();

        $last_pending_bill = PendingBills::where('bill_code',$gen_code)->first();
        
        if ($last_bill != "" || $last_pending_bill != "") 
        {   
          $this->getBillCode($id);
        }
        else
        {
          return $gen_code;   
        }
    }


    public function CustomerLiveSearchList(Request $request,$code,$bill_id)
    {
      try 
      {
        $user_id = session("login")["user_id"];
        
        $company_id = session("login")["company_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $customer = Customers::where('company_id',$company_id)
                              ->where(function($query) use ($code)
                                            {
                                                $query->where('customer_name','like','%'.$code.'%')
                                                ->orWhere('code','like','%'.$code.'%');
                                            })
                              ->get();

        if (count($customer) == 0) 
        {
          return array("status"=>"0");
        }
        else
        {

          foreach ($customer as $key) 
          {
            ?>
              <button type="button" onclick='ChangeBillCustomer("<?php echo $key['id'] ?>","<?php echo $bill_id; ?>"),SelectCustomer("<?php echo $key['customer_name'] ?>","<?php echo $key['id'] ?>")' style="width: 100%;" class="btn btn-light"><?php echo $key['customer_name'] ?></button>
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
    