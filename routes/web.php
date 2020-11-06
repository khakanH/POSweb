<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;

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




Route::middleware(['LoginSession'])->group(function () 
{
	Route::get('dashboard',[DashboardController::class, 'Index'])->name('dashboard');

	Route::get('settings',[AccountController::class, 'Settings'])->name('settings');
	Route::post('add-company-info',[AccountController::class, 'AddCompanyInfo'])->name('add-company-info');
	
	
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

	Route::post('decrease-bill-product-item',[POSController::class,'DecreaseBillProductItem'])->name('decrease-bill-product-item');
	Route::post('increase-bill-product-item',[POSController::class,'IncreaseBillProductItem'])->name('increase-bill-product-item');
	Route::post('change-bill-product-quantity',[POSController::class,'ChangeBillProductQuantity'])->name('change-bill-product-quantity');
	Route::post('apply-bill-tax',[POSController::class,'ApplyBillTax'])->name('apply-bill-tax');


	//_________________________________________________________________________________




});