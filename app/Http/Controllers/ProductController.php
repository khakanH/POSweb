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

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $get_cate_list = $this->category_model
                                      ->where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->paginate(15);

        return view('category',['category_list'=>$get_cate_list]);
    }


    public function AddUpdateCategory(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();


             

            if (empty($input['cate_id'])) 
            {   
                $data = array(
                        "name"                  => $input['cate_name'],
                        "member_id"             => $user_id,
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

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($search_text)) 
            {       
                    $get_cate_list = $this->category_model
                                      ->where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->paginate(15);
            }
            else
            {   
                    $get_cate_list = $this->category_model
                                      ->where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->where('name','like','%'.$search_text.'%')
                                      ->paginate(15);

            }
            
            if (count($get_cate_list)==0) 
            {
                ?>
                <tr><td colspan="3" class="text-center tx-18">No Category Found</td></tr>
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
                      <td><?php echo $key['name']?></td>
                      <td><a class="btn btn-primary" href="javascript:void(0)" onclick='EditCategory("<?php echo $key['id']?>","<?php echo $key['name']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" onclick='DeleteCategory("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a></td>
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

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

                 
                    $get_cate_list = $this->category_model
                                      ->where('member_id',$user_id)
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

        $user_info = $this->checkUserAvailbility($user_id,$request);

        $get_product_list = $this->product_model
                                      ->where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->paginate(15);

        return view('product',['product_list'=>$get_product_list]);
    }


    public function AddUpdateProduct(Request $request)
    {
        try 
        {   
            $user_id = session("login")["user_id"];

            $user_info = $this->checkUserAvailbility($user_id,$request);
            
            $input = $request->all();


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
             

            if (empty($input['prod_id'])) 
            {   
                $data = array(
                        "product_code"          => $input['prod_code'],
                        "name"                  => $input['prod_name'],
                        "image"                 => $path,
                        "category_id"           => $input['prod_cate'],
                        "description"           => $input['prod_descrip'],
                        "cost"                  => $input['prod_cost'],
                        "tax"                   => $input['prod_tax'],
                        "price"                 => $input['prod_price'],
                        "member_id"             => $user_id,
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

                if (!empty($image)) 
                {   
                    $get_product = $this->product_model->where('id',$input['prod_id'])->first();
                    $image_path = public_path('images/'.$get_product->image);  // Value is not URL but directory file path

                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }

                    
                }

                $data = array(
                        "product_code"          => $input['prod_code'],
                        "name"                  => $input['prod_name'],
                        "image"                 => $path,
                        "category_id"           => $input['prod_cate'],
                        "description"           => $input['prod_descrip'],
                        "cost"                  => $input['prod_cost'],
                        "tax"                   => $input['prod_tax'],
                        "price"                 => $input['prod_price'],
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

            $user_info = $this->checkUserAvailbility($user_id,$request);
            

            if (empty($search_text)) 
            {       
                    $get_prod_list = $this->product_model
                                      ->where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->paginate(15);
            }
            else
            {   
                    $get_prod_list = $this->product_model
                                      ->where('member_id',$user_id)
                                      ->where('is_deleted',0)
                                      ->where(function($query) use ($search_text)
                                            {
                                                $query->where('name','like','%'.$search_text.'%')
                                                ->orWhere('product_code','like','%'.$search_text.'%');
                                            })
                                      ->paginate(15);

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
                      <td><?php echo $key->category_name->name?></td>
                      <td><?php Str::limit($key['description'],35)?></td>
                      <td><?php echo $key['cost']?></td>
                      <td><?php echo $key['tax']?></td>
                      <td><?php echo $key['price']?></td>
                      <td><a class="btn btn-primary" href="javascript:void(0)" onclick='EditProduct("<?php echo $key['id']?>","<?php echo $key['product_code']?>","<?php echo $key['name']?>","<?php echo $key['category_id']?>","<?php echo $key['cost']?>","<?php echo $key['tax']?>","<?php echo $key['price']?>","<?php echo $key['description']?>","<?php echo $key['image']?>")'><i class="fa fa-edit tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" onclick='DeleteProduct("<?php echo $key['id'] ?>")' href="javascript:void(0)"><i class="fa fa-trash tx-15"></i></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo env('IMG_URL').$key['image'] ?>" class="btn btn-success"><i class="fa fa-picture-o"></i></a></td>
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

        $user = $this->member_model->where('id',$id)->first();


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
    