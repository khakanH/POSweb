<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\Category;
use App\Models\Product;

use File;

class ProductController extends Controller
{

    protected $member_model;
    protected $category_model;
    protected $product_model;

    public function __construct(Request $request)
    {   
        $this->member_model         = new Members();
        $this->category_model       = new Category();
        $this->product_model       = new Product();


        
    }



    public function CategoryList(Request $request)
    {
        $user_id = session("login")["user_id"];

        $company_id = session("login")["company_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $get_cate_list = $this->category_model
                                      ->where('company_id',$company_id)
                                      ->where('is_deleted',0)
                                      ->get();

        return view('category',['category_list'=>$get_cate_list]);
    }


    public function AddUpdateCategory(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $company_id = session("login")["company_id"];
            
            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();


             

            if (empty($input['cate_id'])) 
            {   
                $data = array(
                        "name"                  => $input['cate_name'],
                        "member_id"             => $user_id,
                        "company_id"            => $company_id,
                        "is_deleted"            => 0,
                        "created_at"            => date('Y-m-d H:i:s'),
                        "updated_at"            => date('Y-m-d H:i:s'),
                        );
                if($this->category_model->insert($data))
                {
                    return array("status"=>"1","msg"=>"Category Added Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Failed");

                }
            }
            else
            {
                $data = array(
                        "name"                  => $input['cate_name'],
                        "updated_at"            => date('Y-m-d H:i:s'),
                        );
                if($this->category_model->where('id',$input['cate_id'])->update($data))
                {
                    return array("status"=>"1","msg"=>"Category Updated Successfully.");
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

    public function CategoryListAJAX(Request $request,$search_text)
    {
        try 
        {   
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($search_text)) 
            {       
                    $get_cate_list = $this->category_model
                                      ->where('company_id',$company_id)
                                      ->where('is_deleted',0)
                                      ->get();
            }
            else
            {   
                    $get_cate_list = $this->category_model
                                      ->where('company_id',$company_id)
                                      ->where('is_deleted',0)
                                      ->where('name','like','%'.$search_text.'%')
                                      ->get();

            }
            
            if (count($get_cate_list)==0) 
            {
                ?>
                <tr><td colspan="4" class="text-center tx-18">No Category Found</td></tr>
                <?php
            }
            else
            {

                $count= 1;
                foreach ($get_cate_list as $key) 
                {
                ?>

                    <tr id="cate<?php echo $key['id']?>">
                      <td scope="row"><b><?php echo $count; ?></b></td>
                      <td><?php echo $key['id']?></td>
                      <td><?php echo $key['name']?></td>
                      <td><a class="" href="javascript:void(0)" onclick='EditCategory("<?php echo $key['id']?>","<?php echo $key['name']?>")'><i class="fa fa-edit tx-20"></i></a>&nbsp;&nbsp;&nbsp;<a class="" onclick='DeleteCategory("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash text-danger tx-20"></i></a></td>
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



    public function DeleteCategory(Request $request,$id)
    {
        try 
        {

            if($this->category_model->where('id',$id)->update(array('is_deleted'=>1)))
            {
                return array("status"=>"1","msg"=>"Category Deleted Successfully.");
            }
            else
            {
                return array("status"=>"0","msg"=>"Failed to Delete Category.");
            }
        } catch (Exception $e) 
        {
            
        }
    }


     public function CategoryNameList(Request $request,$cate_id)
    {
        try 
        {   
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

                 
                    $get_cate_list = $this->category_model
                                      ->where('company_id',$company_id)
                                      ->where('is_deleted',0)
                                      ->get();
           
            
            if (count($get_cate_list)==0) 
            {
                ?>
                <option value="" disabled="" selected="">Select Category</option>
                <?php
            }
            else
            {

                foreach ($get_cate_list as $key) 
                {
                ?>

                <option   
                        <?php if ((int)$cate_id== $key['id']): ?>
                            selected
                        <?php endif ?>
                 value="<?php echo $key['id'] ?>"><?php echo $key['name'] ?></option>
                    

                <?php
                } 
            }

        } 
        catch (Exception $e) 
        {
            
        }
    }


//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------


    public function ProductList(Request $request)
    {   
        $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $get_product_list = $this->product_model
                                      ->where('company_id',$company_id)
                                      ->where('is_deleted',0)
                                      ->get();

        return view('product',['product_list'=>$get_product_list]);
    }





    public function UploadProductsUsingCSV(Request $request)
    {
        try 
        {
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();  
        

            $file = $request->file('csv_file');

            $filename = $file->getClientOriginalName();
              $extension = $file->getClientOriginalExtension();
              $tempPath = $file->getRealPath();
              $fileSize = $file->getSize();
              $mimeType = $file->getMimeType();

              // Valid File Extensions
              $valid_extension = array("csv");

              // 2MB in Bytes
              $maxFileSize = 2097152; 

                    // Check file extension
      if(in_array(strtolower($extension),$valid_extension)){

        // Check file size
        if($fileSize <= $maxFileSize){

          // File upload location
          $destinationPath = public_path('/csv_files');
          $filename =  uniqid().".".$extension;

          // Upload file
          $file->move($destinationPath,$filename);

          //Import CSV to Database
          $filepath = public_path("csv_files/".$filename);

          // Reading file
          $file = fopen($filepath,"r");

          $importData_arr = array();
          $i = 0;

          while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
             $num = count($filedata );
            
             // Skip first row (Remove below comment if you want to skip the first row)
             if($i == 0){
                $i++;
                continue; 
             }
             for ($c=0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata [$c];
             }
             $i++;
          }
          fclose($file);

          // Insert to MySQL database
          foreach($importData_arr as $importData){

            $insertData = array(
                        "product_code"          => (string)$importData[1],
                        "name"                  => (string)$importData[2],
                        "image"                 => "product/default_product.png",
                        "category_id"           => 0,
                        "description"           => (string)$importData[3],
                        "cost"                  => (float)$importData[4],
                        "tax"                   => (float)$importData[5],
                        "price"                 => (float) $importData[4] + ((float)$importData[4]*(float)$importData[5]/100),
                        "member_id"             => $user_id,
                        "company_id"            => $company_id,
                        "is_deleted"            => 0,
                        "created_at"            => date("Y-m-d H:i:s"),
                        "updated_at"            => date("Y-m-d H:i:s"),
            );
            Product::insert($insertData);

          }
            return redirect()->route('product-list')->with('success','Product Uploaded Successfully!');

        }
        else
        {
            return redirect()->route('product-list')->with('failed','Invalid File Size! Kindly Use file Size of Minimum 2 MB');
        }

      }
      else
      {
        return redirect()->route('product-list')->with('failed','Invalid File Extension! Kindly Use Only CSV Files');
      }


        } 
        catch (Exception $e) 
        {
            
        }
    }

    public function AddUpdateProduct(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();


            
             

            if (empty($input['prod_id'])) 
            {   
                
                $image= $request->file('product_image');
                if (empty($image)) 
                {

                    $path = "product/default_product.png";
                    
                }
                else
                {

                    $input['imagename'] =  uniqid().'.webp';
                   
                    $destinationPath = public_path('/images/product');

                    if($image->move($destinationPath, $input['imagename']))
                    {
                            $path =  'product/'.$input['imagename'];
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Image Uploading");
                    }

                }
                $data = array(
                        "product_code"          => $input['prod_code'],
                        "name"                  => $input['prod_name'],
                        "image"                 => $path,
                        "category_id"           => $input['prod_cate'],
                        "description"           => $input['prod_descrip'],
                        "cost"                  => $input['prod_cost'],
                        "tax"                   => isset($input['prod_tax'])?$input['prod_tax']:0,
                        "price"                 => (float) $input['prod_cost'] + ($input['prod_cost']*(isset($input['prod_tax'])?$input['prod_tax']:0)/100),
                        "member_id"             => $user_id,
                        "company_id"            => $company_id,
                        "is_deleted"            => 0,
                        "created_at"            => date('Y-m-d H:i:s'),
                        "updated_at"            => date('Y-m-d H:i:s'),
                        );
                if($this->product_model->insert($data))
                {
                    return array("status"=>"1","msg"=>"Product Added Successfully.");
                }
                else
                {
                    return array("status"=>"0","msg"=>"Failed");

                }
            }
            else
            {

                

                $get_product = $this->product_model->where('id',$input['prod_id'])->first();


               
                $image= $request->file('product_image');
                if (empty($image)) 
                {

                    $path = $get_product->image;
                
                }
                else
                {   

                    $input['imagename'] =  uniqid().'.webp';
                   
                    $destinationPath = public_path('/images/product');

                    if($image->move($destinationPath, $input['imagename']))
                    {
                            $path =  'product/'.$input['imagename'];
                    }
                    else
                    {       
                        return array("status"=>"0","msg"=>"Something Went Wrong for Image Uploading.");
                    }
                    
                    if ($get_product->image != "product/default_product.png") 
                    {
                        $image_path = public_path('/images/'.$get_product->image);  // Value is not URL but directory file path
                        if(File::exists($image_path)) {
                            File::delete($image_path);
                        }
                    }


                }

                $data = array(
                        "product_code"          => $input['prod_code'],
                        "name"                  => $input['prod_name'],
                        "image"                 => $path,
                        "category_id"           => $input['prod_cate'],
                        "description"           => $input['prod_descrip'],
                        "cost"                  => $input['prod_cost'],
                        "tax"                   => isset($input['prod_tax'])?$input['prod_tax']:0,
                        "price"                 => (float) $input['prod_cost'] + ($input['prod_cost']*(isset($input['prod_tax'])?$input['prod_tax']:0)/100),
                        "updated_at"            => date('Y-m-d H:i:s'),
                        );
                if($this->product_model->where('id',$input['prod_id'])->update($data))
                {
                    return array("status"=>"1","msg"=>"Product Updated Successfully.");
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

    public function ProductListAJAX(Request $request,$search_text)
    {
        try 
        {   
            $user_id = session("login")["user_id"];
            $company_id = session("login")["company_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($search_text)) 
            {       
                    $get_prod_list = $this->product_model
                                      ->where('company_id',$company_id)
                                      ->where('is_deleted',0)
                                      ->get();
            }
            else
            {   
                    $get_prod_list = $this->product_model
                                      ->where('company_id',$company_id)
                                      ->where('is_deleted',0)
                                      ->where(function($query) use ($search_text)
                                            {
                                                $query->where('name','like','%'.$search_text.'%')
                                                ->orWhere('product_code','like','%'.$search_text.'%');
                                            })
                                      ->get();

            }
            
            if (count($get_prod_list)==0) 
            {
                ?>
                <tr><td colspan="10" class="text-center tx-18">No Product Found</td></tr>
                <?php
            }
            else
            {

                $count= 1;
                foreach ($get_prod_list as $key) 
                {
                ?>

                    <tr id="prod<?php echo $key['id']?>">
                      <td scope="row"><b><?php echo $count; ?></b></td>
                      <td><?php echo $key['product_code']?></td>
                      <td><?php echo $key['name']?></td>
                      <td><?php echo isset($key->category_name->name)?$key->category_name->name:"-"?></td>
                      <td><?php echo Str::limit($key['description'],35)?></td>
                      <td><?php echo $key['cost']?></td>
                      <td><?php echo $key['tax']?>%</td>
                      <td><?php echo $key['price']?></td>
                      <td><a class="" href="javascript:void(0)" onclick='EditProduct("<?php echo $key['id']?>","<?php echo $key['product_code']?>","<?php echo $key['name']?>","<?php echo $key['category_id']?>","<?php echo $key['cost']?>","<?php echo $key['tax']?>","<?php echo $key['price']?>","<?php echo $key['description']?>","<?php echo $key['image']?>")'><i class="fa fa-edit tx-20"></i></a>&nbsp;&nbsp;&nbsp;<a class="" onclick='DeleteProduct("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-20 text-danger"></i></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo env('IMG_URL').$key['image'] ?>" target="_blank" class=""><i class="fa fa-picture-o tx-20 text-success"></i></a></td>
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



     public function DeleteProduct(Request $request,$id)
    {
        try 
        {

            if($this->product_model->where('id',$id)->update(array('is_deleted'=>1)))
            {
                return array("status"=>"1","msg"=>"Product Deleted Successfully.");
            }
            else
            {
                return array("status"=>"0","msg"=>"Failed to Delete Product.");
            }
        } catch (Exception $e) 
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
    