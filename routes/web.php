<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use Google\Service\Classroom\Teacher;
use App\Http\Controllers\InfoController;
use Google\Service\AdExchangeBuyer\Account;

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
Route::get('/thoi-khoa-bieu',[HomeController::class,'trangchusv']);
Route::get('/previous-week',[HomeController::class,'backDay']);
Route::get('/next-week',[HomeController::class,'nextDay']);

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
Route::get('/admin', [AdminController::class, 'admin']);
Route::get('/quan-ly-lop-hoc',[AdminController::class,'FrmThemLopHoc']);
Route::get('/quan-ly-sinh-vien',[AdminController::class,'frmAddSV']);

// Chọn option
Route::post('/option-row-14', [TeacherController::class,'ChiaDiem']);
//nĐiểm cột 16
Route::post('/nhap-diem', [TeacherController::class,'DiemCot16']);


//Admin Thêm sinh viên
Route::post('/them-sinh-vien',[AdminController::class,'themSinhVien']);
Route::get('/confirmToAdd',[AdminController::class,'confirmAddStudent']);
Route::get('/Delete-id',[AdminController::class,'DeletSinhVien']);

//Admin test thêm sinh viên vào lớp qua file excel
Route::get('/test-excel',[AdminController::class,'addStudentToClass']);
Route::post('/test-excel-ctrl',[AdminController::class,'addStudentToClassBack']);

//Admin thêm lớp
Route::post('/them-danh-sach',[AdminController::class,'ThemDanhSach']);
Route::get('/Delete-subject',[AdminController::class,'DeletLop']);
Route::get('/confirmToAddSubject',[AdminController::class,'ConfirmAddClass']);

//Admin thêm danh sách sinh viên lớp
Route::get('/Them-danh-sach-sv',[AdminController::class,'frmAddStudentList']);
Route::post('/them-danh-sach-sinh-vien',[AdminController::class,'ThemDanhSachSV']);
Route::get('/DeleteSV',[AdminController::class,'XoaKhoiDanhSach']);
Route::get('/confirmToAddDSSV',[AdminController::class,'XacNhanThemSV']);

//Thêm giảng viên
Route::get('/quan-ly-gv',[AdminController::class,'FrmThemGV']);
Route::post('/them-giang-vien',[AdminController::class,'ThemGV']);
Route::get('/Delete-gv-id',[AdminController::class,'XoaGVDSTam']);
Route::get('/confirmToAddGV',[AdminController::class,'XacNhanThemGV']);

//Thêm lớp học
Route::get('/them-lop-nien-khoa',[AdminController::class,'FrmThemLopNienKhoa']);
Route::post('/them-lop',[AdminController::class,'ThemLop']);
Route::get('/Delete-class-id',[AdminController::class,'XoaDSLopTam']);
Route::get('/confirmToAddClass',[AdminController::class,'XacNhanThemLopNK']);



//Cập nhật mật khẩu lần đầu
//thì sẽ luôn hiện popup
Route::get('/xac-nhan-nguoi-dung',[AccountController::class,'FrmChangePass']);
Route::post('/Confirmed',[AccountController::class,'ConfirmAndChangePass']);

//Trang thông tin & cập nhật thông tin
Route::get('/thong-tin-ca-nhan',[InfoController::class,'FrmAcessInfo']);
//Chỉnh sửa thông tin cá nhân
Route::get('/trang-thay-doi-thong-tin',[InfoController::class,'FrmChangeInfo']);
Route::post('/thay-doi-thong-tin',[InfoController::class,'ChangeInfoFunc'])->name('ChangeInfo');

//Hồ sơ giảng dạy
Route::get('/ho-so-tt-giang-day',[HomeController::class,'trangchusv']);
