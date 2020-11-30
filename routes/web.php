<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;

//for Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\OptionsController;
use App\Http\Controllers\Admin\ModuleController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/api', function () {


		$response = Http::withToken('1298b5eb-b252-3d97-8622-a4a69d5bf818')->post('https://esp.fbr.gov.pk:8244/FBR/v1/api/Live/PostData',[
		    
		"InvoiceNumber"=>"",
		"POSID"=>110014,
		"USIN"=>"USIN0",
		"DateTime"=>"2020-01-01 12:00:00",
		"BuyerNTN"=>"1234567-8",
		"BuyerCNIC"=>"12345-1234567-8",
		"BuyerName"=>"Buyer Name",
		"BuyerPhoneNumber"=>"0000-0000000",
		"TotalBillAmount"=>0.0,
		"TotalQuantity"=>0.0,
		"TotalSaleValue"=>0.0,
		"TotalTaxCharged"=>0.0,
		"Discount"=>0.0,
		"FurtherTax"=>0.0,
		"PaymentMode"=>1,

		"RefUSIN"=>null,
		"InvoiceType"=>1,
		"Items"=>[
		[
		"ItemCode"=>"IT_1011",
		"ItemName"=>"Test Item",
		"Quantity"=>1.0,
		"PCTCode"=>"",
		"TaxRate"=>0.0,
		"SaleValue"=>0.0,
		"TotalAmount"=>0.0,
		"TaxCharged"=>0.0,
		"Discount"=>0.0,
		"FurtherTax"=>0.0,
		"InvoiceType"=>1,
		"RefUSIN"=>null
		],

		[
		"ItemCode"=>"IT_1012",
		"ItemName"=>"Test Item",
		"Quantity"=>1.0,
		"PCTCode"=>"",
		"TaxRate"=>0.0,
		"SaleValue"=>0.0,
		"TotalAmount"=>0.0,
		"TaxCharged"=>0.0,
		"Discount"=>0.0,
		"FurtherTax"=>0.0,
		"InvoiceType"=>1,
		"RefUSIN"=>null
		]
		]

		])->json();

		dump($response);
});


Route::get('/', function () {
    return view('login');
})->name('index')->middleware('CheckLogin');


Route::post('/login', [AccountController::class, 'Login'])->name('login');



Route::get('signout',[AccountController::class, 'SignOut'])->name('signout');


Route::get('/signup', function () {
    return view('signup');
})->name('signup');


Route::post('/register', [AccountController::class, 'Register'])->name('register');

Route::post('/send-verification-code', [AccountController::class, 'SendVerificationCode'])->name('send-verification-code');
Route::post('/check-verification-code', [AccountController::class, 'CheckVerificationCode'])->name('check-verification-code');

Route::get('forgot-password/{email}',[AccountController::class, 'ForgotPassword'])->name('forgot-password');
Route::get('reset-password/{email}',[AccountController::class, 'ResetPassword'])->name('reset-password');
Route::post('save-new-password',[AccountController::class, 'SaveNewPassword'])->name('save-new-password');




Route::get('verify-email/{email}',[AccountController::class, 'VerifyEmail'])->name('verify-email');



Route::get('settings',[AccountController::class, 'Settings'])->name('settings')->middleware('CheckMemberRoles');
Route::post('add-company-info',[AccountController::class, 'AddCompanyInfo'])->name('add-company-info')->middleware('CheckMemberRoles');



Route::middleware(['LoginSession','CheckMemberRoles'])->group(function () 
{
	Route::get('dashboard',[DashboardController::class, 'Index'])->name('dashboard');
	

	Route::get('edit-profile',[AccountController::class, 'EditProfile'])->name('edit-profile');
	Route::post('save-profile',[AccountController::class, 'SaveProfile'])->name('save-profile');
	Route::post('change-email-address-check',[AccountController::class, 'ChangeEmailAddressCheck'])->name('change-email-address-check');
	Route::post('change-password',[AccountController::class, 'ChangePassword'])->name('change-password');



	
	
	//_______C A T E G O R Y -- R O U T E S________________________________________
	Route::get('category-list',[ProductController::class,'CategoryList'])->name('category-list');
	Route::get('get-category-list-AJAX/{search_text}',[ProductController::class,'CategoryListAJAX'])->name('get-category-list-AJAX');
	Route::post('add-update-category',[ProductController::class,'AddUpdateCategory'])->name('add-update-category');
	Route::get('delete-category/{id}',[ProductController::class,'DeleteCategory'])->name('delete-category');


	Route::get('get-category-name-list/{cate_id}',[ProductController::class,'CategoryNameList'])->name('get-category-name-list');



	//_________________________________________________________________________________





	//_______P R O D U C T -- R O U T E S________________________________________
	Route::get('product-list',[ProductController::class, 'ProductList'])->name('product-list');
	Route::get('get-product-list-AJAX/{search_text}',[ProductController::class,'ProductListAJAX'])->name('get-product-list-AJAX');
	Route::post('add-update-product',[ProductController::class,'AddUpdateProduct'])->name('add-update-product');
	Route::post('upload-products-using-csv',[ProductController::class,'UploadProductsUsingCSV'])->name('upload-products-using-csv');
	Route::get('delete-product/{id}',[ProductController::class,'DeleteProduct'])->name('delete-product');
	//_________________________________________________________________________________


	//_______P O S -- R O U T E S________________________________________
	Route::get('pos',[POSController::class, 'Index'])->name('pos');
	Route::get('get-pos-product-list/{cate_id}/{search_text}',[POSController::class, 'GetPOSProductList'])->name('get-pos-product-list');
	Route::get('create-new-bill',[POSController::class, 'CreateNewBill'])->name('create-new-bill');
	Route::get('delete-last-bill',[POSController::class, 'DeleteLastBill'])->name('delete-last-bill');
	Route::get('get-bill-nav-links',[POSController::class, 'GetBillNavLinks'])->name('get-bill-nav-links');
	Route::get('get-pending-bill/{id}',[POSController::class, 'GetPendingBill'])->name('get-pending-bill');
	Route::get('cancel-bill/{id}',[POSController::class, 'CancelBill'])->name('cancel-bill');

			//________ B I L L -- P R O D U C T S -- R O U T E S_______

	Route::post('add-product-to-bill',[POSController::class,'AddProductToBill'])->name('add-product-to-bill');
	Route::get('calculate-total-bill/{id}',[POSController::class, 'CalculateTotalBill'])->name('calculate-total-bill');
	Route::post('delete-product-from-bill',[POSController::class,'DeleteProductFromBill'])->name('delete-product-from-bill');

	Route::get('get-pos-product-by-barcode/{val}',[POSController::class, 'GetPOSProductByBarcode'])->name('get-pos-product-by-barcode');



	Route::post('decrease-bill-product-item',[POSController::class,'DecreaseBillProductItem'])->name('decrease-bill-product-item');
	Route::post('increase-bill-product-item',[POSController::class,'IncreaseBillProductItem'])->name('increase-bill-product-item');
	Route::post('change-bill-product-quantity',[POSController::class,'ChangeBillProductQuantity'])->name('change-bill-product-quantity');
	Route::post('apply-bill-tax',[POSController::class,'ApplyBillTax'])->name('apply-bill-tax');
	Route::post('apply-bill-discount',[POSController::class,'ApplyBillDiscount'])->name('apply-bill-discount');


			//________ B I L L -- C U S T O M E R S -- R O U T E S_______

	Route::post('add-update-customer',[BillController::class,'AddUpdateCustomer'])->name('add-update-customer');
	Route::get('get-customers-list',[BillController::class,'GetCustomersList'])->name('get-customers-list');
	Route::get('change-bill-customer/{cust_id}/{bill_id}',[BillController::class, 'ChangeBillCustomer'])->name('change-bill-customer');

	Route::get('get-bill-details/{id}',[BillController::class, 'GetBillDetails'])->name('get-bill-details');
	Route::get('get-payment-method',[BillController::class, 'GetPaymentMethod'])->name('get-payment-method');
	Route::post('add-sale',[BillController::class,'AddSale'])->name('add-sale');

	Route::get('get-bill-receipt/{id}',[BillController::class, 'GetBillReceipt'])->name('get-bill-receipt');

	//_________________________________________________________________________________

	


	//________________________ C U S T O M E R S -- R O U T E S________________________

	Route::get('customers',[CustomerController::class,'Index'])->name('customers');
	Route::get('get-customer-list-AJAX/{search_text}',[CustomerController::class,'CustomerListAJAX'])->name('get-customer-list-AJAX');
	Route::get('delete-customer/{id}',[CustomerController::class,'DeleteCustomer'])->name('delete-customer');
	//_________________________________________________________________________________




	//_______________________________ S A L E S -- R O U T E S ________________________

	Route::get('sales',[BillController::class,'Index'])->name('sales');
	Route::post('get-sale-list-AJAX',[BillController::class,'SaleListAJAX'])->name('get-sale-list-AJAX');
	Route::get('delete-sale/{id}',[BillController::class,'DeleteSale'])->name('delete-sale');
	
	Route::get('get-bill-sale-items/{id}',[BillController::class,'BillSaleItems'])->name('get-bill-sale-items');
	Route::get('export-sale-csv',[BillController::class,'ExportSaleCSV'])->name('export-sale-csv');

	//_________________________________________________________________________________





	//___________________ M E M B E R -- T Y P E -- M A N A G E M E N T____________________

	Route::get('users',[UserController::class,'Index'])->name('users');
	Route::get('get-user-list-AJAX/{search_text}',[UserController::class,'UserListAJAX'])->name('get-user-list-AJAX');
	Route::post('add-user',[UserController::class,'AddUser'])->name('add-user');
	// Route::get('delete-user/{id}',[UserController::class,'DeleteUser'])->name('delete-user');
	Route::get('block-unblock-user/{id}',[UserController::class,'BlockUnblockUser'])->name('block-unblock-user');
	Route::get('get-member-type',[UserController::class,'MemberType'])->name('get-member-type');

	//_________________________________________________________________________________

});




//Admin Route
Route::prefix('admin')->group(function () {
	
	Route::get('/',function(){
		return view('admin.login');
	})->name('admin_index')->middleware('AdminCheckLogin');
	Route::post('sign-in',[AdminController::class,'Login'])->name('sign-in');
	Route::get('sign-out',[AdminController::class,'Logout'])->name('sign-out');


	Route::middleware(['AdminLoginSession'])->group(function () 
	{
        Route::get('admin_dashboard',[AdminController::class,'Dashboard'])->name('admin_dashboard');
        
        Route::get('admin_account',[AdminController::class,'Account'])->name('admin_account');
		Route::post('save-profile-admin',[AdminController::class, 'SaveProfile'])->name('save-profile-admin');
		Route::post('change-email-address-check-admin',[AdminController::class, 'ChangeEmailAddressCheck'])->name('change-email-address-check-admin');
		Route::post('change-password-admin',[AdminController::class, 'ChangePassword'])->name('change-password-admin');



        Route::get('members-list',[MemberController::class,'MemberList'])->name('members-list');
        Route::get('get-member-details/{id}',[MemberController::class,'MemberDetails'])->name('get-member-details');
		Route::get('block-unblock-member/{id}',[MemberController::class,'BlockUnblockMember'])->name('block-unblock-member');
		Route::get('get-member-list-AJAX/{search_text}',[MemberController::class,'MemberListAJAX'])->name('get-member-list-AJAX');



        Route::get('member-types',[MemberController::class,'MemberTypes'])->name('member-types');
		Route::get('get-member-type-list-AJAX/{search_text}',[MemberController::class,'MemberTypeListAJAX'])->name('get-member-type-list-AJAX');
		Route::post('add-update-member-type',[MemberController::class,'AddUpdateMemberType'])->name('add-update-member-type');
		Route::get('delete-member-type/{id}',[MemberController::class,'DeleteMemberType'])->name('delete-member-type');
		Route::get('change-member-type-availability/{id}/{status}',[MemberController::class,'ChangeMemberTypeAvailability'])->name('change-member-type-availability');

        Route::get('member-roles',[MemberController::class,'MemberRoles'])->name('member-roles');
        Route::post('save-roles',[MemberController::class,'SaveRoles'])->name('save-roles');


        Route::get('get-member-roles-AJAX/{id}',[MemberController::class,'MemberRolesAJAX'])->name('get-member-roles-AJAX');

	//_________________________________________________________________________________


        Route::get('country-list',[OptionsController::class,'CountryList'])->name('country-list');
		Route::get('get-country-list-AJAX/{search_text}',[OptionsController::class,'CountryListAJAX'])->name('get-country-list-AJAX');
		Route::post('add-update-country',[OptionsController::class,'AddUpdateCountry'])->name('add-update-country');
		Route::get('delete-country/{id}',[OptionsController::class,'DeleteCountry'])->name('delete-country');
		Route::get('change-country-availability/{id}/{status}',[OptionsController::class,'ChangeCountryAvailability'])->name('change-country-availability');
        
	//_________________________________________________________________________________


        Route::get('payment-method-list',[OptionsController::class,'PaymentMethodList'])->name('payment-method-list');
		Route::get('get-payment-list-AJAX/{search_text}',[OptionsController::class,'PaymentListAJAX'])->name('get-payment-list-AJAX');
		Route::post('add-update-payment-method',[OptionsController::class,'AddUpdatePayment'])->name('add-update-payment-method');
		Route::get('delete-payment-method/{id}',[OptionsController::class,'DeletePayment'])->name('delete-payment-method');
		Route::get('change-payment-availability/{id}/{status}',[OptionsController::class,'ChangePaymentAvailability'])->name('change-payment-availability');


	//_________________________________________________________________________________

        Route::get('website-modules',[ModuleController::class,'WebsiteModule'])->name('website-modules');
		Route::get('get-module-list-AJAX/{search_text}',[ModuleController::class,'ModuleListAJAX'])->name('get-module-list-AJAX');
		Route::post('add-update-module',[ModuleController::class,'AddUpdateModule'])->name('add-update-module');
		Route::get('delete-module/{id}',[ModuleController::class,'DeleteModule'])->name('delete-module');



	});
});
