<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\Customers;
use App\Models\PendingBills;
use App\Models\PendingBillItems;
use App\Models\CompanyInfo;
use App\Models\PaymentMethods;
use App\Models\Product;

use App\Models\Sales;
use App\Models\SalesItems;




class BillController extends Controller
{

    protected $member_model;

    public function __construct(Request $request)
    {   
        $this->member_model         = new Members();
    }


    public function Index(Request $request)
    {   
        $user_id = session("login")["user_id"];
        $company_id = session("login")["company_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);
        
        $sales = Sales::where('company_id',$company_id)->orderBy('created_at','desc')->get();
        return view('sales',compact('sales'));
    }

    public function SaleListAJAX(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();


            if (empty($input['search'])) 
            {       
                if (empty($input['start_date']) && empty($input['end_date'])) 
                {
                    $get_sale_list = Sales::where('company_id',$company_id)
                                      ->orderBy('created_at','desc')
                                      ->get();
                }
                else
                {
                    $get_sale_list = Sales::where('company_id',$company_id)
                                          ->whereDate('created_at','>=',$input['start_date'])
                                          ->whereDate('created_at','<=',$input['end_date'])
                                          ->orderBy('created_at','desc')
                                          ->get();
                }
            }
            else
            {   
                if (empty($input['start_date']) && empty($input['end_date'])) 
                {   
                    $get_sale_list = Sales::where('company_id',$company_id)
                                      ->where('bill_code','like','%'.$input['search'].'%')
                                      ->orderBy('created_at','desc')
                                      ->get();
                }
                else
                {
                    $get_sale_list = Sales::where('company_id',$company_id)
                                        ->whereDate('created_at','>=',$input['start_date'])
                                        ->whereDate('created_at','<=',$input['end_date'])
                                        ->where('bill_code','like','%'.$input['search'].'%')
                                        ->orderBy('created_at','desc')
                                        ->get();
                }
            }
            
            if (count($get_sale_list)==0) 
            {
                ?>
                <tr><td colspan="9" class="text-center tx-18">No Sale Found</td></tr>
                <?php
            }
            else
            {

                $count= 1;
                foreach ($get_sale_list as $key) 
                {
                ?>

                    <tr id="sale<?php echo $key['id']?>">
                      <td scope="row"><b><?php echo $count; ?></b></td>
                      <td><?php echo $key['bill_code']?></td>
                      <td><?php echo isset($key->customer_name['customer_name'])?$key->customer_name['customer_name']:"Walk In Customer"?></td>
                      <td><?php echo number_format($key['tax'],1)?>%</td>
                      <td><?php echo number_format($key['discount'],1)?>%</td>
                      <td><?php echo $key['total_bill']?></td>
                      <td><?php echo $key['total_item']?></td>
                       <td><?php echo $key->payment_method_name->name ?></td>
                                                <td>
                                                    <?php if($key['is_paid'] == 0): ?>
                                                        <p class="text-danger">No</p>
                                                    <?php else: ?>
                                                        <p class="text-success">Yes</p>
                                                    <?php endif; ?>

                                                </td>
                      <td><?php echo date("d-M-Y",strtotime($key['created_at']))?></td>
                      <td class="text-center"><a class="btn btn-primary" href="javascript:void(0)" onclick='ViewSaleItem("<?php echo $key['id']?>","<?php echo $key['bill_code']?>")'><i class="fa fa-eye tx-15"></i></a>&nbsp;&nbsp;&nbsp;<!-- <a class="btn btn-danger" onclick='DeleteSale("<?php //echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a> --></td>
                    </tr>

                <?php
                $count++;
                } 
            }

        } 
        catch (Exception $e) 
        {
            
            return response()->json($e,500);
        }

    }

   public function BillSaleItems(Request $request, $id)
    {
        try 
        {   
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
             $get_sale = Sales::where('company_id',$company_id)
                                    ->where('id',$id) 
                                    ->first();

            $get_sale_item = SalesItems::where('company_id',$company_id)
                                    ->where('sale_id',$id) 
                                      ->get();
           
            
            if ($get_sale=="") 
            {
                ?>
                <center><p> No Sale Details Found </p></center>
                <?php
            }
            else
            {

                ?>

                <div class="au-card au-card--bg-blue au-card-top-countries m-b-30"> 
                    <div class="au-card-inner">
                    <table class="table table-top-countries">
                    <tbody>
                    <tr>
                        <td>Payment Type: <?php echo $get_sale->payment_method_name->name; ?></td>
                        <?php if($get_sale->payment_method ==1): ?>
                        <td>Cash Paid: <?php echo number_format($get_sale->cash_paid,2); ?></td>
                        <td>Cash Change: <?php echo number_format($get_sale->cash_change,2); ?></td>
                        <?php elseif($get_sale->payment_method ==2): ?>
                        <td>Credit Card Holder: <?php echo $get_sale->credit_card_holder; ?></td>
                        <td>Credit Card Number: <?php echo $get_sale->credit_card_number; ?></td>
                        <?php elseif($get_sale->payment_method ==3): ?>
                        <td colspan="2">Cheque Number: <?php echo $get_sale->cheque_number; ?></td>
                        <td></td>
                        <?php elseif($get_sale->payment_method ==4): ?>
                        <td colspan="2"><?php echo ($get_sale->is_paid == 0 )?'<button class="btn btn-success" onclick="MarkBillPaid('.$id.')" style="width: 100%;">Mark Paid</button>':''; ?></td>
                        <td></td>
                        <?php endif; ?>

                    </tr>
                    <tr>
                        <td>FBR Integarated: <?php echo empty($get_sale->fbr_invoice_number)?"No":"Yes"; ?> </td>
                        <?php if(!empty($get_sale->fbr_invoice_number)): ?>
                        <td colspan="2">FBR Invoice Number: <?php echo $get_sale->fbr_invoice_number; ?> </td>
                        <?php endif; ?>

                    </tr>
                    </tbody>
                    </table>
                    </div>
                </div>

                
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php


                $count= 1;
                foreach ($get_sale_item as $key) 
                {
                ?>

                    <tr id="saleitem<?php echo $key['id']?>">
                      <td scope="row"><b><?php echo $count; ?></b></td>
                      <td><?php echo $key['product_name']?><br><center><i><?php echo $key['product_price']?>/-</i></center></td>
                      <td><?php echo $key['product_quantity']?></td>
                      <td><?php echo $key['product_subtotal']?></td>
                    </tr>

                <?php
                $count++;
                }

                ?>
                        </tbody>
                    </table>

                <?php



            }

        } 
        catch (Exception $e) 
        {
            
            return response()->json($e,500);
        }

    } 
    

     public function MarkBillPaid(Request $request, $id)
    {
        try 
        {   
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            Sales::where('company_id',$company_id)
                                    ->where('id',$id) 
                                    ->update(array('is_paid'=>1));

            
            
           
        } 
        catch (Exception $e) 
        {
            
            return response()->json($e,500);
        }

    } 



    public function AddUpdateCustomer(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();



            if (empty($input['cust_id'])) 
            {   
                $data = array(
                        "code"                  => $input['cust_code'],
                        "customer_name"         => $input['cust_name'],
                        "customer_email"        => $input['cust_email'],
                        "customer_phone"        => $input['cust_phone'],
                        "customer_discount"     => (float)$input['cust_discount'],
                        "member_id"             => $user_id,
                        "company_id"            => $company_id,
                        "payment_type"          => $input['cust_payment_method'],
                        "credit_card_holder"    => $input['cust_cc_holder'],
                        "credit_card_number"    => $input['cust_cc_number'],
                        "is_deleted"            => 0,
                        "created_at"            => date('Y-m-d H:i:s'),
                        "updated_at"            => date('Y-m-d H:i:s'),
                        );
                if(Customers::insert($data))
                {
                    return array("status"=>"1","msg"=>"Customer Added Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Failed");

                }
            }
            else
            {
                $data = array(
                        "customer_name"         => $input['cust_name'],
                        "customer_email"        => $input['cust_email'],
                        "customer_phone"        => $input['cust_phone'],
                        "customer_discount"     => (float)$input['cust_discount'],
                        "code"                  => $input['cust_code'],
                        "payment_type"          => $input['cust_payment_method'],
                        "credit_card_holder"    => $input['cust_cc_holder'],
                        "credit_card_number"    => $input['cust_cc_number'],
                        "updated_at"            => date('Y-m-d H:i:s'),
                        );
                if(Customers::where('id',$input['cust_id'])->update($data))
                {
                    return array("status"=>"1","msg"=>"Customer Updated Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Failed");
                }
            }
            
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
            
        }
    }



    public function GetCustomersList(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];
            $user_info = $this->checkUserAvailbility($user_id,$request);
            
        	$customers = Customers::where('company_id',$company_id)->where('is_deleted',0)->get();
        	?>
        		<option value="0">Walk in Customer</option>
            <?php
            	foreach($customers as $cust):
            ?>
            	<option value="<?php echo $cust['id'] ?>"><?php echo $cust['customer_name'] ?></option>

            <?php
            	endforeach;
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
            
        }
    }

    public function ChangeBillCustomer(Request $request,$cust_id,$bill_id)
    {
        try 
        {   
        	$cust_id = (int)$cust_id;

            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];
            $user_info = $this->checkUserAvailbility($user_id,$request);
            


            if ($cust_id == 0) 
            {
    			$company  = CompanyInfo::where('id',$company_id)->first();
            	
            	PendingBills::where('company_id',$company_id)->where('id',$bill_id)->update(array('customer_id'=>0,"discount_percentage"=>$company->default_discount));

		        	PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$bill_id)->update(array('customer_id'=>0));
	                
	                return array("status"=>"1","msg"=>"Customer Updated Successfully.");
            }
            else
            {
	        	$check_customer = Customers::where('company_id',$company_id)->where('id',$cust_id)->where('is_deleted',0)->first();

	        	if ($check_customer == "") 
	        	{
	                return array("status"=>"0","msg"=>"Something went wrong.");
	        	}
	        	else
	        	{
		        	PendingBills::where('company_id',$company_id)->where('id',$bill_id)->update(array('customer_id'=>$cust_id,"discount_percentage"=>$check_customer->customer_discount));

		        	PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$bill_id)->update(array('customer_id'=>$cust_id));

	                return array("status"=>"1","msg"=>"Customer Updated Successfully.");
	        	}
            }
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
            
        }
    }

    public function GetBillDetails(Request $request,$id)
    {
        try 
        {
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $get_bill = PendingBills::where('company_id',$company_id)->where('id',$id)->first();
            $payment_method = PaymentMethods::where('is_show',1)->get();

            if ($get_bill == "") 
            {
                ?>
                <center><p class="tx-danger">Something Went Wrong</p></center>
                <?php
            }
            else
            {
                ?>
                        <input type="hidden" id="bill_id" name="bill_id" value="<?php echo $id; ?>">
                        <input type="hidden" id="total_bill_amount" name="total_bill_amount" value="<?php echo $get_bill->total_bill; ?>">
                        <input type="hidden" id="bill_cash_change" name="bill_cash_change">

                         <div class="p-style">
                        <p>Customer: <span id="bill_cust_name"><?php echo isset($get_bill->customer_name->customer_name)?$get_bill->customer_name->customer_name:"Walk In Customer"; ?></span></p>
                        
                        <p id="bill_total_item">Total Item: <?php echo $get_bill->total_item; ?></p>
                        <p id="bill_total_amount">Total Amount: <?php echo $get_bill->total_bill; ?></p>
                      </div>


                         <div class="form-group pos-right-search">
                          <label for="payment_method" class=" form-control-label">Payment Method:</label>
                          <select required="" onchange="CheckPaymentMethod(this.value)" name="payment_method" id="payment_method" class="form-control">
                            <?php foreach ($payment_method as $key): ?>
                                <option <?php if ($key['id'] == (isset($get_bill->customer_name->payment_type)?$get_bill->customer_name->payment_type:0)): ?>
                        selected
                 <?php endif ?> value="<?php echo $key['id']; ?>"><?php echo $key['name']; ?></option>
                            <?php endforeach; ?>
                           
                          </select>
                        </div>


                         <div class="form-group pos-right-search" <?php if ((isset($get_bill->customer_name->payment_type)?$get_bill->customer_name->payment_type:0) != 1 && $get_bill->customer_id != 0): ?>
                             style ="display: none;"
                         <?php else: ?>
                             style ="display: block;"
                         <?php endif; ?>

                          id="for_cash">
                          <label for="payment_amount"  class=" form-control-label">Payment Amount:</label>
                          <input type="number" required="" name="payment_amount" onkeyup="CalculateBillChange(this.value)" value="<?php echo $get_bill->total_bill; ?>" id="payment_amount" class="form-control">
                          <br>
                          <label for="bill_change" class=" form-control-label">Change: <span id="bill_change">0</span></label>
                        </div>



                         <div class="form-group pos-right-search" <?php if ((isset($get_bill->customer_name->payment_type)?$get_bill->customer_name->payment_type:0) != 2): ?>
                             style ="display: none;"
                             <?php else: ?>
                             style ="display: block;"
                         <?php endif ?>  id="for_credit_card">
                          <label for="credit_card_number" class=" form-control-label">Credit Card Number:</label>
                          <input required="" name="credit_card_number" id="credit_card_number" class="form-control" type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}" autocomplete="cc-number" maxlength="19"  value="<?php echo isset($get_bill->customer_name->credit_card_number)?$get_bill->customer_name->credit_card_number:"" ?>" placeholder="xxxx xxxx xxxx xxxx">
                          <br>
                          <label for="credit_card_holder" class=" form-control-label">Credit Card Holder:</label>
                          <input type="text" required=""  value="<?php echo isset($get_bill->customer_name->credit_card_holder)?$get_bill->customer_name->credit_card_holder:"" ?>" name="credit_card_holder" id="credit_card_holder" class="form-control">
                        </div>



                        <div class="form-group pos-right-search" <?php if ((isset($get_bill->customer_name->payment_type)?$get_bill->customer_name->payment_type:0) != 3): ?>
                             style ="display: none;"
                             <?php else: ?>
                             style ="display: block;"
                         <?php endif ?> id="for_cheque">
                          <label for="cheque_number" class=" form-control-label">Cheque Number:</label>
                          <input type="text" required="" name="cheque_number" id="cheque_number" class="form-control">
                        </div>

                        <script type="text/javascript">
                                $("#credit_card_number").mask("9999-9999-9999-9999");
                            
                        </script>
                <?php
            }

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
            
        }
    }

    public function GetPaymentMethod(Request $request,$id)
    {
        try 
        {
            $user_id = session("login")["user_id"];

            $company_id = session("login")["company_id"];
            
            $user_info = $this->checkUserAvailbility($user_id,$request);

            $payment_method = PaymentMethods::where('is_show',1)->get();

            foreach ($payment_method as $key) 
            {
                ?>
                 <option <?php if ($key['id'] == $id): ?>
                        selected
                 <?php endif ?> value="<?php echo $key['id']; ?>"><?php echo $key['name']; ?></option>
                <?php
            }
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
            
        }
    }


    public function AddSale(Request $request)
    {
        try 
        {
            $user_id = session("login")["user_id"];

            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $company  = CompanyInfo::where('id',$company_id)->first();

            
            $input = $request->all();

            $get_bill = PendingBills::where('company_id',$company_id)->where('id',$input['bill_id'])->first();
            if ($get_bill == "") 
            {
                    return array("status"=>"0","msg"=>"Something went wrong");
            }
            else
            {   
                $sale_id = Sales::insertGetId(array(
                                'bill_code'             => $get_bill->bill_code,
                                'member_id'             => $user_id,
                                'company_id'            => $company_id,
                                'customer_id'           => $get_bill->customer_id,
                                'tax'                   => $get_bill->tax_percentage,
                                'discount'              => $get_bill->discount_percentage,
                                'subtotal_bill'         => $get_bill->subtotal,
                                'total_item'            => $get_bill->total_item, 
                                'total_bill'            => $get_bill->total_bill,
                                'payment_method'        => $input['payment_method'],
                                'cash_paid'             => $input['payment_amount'],
                                'cash_change'           => $input['bill_cash_change'],
                                'credit_card_holder'    => $input['credit_card_holder'],
                                'credit_card_number'    => $input['credit_card_number'],
                                'cheque_number'         => $input['cheque_number'],
                                'is_paid'               => ($input['payment_method'] == 4)?0:1,
                                'created_at'            => date("Y-m-d H:i:s"),
                                'updated_at'            => date("Y-m-d H:i:s"),
                ));



                $get_bill_item = PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$input['bill_id'])->get();

                foreach ($get_bill_item as $key) 
                {
                    SalesItems::insert(array(
                                "sale_id"           => $sale_id,
                                "member_id"         => $user_id,
                                "company_id"        => $company_id,
                                "customer_id"       => $get_bill->customer_id,
                                "product_id"        => $key['product_id'],
                                "product_name"      => $key['product_name'],
                                "product_price"     => $key['product_price'],
                                "product_quantity"  => $key['product_quantity'],
                                "product_subtotal"  => $key['product_subtotal'],
                                "created_at"        => date("Y-m-d H:i:s"),
                                "updated_at"        => date("Y-m-d H:i:s"),
                    ));
                    
                }

            
                PendingBills::where('company_id',$company_id)->where('id',$input['bill_id'])->delete();
                PendingBillItems::where('company_id',$company_id)->where('pending_bill_id',$input['bill_id'])->delete();
                if ($company->fbr_invoice == 1) 
                {   

                    foreach ($get_bill_item as $key) 
                    {
                        $item = Product::where('id',$key['product_id'])->where('company_id',$company_id)->first();
                        $arr[]=array(
                            "ItemCode"      => (string)isset($item->product_code)?$item->product_code:"",
                            "ItemName"      => $key['product_name'],
                            "Quantity"      => (float)$key['product_quantity'],
                            "PCTCode"       => (string)"1",
                            "TaxRate"       => (float)isset($item->tax)?$item->tax:0.00,
                            "SaleValue"     => (float)$key['product_price'],
                            "TotalAmount"   => (float)$key['product_subtotal'],
                            "TaxCharged"    => (float)($key['product_price']*(isset($item->tax)?$item->tax:0.00/100)),
                            "Discount"      => (float)"0.00",
                            "FurtherTax"    => (float)"0.00",
                            "InvoiceType"   => ($input['payment_method']==2)?2:1,
                            "RefUSIN"       => ""
                        );
                    }

                    $response = Http::withToken('1298b5eb-b252-3d97-8622-a4a69d5bf818')->post('https://gw.fbr.gov.pk/imsp/v1/api/Live/PostData',[
    
                                "InvoiceNumber"      => "",
                                "POSID"              => (int)"",
                                "USIN"               => (int)"",
                                "DateTime"           => date("Y-m-d H:i:s"),
                                "BuyerNTN"           => "",
                                "BuyerCNIC"          => "",
                                "BuyerName"          => "",
                                "BuyerPhoneNumber"   => "",
                                "TotalBillAmount"    => (float)$get_bill->total_bill,
                                "TotalQuantity"      => (float)$get_bill->total_item,
                                "TotalSaleValue"     => (float)$get_bill->subtotal,
                                "TotalTaxCharged"    => (float)$get_bill->tax_amount,
                                "Discount"           => (float)"0.00",
                                "FurtherTax"         => (float)"0.00",
                                "PaymentMode"        => (int)($input['payment_method']==1?1:($input['payment_method']==2?2:($input['payment_method']==3?6:1))),
                                "RefUSIN"            => "",
                                "InvoiceType"        => ($input['payment_method']==2)?2:1,
                                "Items"              => $arr

                    ])->json();

                   info($response);
                    
                    if ($response['Code'] == 100) 
                    {
                        $status = "1";
                        $msg = "Sale Added and ".$response['Response'];
                        Sales::where('id',$sale_id)->update(array('fbr_invoice_number'=>$response['InvoiceNumber']));
                    }
                    else
                    {
                        $status = "2";
                        $msg = "Sale Added but ".$response['Response'];
                    }
                }
                else
                {       
                    $status = "1";
                    $msg = "Sale Added Successfully ";
                }
                return array("status"=>$status,"msg"=>$msg,"sale_id"=>$sale_id);


            }




        } 
        catch (Exception $e) 
        {
            
            return response()->json($e,500);
        }
    }

    public function ExportSaleCSV(Request $request)
    {
        try 
        {
            $user_id = session("login")["user_id"];

            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $company  = CompanyInfo::where('id',$company_id)->first();

            $fileName = $company->name.'_sale_report.csv';
            
            $sales = Sales::where('company_id',$company_id)->orderBy('created_at','asc')->get();

            

            $columns = array('S_No', 'Bill_Code', 'Customers', 'Tax', 'Discount','Subtotal','Total_Item','Total_Bill','Payment_Type','Cash_Paid','Cash_Change','Credit_Card_Holder','Credit_Card_Number','Cheque_Number','Created_At');

           
                $file = fopen($fileName, 'w+');
                fputcsv($file, $columns);

                $count=1;
                foreach ($sales as $sale) {
                    $row['S_No']        = $count;
                    $row['Bill_Code']   = $sale['bill_code'];
                    $row['Customers']   = isset($sale->customer_name['customer_name'])?$sale->customer_name['customer_name']:"Walk In Customer";
                    $row['Tax']         = number_format($sale['tax'],1)."%";
                    $row['Discount']    = number_format($sale['discount'],1)."%";
                    $row['Subtotal']    = number_format($sale['subtotal_bill'],2);
                    $row['Total_Item']  = number_format($sale['total_item'],2);
                    $row['Total_Bill']  = $sale['total_bill'];

                    $row['Payment_Type']        = $sale->payment_method_name['name'];
                    $row['Cash_Paid']           = $sale['cash_paid'];
                    $row['Cash_Change']         = $sale['cash_change'];
                    $row['Credit_Card_Holder']  = $sale['credit_card_holder'];
                    $row['Credit_Card_Number']  = $sale['credit_card_number'];
                    $row['Cheque_Number']       = $sale['cheque_number'];
                    $row['Created_At']          = $sale['created_at'];

                    fputcsv($file, array($row['S_No'], $row['Bill_Code'], $row['Customers'], $row['Tax'], $row['Discount'], $row['Subtotal'], $row['Total_Item'], $row['Total_Bill'], $row['Payment_Type'], $row['Cash_Paid'], $row['Cash_Change'], $row['Credit_Card_Holder'], $row['Credit_Card_Number'], $row['Cheque_Number'], $row['Created_At']));
                    $count++;
                }

                fclose($file);
                 $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                  );


             return response()->download($fileName, $fileName,$headers);
             // return redirect()->route('sales')->with('success','File Exported Successfully');
        } 
        catch (Exception $e) 
        {
            
            return response()->json($e,500);
        }
    }



    public function GetBillReceipt(Request $request,$id)
    {
        try 
        {
            $user_id = session("login")["user_id"];

            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $company  = CompanyInfo::where('id',$company_id)->first();

            if (empty($id)) 
            {
                $get_bill = Sales::where('company_id',$company_id)->orderBy('id','desc')->first();
            }
            else
            {
                $get_bill = Sales::where('company_id',$company_id)->where('id',$id)->first();

            }

            if ($get_bill == "") 
            {
                ?>
                    <center><p>No Bill Found :(</p></center>
                <?php
            }
            else
            {   
                $get_bill_item = SalesItems::where('sale_id',$get_bill->id)->where('company_id',$company_id)->get();
                ?>
                    <center>
                        <img src="<?php echo env('IMG_URL').$company->logo; ?>" height="100" width="100">
                        <br>
                        <br>
                        <h4><?php echo $company->name; ?></h4>
                        <p style="word-wrap: break-word;"><?php echo $company->receipt_header; ?></p>
                        <p style="font-size: 18px;">Sale No. <?php echo $get_bill->bill_code; ?></p>
                    </center>
                    <span>Date: <?php echo $get_bill->created_at; ?></span>
                    <br>
                    <span>Customer: <?php echo isset($get_bill->customer_name['customer_name'])?$get_bill->customer_name['customer_name']:"Walk In Customer"; ?></span>
                    <br>
                      <span>Payment: <?php echo $get_bill->payment_method_name->name ?></span>
                    <hr>
                    <table class="table-receipt table table-sm"> 
                        <thead>
                            <tr>
                                <th style="text-align: left;" width="60%">Product</th>
                                <th style="text-align: left;" width="60%">Price</th>
                                <th style="text-align: center;" width="5%">Qty</th>
                                <th style="text-align: center;" width="30%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count=1; foreach($get_bill_item as $key): ?>
                                <tr>
                                    <td style="text-align: left;"><?php echo $key['product_name']?></td>
                                    <td style="text-align: left;"><?php echo $key['product_price']?></td>
                                    <td style="text-align: center;"><?php echo $key['product_quantity']?></td>
                                    <td style="text-align: center;"><?php echo $key['product_subtotal']?></td>
                                </tr>
                            <?php $count++; endforeach; ?>
                        </tbody>
                    </table>
                    <hr>
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td width="25%">Total Items</td>
                                <td style="text-align: center;" width="25%"><?php echo $get_bill->total_item ?></td>
                                <td width="25%">Total</td>
                                <td style="text-align: right;" width="25%"><?php echo $get_bill->subtotal_bill ?></td>
                            </tr>
                            <tr><td colspan="4"><hr></td></tr>
                            <tr>
                                <td width="25%"></td>
                                <td width="25%"></td>
                                <td width="25%">Discount</td>
                                <td style="text-align: right;" width="25%"><?php echo number_format($get_bill->discount,1) ?>%</td>
                            </tr>
                            <tr><td colspan="4"><hr></td></tr>

                            <tr>
                                <td width="25%"></td>
                                <td width="25%"></td>
                                <td width="25%">Tax</td>
                                <td style="text-align: right;" width="25%"><?php echo number_format($get_bill->tax,1) ?>%</td>
                            </tr>
                            <tr><td colspan="4"><hr></td></tr>
                            
                            <tr>
                                <td colspan="2" width="50%">Grand Total</td>
                                <td style="text-align: right;" colspan="2" width="50%"><?php echo number_format($get_bill->total_bill,2) ?></td>
                            </tr>
                            <?php if($get_bill->payment_method == 1): ?>

                            <tr><td colspan="4"><hr></td></tr>
                            <tr>
                                <td colspan="2" width="50%">Paid</td>
                                <td style="text-align: right;" colspan="2" width="50%"><?php echo number_format($get_bill->cash_paid,2) ?></td>
                            </tr>
                            <tr><td colspan="4"><hr></td></tr>

                             <tr>
                                <td colspan="2" width="50%">Change</td>
                                <td style="text-align: right;" colspan="2" width="50%"><?php echo number_format($get_bill->cash_change,2) ?></td>
                            </tr>

                            <?php endif;?>


                          

                        </tbody>

                    </table>

                    
                    
                   
                    
                    
                  
                    <br>
                    
                    <?php if (!empty($get_bill->fbr_invoice_number)): ?>
                    <div style="width: auto; padding: 5px; border: solid black 1px;">
                        FBR Invoice Number: <?php echo $get_bill->fbr_invoice_number; ?>
                    </div>
                    <?php endif; ?>


                    <br>

                   <div class="modal-bottom-btns modal-reciept-btn">
                <button type="button" class="" style="cursor: default; font-size: 12px !important;  width: 100%;background-color: #6c6d70;  color: #ffffff;  padding: 5px 46px;  border-radius: 50px; " disabled=""><?php echo $company->receipt_footer; ?></button>
              </div>
                <?php
            }

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
    