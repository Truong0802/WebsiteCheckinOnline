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
use App\Http\Controllers\MailerController;

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
Route::post('/account-login',[AccountController::class,'login'])->name('login');

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

//Admin test thêm sinh viên vào lớp qua scan
Route::get('/quet-danh-sach',[AdminController::class,'addStudentToClass']);
Route::post('/Confirm-to-scan',[AdminController::class,'addStudentToClassBack'])->name('scanPost');

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

//Giảng viên chọn ban cán sự lớp môn học
Route::post('/chon-ban-can-su',[TeacherController::class,'CheckToPutLeader']);

//Admin thêm ban cán sự cho lớp niên khóa
Route::get('/bo-sung-ban-can-su',[AdminController::class,'FrmAddClassManage']);
Route::post('/Add-ban-can-su',[AdminController::class,'ConfirmAddClassManage'])->name('addClassManage');

//Phản hồi ý kiến

Route::post('/comment',[TeacherController::class,'AddnewComment']);


//Quên mật khẩu
Route::get('/forgot-password',[AccountController::class,'FrmForgotPassword']);
Route::post('/Confirmed-change-password',[AccountController::class,'FuncForgotPassword']);
Route::get('/mail-confirm=change',[AccountController::class,'ReceiveMail'])->name('ConfirmToChange');
Route::post('/Confirmed-change-password-from-mail',[AccountController::class,'FuncForgotPasswordByMail']);

//Mailer
Route::get('/mail-sent',[MailerController::class,'sendMail']);

//Danh sách tất cá giảng viên
Route::get('/teacher-list',[AdminController::class,'GetAllTeacherList']);
Route::get('/tim-kiem-giang-vien',[AdminController::class,'FindTeacherFromList']);
Route::get('/xoa-tim-kiem-gv',[AdminController::class,'ResetFindTeacherList']);

//Danh sách tất cả sinh viên
Route::get('/all-student-list',[AdminController::class,'GetAllStudentList']);
Route::get('/tim-kiem-tat-ca-sinh-vien',[AdminController::class,'FindStudentFromList']);
Route::get('/xoa-tim-kiem-tat-ca-sinh-vien',[AdminController::class,'ResetFindStudentList']);
// Route::get('/test-api', [HomeController::class,'getapi']);
