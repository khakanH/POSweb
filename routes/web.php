<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;

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




});