<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SinhVienController;
use Google\Service\Classroom\Teacher;

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
Route::get('/diem-danh',[TeacherController::class,'DiemDanh'])->name('diemdanh');
Route::get('/form-diem-danh',[TeacherController::class,'qrcodeGenerate']);
//
Route::get('/tro-ve',[TeacherController::class,'trovedanhsach']);
//Hỗ trợ
Route::get('/ho-tro', [ContactController::class, 'contact']);

//Trang admin
Route::get('/admin', [SinhVienController::class, 'admin']);
Route::get('/quan-ly-lop-hoc',[SinhVienController::class,'FrmThemLopHoc']);
Route::get('/quan-ly-sinh-vien',[SinhVienController::class,'frmAddSV']);

// Chọn option
Route::post('/option-row-14', [TeacherController::class,'ChiaDiem']);
//nĐiểm cột 16
Route::post('/nhap-diem', [TeacherController::class,'DiemCot16']);


//Admin Thêm sinh viên

Route::post('/them-sinh-vien',[SinhVienController::class,'themSinhVien']);
Route::get('/confirmToAdd',[SinhVienController::class,'confirmAddStudent']);
Route::get('/Delete-id',[SinhVienController::class,'DeletSinhVien']);

//Admin thêm lớp

Route::post('/them-danh-sach',[SinhVienController::class,'ThemDanhSach']);
Route::get('/Delete-subject',[SinhVienController::class,'DeletLop']);
Route::get('/confirmToAddSubject',[SinhVienController::class,'ConfirmAddClass']);

//Admin thêm danh sách sinh viên lớp
Route::get('/Them-danh-sach-sv',[TeacherController::class,'frmAddStudentList']);
Route::post('/them-danh-sach-sinh-vien',[TeacherController::class,'ThemDanhSachSV']);
Route::get('/DeleteSV',[TeacherController::class,'XoaKhoiDanhSach']);
Route::get('/confirmToAddDSSV',[TeacherController::class,'XacNhanThemSV']);
