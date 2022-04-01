<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DetailTransaksiController;

Route::post('login',[AuthController::class,'login']);
Route::post('register', [AuthController::class, 'store']);



Route::group(['middleware'=> ['jwt.verify:admin,kasir,owner']], function() {
    route::get('login/check', [AuthController::class, 'logincheck']);
    route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user', [AuthController::class, 'store']);
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::post('report', [TransaksiController::class, 'report']);
});

//khusus admin
Route::group(['middleware' => ['jwt.verify:admin']], function() {

    //OUTLET
    Route::get('outlet', [OutletController::class, 'store']);
    Route::get('outlet', [OutletController::class, 'getAll']);
    Route::get('outlet/{id}', [OutletController::class, 'getById']);
    Route::post('outlet', [OutletController::class, 'store']);
    Route::put('outlet/{id}', [OutletController::class, 'update']);
    Route::delete('outlet/{id}', [OutletController::class, 'delete']);


    //USER
    route::post('user', [UserController::class, 'store']);
    route::get('user', [UserController::class, 'getAll']);
    route::get('user/{id}', [UserController::class, 'getById']);
    route::put('user/{id}', [UserController::class, 'update']);
    route::delete('user/{id}', [UserController::class, 'delete']);

    
});

//khusus admin dan kasir 
Route::group(['middleware' => ['jwt.verify:admin,kasir']], function() {
  
    //member  
    route::post('member', [MemberController::class, 'store']);
    route::get('member', [MemberController::class, 'getAll']);
    route::get('member/{id}', [MemberController::class, 'getById']);
    route::put('member/{id}', [MemberController::class, 'update']);
    route::delete('member/{id}', [MemberController::class, 'delete']);

    //PAKET
    route::post('paket', [PaketController::class, 'store']);
    route::get('paket', [PaketController::class, 'getAll']);
    route::get('paket/{id}', [PaketController::class, 'getById']);
    route::put('paket/{id}', [PaketController::class, 'update']);
    route::delete('paket/{id}', [PaketController::class, 'delete']);

    //TRANSAKSI
    Route::post('transaksi', [TransaksiController::class, 'store']);
    Route::get('transaksi/{id}', [TransaksiController::class, 'getById']);
    Route::get('transaksi', [TransaksiController::class, 'getAll']);
    Route::put('transaksi/{id}', [TransaksiController::class, 'update']);

    //DETAIL TRANSAKSI
    Route::post('detail_transaksi', [DetailTransaksiController::class, 'store']);
    Route::get('transaksi/detail/{id}', [DetailTransaksiController::class, 'getById']);
    Route::post('transaksi/status/{id}', [TransaksiController::class, 'changeStatus']);
    Route::post('transaksi/bayar/{id}', [TransaksiController::class, 'bayar']);
    Route::get('transaksi/total/{id}', [DetailTransaksiController::class, 'getTotal']);      
   
});
Route::group(['middleware' => ['jwt.verify:owner']], function() {
    
});
