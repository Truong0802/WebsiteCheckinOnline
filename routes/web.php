<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/',function () {
//         return view('Account.login');
//  });
Route::get('/',[AccountController::class,'index']);
//Đăng nhập
Route::post('/account-login',[AccountController::class,'login']);
//Đăng xuất
Route::get('/logout',[AccountController::class,'logout']);
//Trang thời khóa biểu sinh viên
Route::get('/trang-chu',[HomeController::class,'trangchusv']);
//Trang danh sách lớp của giảng viên
Route::get('/danh-sach-lop',[TeacherController::class,'danhsachlop']);
//Danh sách sinh viên
Route::get('/danh-sach-sinh-vien',[TeacherController::class,'danhsachsinhvien']);
//Tìm kiếm trong danh sách lớp
Route::get('/tim-kiem',[TeacherController::class,'timkiem']);
//
Route::get('/xoa-tim-kiem',[TeacherController::class,'removetimkiem']);

//Tìm kiếm trong danh sách sinh viên
Route::get('/tim-kiem-sinh-vien',[TeacherController::class,'timkiemsinhvien']);
//
Route::get('/xoa-tim-kiem-sv',[TeacherController::class,'removetimkiemsv']);
//Điểm danh
Route::get('/diem-danh',[TeacherController::class,'DiemDanh']);
