<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AdminController;
use \App\Http\Controllers\AdminAuthController;
use App\Http\Middleware\VerifyToken;
use App\Http\Middleware\UpdateSession;
use Illuminate\Http\Request;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\CarPageController;
use \App\Http\Controllers\MemberController;

Route::middleware([UpdateSession::class])->group(function () {
    Route::get('/auth/google/redirect',[Authcontroller::class,'redirectToProvider'])->name('google.auth.page');
    Route::get('/auth/google/callback',[Authcontroller::class,'handleProviderCallback']);

    Route::get('/auth/google/verify',[Authcontroller::class,'verify_phone'])->name('google.auth.verify_phone');
    Route::post('/auth/google/verify',[Authcontroller::class,'verify_phone_post'])->name('google.auth.verify_phone_post');
    Route::get('/auth/google/logout',[Authcontroller::class,'logout'])->name('google.auth.logout');

    Route::get('/',[HomeController::class,'home'])->name('home');
    Route::get('/notice',[HomeController::class,'notice'])->name('notice');
    Route::get('/rental/search',[HomeController::class,'search'])->name('rental_search');
    Route::post('/rental/search',[HomeController::class,'search_post'])->name('rental_search_post');
    Route::get('/rental/search/car/{id}', [CarPageController::class, 'getAvailability']);
    Route::post('/rental/reserve', [CarPageController::class, 'reserve'])->name('rental_reserve');

    Route::get('/member', [MemberController::class, 'member_center'])->name('member_center');
    Route::post('/member/rental/trash', [MemberController::class, 'rental_trash'])->name('rental_trash'); //取消訂單
    Route::post('/member/rental/edit', [MemberController::class, 'rental_edit'])->name('rental_edit'); //修改訂單
    Route::post('/auth/google/update/phone',[MemberController::class,'update_phone'])->name('google.auth.update_phone');
});
Route::post('/auth/google/verify/sendcode',[Authcontroller::class,'sendcode'])->name('google.auth.sendcode');


Route::get('/'.env('ADMIN_URL','admin').'/login',[AdminController::class,'login_page'])->name('admin.login.page');
Route::post('/'.env('ADMIN_URL','admin').'/login',[AdminController::class,'login_auth'])->name('admin.login.auth');


// 將所有以 'manager' 開頭的路徑進行保護
Route::middleware([VerifyToken::class])
    ->prefix('manager')  // 設定路徑前綴為 manager
    ->group(function () {
        // 登出路由
        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        Route::get('/', [AdminController::class, 'home'])->name('admin.home');
        Route::get('/rental/{status}', [AdminController::class, 'rental'])->name('admin.rental'); //訂單管理

        Route::post('/rental/update', [AdminController::class, 'rental_update'])->name('admin.rental_update'); //訂單更新

        Route::get('/car-management', [AdminController::class, 'carManagement'])->name('admin.carManagement');
        
        //管理員-保險
        Route::get('/insurance-management', [AdminController::class, 'insuranceManagement'])->name('admin.insuranceManagement');
        Route::get('insurance/{id}/edit', [AdminController::class, 'editInsurance'])->name('admin.insurance.edit');;
        Route::post('/insurance/update', [AdminController::class, 'updateInsurance'])->name('admin.insurance.update'); //保險更新
        Route::post('/insurance/add', [AdminController::class, 'addInsurance']);
        Route::delete('/insurance/{id}/delete', [AdminController::class, 'deleteInsurance']);
        
        //管理員-地點
        Route::get('/store-info', [AdminController::class, 'storeInfo'])->name('admin.storeInfo');
        Route::post('/location/add', [AdminController::class, 'addLocation']);
        Route::get('/location/{id}/edit', [AdminController::class, 'editLocation']);
        Route::post('/location/update', [AdminController::class, 'updateLocation']);
        Route::delete('/location/{id}/delete', [AdminController::class, 'deleteLocation']);

        //管理員-車型
        Route::post('/car_management/add_model', [AdminController::class, 'addModel']);
        Route::get('/car_management/{id}/edit_model', [AdminController::class, 'editModel']);
        Route::post('/car_management/update_model', [AdminController::class, 'updateModel']);
        Route::delete('/car_management/{id}/delete_model', [AdminController::class, 'deleteModel']);
        
        //管理員-汽車
        Route::post('/car_management/add_car', [AdminController::class, 'addCar']);
        Route::delete('/car_management/{id}/delete_car', [AdminController::class, 'deleteCar']);
        Route::get('/car_management/{id}/edit_car', [AdminController::class, 'editCar']);
        Route::post('/car_management/update_car', [AdminController::class, 'updateCar']);
        
        //管理員-會員
        Route::get('/members', [AdminController::class, 'showMembers'])->name('admin.memberManagement');
        Route::get('/pictureManagement', [AdminController::class, 'pictureManagement'])->name('admin.pictureManagement');
        Route::post('/pictureManagement/upload', [AdminController::class, 'uploadImage'])->name('admin.uploadImage');
        Route::delete('/pictureManagement/delete', [AdminController::class, 'deleteImage'])->name('admin.deleteImage');

        Route::fallback(function () {
            return redirect()->route('admin.home');
        });
    });


