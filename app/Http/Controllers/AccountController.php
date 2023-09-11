<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;
Use Exception;
use hisorange\BrowserDetect\Parser as Browser;
use Jenssegers\Agent\Facades\Agent;
class AccountController extends Controller
{
    //
    public function index()
    {
        if(session()->exists('studentid') || session()->exists('teacherid'))
        {
            if(session()->exists('teacherid'))
            {
                return redirect()->to('/trang-chu');
            }
            elseif(session()->exists('studentid'))
            {

                return redirect()->to('/trang-chu');
            }
        }
        else{
            return view('Account.login');
        }
    }


    public function login(Request $request)
    {


        if(session()->has('cdLoginRequest'))
        {

            $start = session()->get('cdLoginRequest');
            $end_date = $start->copy()->addDays(7);
            $diff = $end_date->diffInDays($start);
            if($diff == 0)
            {
                session()->forget('cdLoginRequest');
                            //Tùy biến mã lỗi trả về
                $messages = [
                    'username.required' => 'Không được để trống Tài khoản đăng nhập',
                    'username.max' => 'Tài khoản nhập vào không hợp lệ.
                                Vui lòng nhập Mã sinh viên/ Mã giảng viên để tiếp tục',
                    //Dành cho phần đổi mật khẩu
                    'password.min' => 'Mật khẩu phải lớn hơn 4 ký tự',
                    'password.regex' => 'Mật khẩu nhập không hợp lệ',
                    'password.required' => 'Không được để trống Mật khẩu',

                ];

                //Điều kiện lọc lỗi
                $validated = $request->validate([
                    'username' => 'required|max:20',
                    'password' => 'required|min:4|regex:/^.*(?=.*[!$#%]).*$/',
                    //Thêm điều kiện nếu set validation cho đổi mật khẩu:|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/
                ], $messages);


                $username = $request->username;
                $password = md5($request->password);
                $params = [
                    $username,
                    $password,
                ];
                //$studentLogin = DB::table('sinh_vien')->where('MSSV',$username)->where('password',$password)->first();
                //$teacherLogin = DB::table('giang_vien')->where('MSGV',$username)->where('password',$password)->first();
                try{
                    $studentLogin= DB::select('select * from sinh_vien where MSSV = ? and password = ?', $params)[0];
                    if($studentLogin != null){
                        session()->put('studentid',$studentLogin->MSSV);
                        session()->put('name',$studentLogin->HoTenSV);
                        session()->put('malop',$studentLogin->MaLop);
                        return redirect()->to('/trang-chu');
                    }
                }
                catch(Exception $ex){
                    try{
                        $teacherLogin = DB::select('select * from giang_vien where MSGV = ? and password = ?', $params)[0];
                        if($teacherLogin != null)
                        {

                            session()->put('teacherid',$teacherLogin->MSGV);
                            session()->put('name',$teacherLogin->HoTenGV);
                            session()->put('ChucVu',$teacherLogin->MaChucVu);
                            //Thêm điều kiện lấy thông tin khoa để mở rộng quyền truy cập của GV/Quản lý của các khoa khác nhau
                            if($teacherLogin->MaChucVu == 'AM' || $teacherLogin->MaChucVu == 'QL')
                            {
                                return redirect()->to('/admin');
                            }
                            elseif($teacherLogin->MaChucVu == 'GV')
                            {
                                return redirect()->to('/trang-chu');
                            }
                        }
                    }catch(Exception $ex)
                    {
                        return redirect()->to('/')->with('error-Login','Tài khoản hoặc mật khẩu không hợp lệ')->withInput();
                    }

                }
            }
            else{
                return redirect()->to('/')->with('error-Login','Thời gian chờ còn lại: ('.$diff.") ngày")->withInput();
            }
        }
        else
        {
            // session()->put('cdLoginRequest', Carbon::now()->addDays(7));
                          //Tùy biến mã lỗi trả về
                          $messages = [
                            'username.required' => 'Không được để trống Tài khoản đăng nhập',
                            'username.max' => 'Tài khoản nhập vào không hợp lệ.
                                        Vui lòng nhập Mã sinh viên/ Mã giảng viên để tiếp tục',
                            'password.required' => 'Không được để trống Mật khẩu'
                        ];

                        //Điều kiện lọc lỗi
                        $validated = $request->validate([
                            'username' => 'required|max:20',
                            'password' => 'required',
                        ], $messages);


                        $username = $request->username;
                        $password = md5($request->password);
                        $params = [
                            $username,
                            $password,
                        ];
                        try{
                            $studentLogin= DB::select('select * from sinh_vien where MSSV = ? and password = ?', $params)[0];
                            if($studentLogin != null){
                                session()->put('studentid',$studentLogin->MSSV);
                                session()->put('name',$studentLogin->HoTenSV);
                                session()->put('malop',$studentLogin->MaLop);
                                return redirect()->to('/trang-chu');
                            }
                        }
                        catch(Exception $ex){
                            try{
                                $teacherLogin = DB::select('select * from giang_vien where MSGV = ? and password = ?', $params)[0];
                                if($teacherLogin != null)
                                {

                                    session()->put('teacherid',$teacherLogin->MSGV);
                                    session()->put('name',$teacherLogin->HoTenGV);
                                    session()->put('ChucVu',$teacherLogin->MaChucVu);
                                    //Thêm điều kiện lấy thông tin khoa để mở rộng quyền truy cập của GV/Quản lý của các khoa khác nhau
                                    if($teacherLogin->MaChucVu == 'AM' || $teacherLogin->MaChucVu == 'QL')
                                    {
                                        return redirect()->to('/admin');
                                    }
                                    elseif($teacherLogin->MaChucVu == 'GV')
                                    {
                                        return redirect()->to('/trang-chu');
                                    }
                                }
                            }catch(Exception $ex)
                            {
                                return redirect()->to('/')->with('error-Login','Tài khoản hoặc mật khẩu không hợp lệ')->withInput();
                            }

                        }

        }
    }

    public function logout(){
        session()->forget('studentid');
        session()->forget('teacherid');
        session()->forget('name');
        session()->forget('ChucVu');
        session()->forget('malop');
        session()->forget('mon-hoc');
        session()->forget('row16');

        //Tạo session ghi time khóa đăng nhập 7 ngày
        // //Set thời gian bắt đầu cho cd đến khi được đăng nhập lại là 7 ngày
        // $now = Carbon::now();
        // // $end_date = $now->copy()->addDays(7);
        // // $diff = $end_date->diffInDays($now);
        // session()->put('cdLoginRequest', $now);
        return redirect()->to('/');
    }




}
