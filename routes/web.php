<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/getAllBooks", [App\Http\Controllers\BookDetailsController::class, "getAllBooks"]);
Route::get("/getBooksByAuthorName/{name}/{exactMatch}", [App\Http\Controllers\AuthorsController::class, "getBooksByAuthorName"]);
Route::get("/getBooksByName/{name}/{exactMatch}", [App\Http\Controllers\BookDetailsController::class, "searchByBookName"]);
Route::get("/getTheBookDetail/{bookId}", [App\Http\Controllers\BookDetailsController::class, "gettheBookDetail"]);
Route::get("/add/", [App\Http\Controllers\CartController::class, "add"]);
Route::get("/viewCart/{userId}", [App\Http\Controllers\CartController::class, "viewCart"]);
Route::get("/checkout/{userId}", [App\Http\Controllers\CartController::class, "checkout"]);
Route::get("/updateCount/", [App\Http\Controllers\CartController::class, "updateCount"]);
Route::get("/delete/{cartId}", [App\Http\Controllers\CartController::class, "delete"]);
Route::get("/deleteAll/{userId}", [App\Http\Controllers\CartController::class, "deleteAll"]);