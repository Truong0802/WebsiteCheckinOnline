<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;


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
        $username = $request->username;
        $password = md5($request->password);
        $studentLogin = DB::table('sinh_vien')->where('MSSV',$username)->where('password',$password)->first();
        $teacherLogin = DB::table('giang_vien')->where('MSGV',$username)->where('password',$password)->first();

        if($studentLogin != null){
            session()->put('studentid',$studentLogin->MSSV);
            session()->put('name',$studentLogin->HoTenSV);
            session()->put('malop',$studentLogin->MaLop);
            return redirect()->to('/trang-chu');
        }
        else
        {
            if($teacherLogin !=null)
            {
                session()->put('teacherid',$teacherLogin->MSGV);
                session()->put('name',$teacherLogin->HoTenGV);
                session()->put('ChucVu',$teacherLogin->MaChucVu);
                if($teacherLogin->MaChucVu == 'AM')
                {
                    return redirect()->to('/admin');
                }
                elseif($teacherLogin->MaChucVu == 'GV')
                {
                    return redirect()->to('/trang-chu');
                }
            }
            return redirect()->back();
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
        return redirect()->to('/');
    }




}
