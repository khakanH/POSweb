<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Countries;
use App\Models\PaymentMethods;
use DB;
use File;

class OptionsController extends Controller
{

    public function __construct(Request $request)
    {   
    }



  


    public function CountryList(Request $request)
    {
        
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $country = Countries::get();

            return view('admin.country',compact("country"));

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }


    }


    public function AddUpdateCountry(Request $request)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();
            
            if (empty($input['country_id'])) 
            {   
                $data = array(
                        "name"                  => $input['country_name'],
                        "country_code"          => $input['country_code'],
                        "currency_short_name"   => $input['currency_short_name'],
                        "currency_standard_name" => $input['currency_standard_name'],
                        "flag_icon"             => $input['flag_icon'],
                        "is_show"               => 1,
                        "created_at"            => date('Y-m-d H:i:s'),
                        "updated_at"            => date('Y-m-d H:i:s'),
                        );
                if(Countries::insert($data))
                {
                    return array("status"=>"1","msg"=>"Country Added Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Failed");

                }
            }
            else
            {

                $data = array(
                        "name"                  => $input['country_name'],
                        "country_code"          => $input['country_code'],
                        "currency_short_name"   => $input['currency_short_name'],
                        "currency_standard_name" => $input['currency_standard_name'],
                        );
                if(Countries::where('id',$input['country_id'])->update($data))
                {
                    return array("status"=>"1","msg"=>"Country Updated Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Failed");
                }


            }

            
        } catch (Exception $e) 
        {
            
        }
    }

    public function CountryListAJAX(Request $request,$search_text)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($search_text)) 
            {       
                    $get_country_list = Countries::get();
            }
            else
            {   
                    $get_country_list = Countries::where('name','like','%'.$search_text.'%')
                                        ->get();

            }
            
            if (count($get_country_list)==0) 
            {
                ?>
                <tr><td colspan="6" class="text-center tx-18">No Country Found</td></tr>
                <?php
            }
            else
            {

                foreach ($get_country_list as $key) 
                {
                ?>

                    <tr id="country<?php echo $key['id']?>">
                      <td><?php echo $key['name']?></td>
                      <td><?php echo $key['country_code']?></td>
                      <td><?php echo $key['currency_standard_name']?> - <?php echo $key['currency_short_name']?></td>
                      <td> <img src="<?php echo env('IMG_URL')?><?php echo $key['flag_icon'] ?>" width="40" height="30"></td>
                      <td class="text-center">
                           <label class="switch switch-3d switch-primary mr-3">
                                                          <input id="visibility_value<?php echo $key['id']?>" onclick='CountryStatus("<?php echo $key['id']?>","<?php echo $key['is_show']; ?>")' type="checkbox" class="switch-input"  <?php if ($key['is_show'] ==1): ?>
                                                              checked
                                                          <?php endif ?> value="<?php echo $key['is_show'] ?>">
                                                          <span class="switch-label"></span>
                                                          <span class="switch-handle"></span>
                                                        </label>
                      </td>
                      <td class="text-center"> <a class="btn btn-primary" href="javascript:void(0)" onclick='EditCountry("<?php echo $key['id']?>","<?php echo $key['name']?>","<?php echo $key['country_code']?>","<?php echo $key['currency_short_name']?>","<?php echo $key['currency_standard_name']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" onclick='DeleteCountry("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a></td>
                    </tr>

                <?php
                } 
            }

        } 
        catch (Exception $e) 
        {
            
        }
    }

    public function DeleteCountry(Request $request,$id)
    {
        try 
        {
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            if(Countries::where('id',$id)->delete())
            {
                return array("status"=>"1","msg"=>"Country Deleted Successfully.");
            }
            else
            {
                return array("status"=>"0","msg"=>"Failed to Delete Country.");
            }
        } catch (Exception $e) 
        {
            
        }
    }

    

    public function ChangeCountryAvailability(Request $request,$id,$status)
    {
        try 
        {
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            if($status == 0)
            {
                Countries::where('id',$id)->update(array('is_show' =>1));
                return array("status"=>"1","msg"=>"Country Visible Successfully.");
            }
            else
            {
                Countries::where('id',$id)->update(array('is_show' => 0));
                return array("status"=>"1","msg"=>"Country Hide Successfully.");
            }



        } catch (Exception $e) 
        {
            
        }
    }
















    //_________________________________________________________________________________
    //_________________________________________________________________________________
    //_________________________________________________________________________________
























    public function PaymentMethodList(Request $request)
    {
        
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $payment_method = PaymentMethods::get();

            return view('admin.payment_method',compact("payment_method"));

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }


    }




    public function AddUpdatePayment(Request $request)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();
            
            if (empty($input['payment_id'])) 
            {   
                $data = array(
                        "name"                  => $input['payment_name'],
                        "is_show"               => 1,
                        "created_at"            => date('Y-m-d H:i:s'),
                        "updated_at"            => date('Y-m-d H:i:s'),
                        );
                if(PaymentMethods::insert($data))
                {
                    return array("status"=>"1","msg"=>"Payment Method Added Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Failed");

                }
            }
            else
            {

                $data = array(
                        "name"                  => $input['payment_name'],
                        );
                if(PaymentMethods::where('id',$input['payment_id'])->update($data))
                {
                    return array("status"=>"1","msg"=>"Payment Method Updated Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Failed");
                }


            }

            
        } catch (Exception $e) 
        {
            
        }
    }

    public function PaymentListAJAX(Request $request,$search_text)
    {
        try 
        {   
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($search_text)) 
            {       
                    $get_payment_list = PaymentMethods::get();
            }
            else
            {   
                    $get_payment_list = PaymentMethods::where('name','like','%'.$search_text.'%')
                                        ->get();

            }
            
            if (count($get_payment_list)==0) 
            {
                ?>
                <tr><td colspan="3" class="text-center tx-18">No Payment Method Found</td></tr>
                <?php
            }
            else
            {

                foreach ($get_payment_list as $key) 
                {
                ?>

                    <tr id="payment<?php echo $key['id']?>">
                      <td><?php echo $key['name']?></td>
                      <td class="tx-center">
                                                        <label class="switch switch-3d switch-primary mr-3">
                                                          <input id="visibility_value<?php echo $key['id']?>" onclick='PaymentStatus("<?php echo $key['id']?>","<?php echo $key['is_show']; ?>")' type="checkbox" class="switch-input"  <?php if ($key['is_show'] ==1): ?>
                                                              checked
                                                          <?php endif ?> value="<?php echo $key['is_show'] ?>">
                                                          <span class="switch-label"></span>
                                                          <span class="switch-handle"></span>
                                                        </label>
                                                    </td>
                                                    <td class="tx-center">
                                                         <a class="btn btn-primary" href="javascript:void(0)" onclick='EditPayment("<?php echo $key['id']?>","<?php echo $key['name']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<!-- <a class="btn btn-danger" onclick='DeletePayment("<?php //echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a> -->
                                                    </td>

                    </tr>

                <?php
                } 
            }

        } 
        catch (Exception $e) 
        {
            
        }
    }

    public function DeletePayment(Request $request,$id)
    {
        try 
        {
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);

            if(PaymentMethods::where('id',$id)->delete())
            {
                return array("status"=>"1","msg"=>"Payment Method Deleted Successfully.");
            }
            else
            {
                return array("status"=>"0","msg"=>"Failed to Delete Payment Method.");
            }
        } catch (Exception $e) 
        {
            
        }
    }

    

    public function ChangePaymentAvailability(Request $request,$id,$status)
    {
        try 
        {
            $user_id = session("admin_login.user_id");

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            if($status == 0)
            {
                PaymentMethods::where('id',$id)->update(array('is_show' =>1));
                return array("status"=>"1","msg"=>"Payment Method Visible Successfully.");
            }
            else
            {
                PaymentMethods::where('id',$id)->update(array('is_show' => 0));
                return array("status"=>"1","msg"=>"Payment Method Hide Successfully.");
            }



        } catch (Exception $e) 
        {
            
        }
    }









    public function checkUserAvailbility($id,$request)
    {   

       
        $user = Admin::where('id',$id)->where('is_blocked',0)->first();


        if ($user == "") 
        {   
            $request->session()->put("failed","Something went wrong.");
            header('Location:'.url('sign-out'));
            
            exit();
        }
        else
        {   
            return $user;
        }
    }
    


}
    