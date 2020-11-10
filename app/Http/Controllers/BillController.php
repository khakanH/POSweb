<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\Customers;
use App\Models\PendingBills;
use App\Models\PendingBillItems;
use App\Models\CompanyInfo;
use App\Models\PaymentMethods;

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

        $user_info = $this->checkUserAvailbility($user_id,$request);
        
        $sales = Sales::where('member_id',$user_id)->get();
        return view('sales',compact('sales'));
    }

    public function SaleListAJAX(Request $request, $search_text)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($search_text)) 
            {       
                    $get_sale_list = Sales::where('member_id',$user_id)
                                      ->paginate(15);
            }
            else
            {   
                    $get_sale_list = Sales::where('member_id',$user_id)
                                      ->where('bill_code','like','%'.$search_text.'%')
                                      ->paginate(15);

            }
            
            if (count($get_sale_list)==0) 
            {
                ?>
                <tr><td colspan="8" class="text-center tx-18">No Sale Found</td></tr>
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
                      <td><?php echo $key['tax']?>%</td>
                      <td><?php echo $key['discount']?>%</td>
                      <td><?php echo $key['total_bill']?></td>
                      <td><?php echo $key['total_item']?></td>
                      <td class="text-center"><a class="btn btn-primary" href="javascript:void(0)" onclick='ViewSaleItem("<?php echo $key['id']?>","<?php echo $key['bill_code']?>")'><i class="fa fa-eye tx-15"></i></a>&nbsp;&nbsp;&nbsp;<!-- <a class="btn btn-danger" onclick='DeleteSale("<?php //echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a> --></td>
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

   public function BillSaleItems(Request $request, $id)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            $get_sale_item = SalesItems::where('member_id',$user_id)
                                    ->where('sale_id',$id) 
                                      ->get();
           
            
            if (count($get_sale_item)==0) 
            {
                ?>
                <center><p> No Sale Item Found </p></center>
                <?php
            }
            else
            {

                ?>
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
            
        }

    } 
    




    public function AddUpdateCustomer(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();



            if (empty($input['cust_id'])) 
            {   
                $data = array(
                        "customer_name"         => $input['cust_name'],
                        "customer_email"        => $input['cust_email'],
                        "customer_phone"        => $input['cust_phone'],
                        "customer_discount"     => (float)$input['cust_discount'],
                        "member_id"             => $user_id,
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
            
        }
    }



    public function GetCustomersList(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];
            $user_info = $this->checkUserAvailbility($user_id,$request);
            
        	$customers = Customers::where('member_id',$user_id)->where('is_deleted',0)->get();
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
            
        }
    }

    public function ChangeBillCustomer(Request $request,$cust_id,$bill_id)
    {
        try 
        {   
        	$cust_id = (int)$cust_id;

            $user_id = session("login")["user_id"];
            $user_info = $this->checkUserAvailbility($user_id,$request);
            


            if ($cust_id == 0) 
            {
    			$company  = CompanyInfo::where('member_id',$user_id)->first();
            	
            	PendingBills::where('member_id',$user_id)->where('id',$bill_id)->update(array('customer_id'=>0,"discount_percentage"=>$company->default_discount));

		        	PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$bill_id)->update(array('customer_id'=>0));
	                
	                return array("status"=>"1","msg"=>"Customer Updated Successfully.");
            }
            else
            {
	        	$check_customer = Customers::where('member_id',$user_id)->where('id',$cust_id)->where('is_deleted',0)->first();

	        	if ($check_customer == "") 
	        	{
	                return array("status"=>"0","msg"=>"Something went wrong.");
	        	}
	        	else
	        	{
		        	PendingBills::where('member_id',$user_id)->where('id',$bill_id)->update(array('customer_id'=>$cust_id,"discount_percentage"=>$check_customer->customer_discount));

		        	PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$bill_id)->update(array('customer_id'=>$cust_id));

	                return array("status"=>"1","msg"=>"Customer Updated Successfully.");
	        	}
            }
        } 
        catch (Exception $e) 
        {
            
        }
    }

    public function GetBillDetails(Request $request,$id)
    {
        try 
        {
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $get_bill = PendingBills::where('member_id',$user_id)->where('id',$id)->first();

            if ($get_bill == "") 
            {
                return array("status"=>"0","msg"=>"Something went wrong.");
            }
            else
            {
                if ($get_bill->customer_id == 0) 
                {
                    $result  = array(
                                        "customer_name"=>"Walk In Customer",
                                        "total_item"=>$get_bill->total_item." Items",
                                        "total_bill"=>$get_bill->total_bill,
                                    );
                }
                else
                {
                     $result  = array(
                                        "customer_name"=>$get_bill->customer_name['customer_name'],
                                        "total_item"=>$get_bill->total_item." Items",
                                        "total_bill"=>$get_bill->total_bill,
                                    );
                }
                return array("status"=>"1","msg"=>"Bill Details.","result"=>$result);
            }

        } 
        catch (Exception $e) 
        {
            
        }
    }

    public function GetPaymentMethod(Request $request)
    {
        try 
        {
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);

            $payment_method = PaymentMethods::get();

            foreach ($payment_method as $key) 
            {
                ?>
                 <option value="<?php echo $key['id']; ?>"><?php echo $key['name']; ?></option>
                <?php
            }
        } 
        catch (Exception $e) 
        {
            
        }
    }


    public function AddSale(Request $request)
    {
        try 
        {
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();

            $get_bill = PendingBills::where('member_id',$user_id)->where('id',$input['bill_id'])->first();
            if ($get_bill == "") 
            {
                    return array("status"=>"0","msg"=>"Something went wrong");
            }
            else
            {   
                $sale_id = Sales::insertGetId(array(
                                'bill_code'             => $get_bill->bill_code,
                                'member_id'             => $user_id,
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
                                'created_at'            => date("Y-m-d H:i:s"),
                                'updated_at'            => date("Y-m-d H:i:s"),
                ));



                $get_bill_item = PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$input['bill_id'])->get();

                foreach ($get_bill_item as $key) 
                {
                    SalesItems::insert(array(
                                "sale_id"           => $sale_id,
                                "member_id"         => $user_id,
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

            
                PendingBills::where('member_id',$user_id)->where('id',$input['bill_id'])->delete();
                PendingBillItems::where('member_id',$user_id)->where('pending_bill_id',$input['bill_id'])->delete();

                    return array("status"=>"1","msg"=>"Success","sale_id"=>$sale_id);


            }




        } 
        catch (Exception $e) 
        {
            
        }
    }



    public function GetBillReceipt(Request $request,$id)
    {
        try 
        {
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $company  = CompanyInfo::where('member_id',$user_id)->first();

            if (empty($id)) 
            {
                $get_bill = Sales::where('member_id',$user_id)->orderBy('id','desc')->first();
            }
            else
            {
                $get_bill = Sales::where('member_id',$user_id)->where('id',$id)->first();

            }

            if ($get_bill == "") 
            {
                ?>
                    <center><p>No Bill Found :(</p></center>
                <?php
            }
            else
            {   
                $get_bill_item = SalesItems::where('sale_id',$get_bill->id)->where('member_id',$user_id)->get();
                ?>
                    <center>
                        <h3>Title Here</h3>
                        <p><?php echo $company->receipt_header; ?></p>
                        <p style="font-size: 18px;">Sale No. <?php echo $get_bill->bill_code; ?></p>
                    </center>
                    <span>Date: <?php echo $get_bill->created_at; ?></span>
                    <br>
                    <span>Customer: <?php echo isset($get_bill->customer_name['customer_name'])?$get_bill->customer_name['customer_name']:"Walk In Customer"; ?></span>
                    
                    <table width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: left;" width="5%">#</th>
                                <th style="text-align: left;" width="60%">Product</th>
                                <th style="text-align: center;" width="5%">Qty</th>
                                <th style="text-align: center;" width="30%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count=1; foreach($get_bill_item as $key): ?>
                                <tr>
                                    <td style="text-align: left;"><?php echo $count; ?></td>
                                    <td style="text-align: left;"><?php echo $key['product_name']?></td>
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
                                <td style="text-align: right;" width="25%"><?php echo $get_bill->total_item ?></td>
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
                            <tr><td colspan="4"><hr style="border: solid black 2px;"></td></tr>
                            <tr>
                                <td colspan="2" width="50%"><?php echo $company->name; ?></td>
                                <td style="text-align: right;" colspan="2" width="50%">Tel: <?php echo $company->phone ?></td>
                            </tr>

                        </tbody>

                    </table>

                    
                    
                   
                    
                    
                  
                    <br>
                    <br>

                    <div style="background: black; color: white; width: 100%; text-align: center; padding: 10px;">
                        <?php echo $company->receipt_footer; ?>
                    </div>
                <?php
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
    