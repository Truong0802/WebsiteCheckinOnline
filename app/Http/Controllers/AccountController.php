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
            elseif(session()->exists('studentid')){

                return redirect()->to('/trang-chu');

            }

        }
        else{
            return view('Account.login');
        }
    }


    public function login(Request $request){

        // dd($request->input());
        // $validate = $request->validate([
        //     'username' => 'required|max:10',
        //     'password' => 'required'
        // ]);
        $username = $request->username;
        $password = md5($request->password);
         $studentlogin = DB::table('sinh_vien')->where('MSSV',$username)->where('password',$password)->first();
         $teacherlogin = DB::table('giang_vien')->where('MSGV',$username)->where('password',$password)->first();

         if($studentlogin != null){
            session()->put('studentid',$studentlogin->MSSV);
            session()->put('name',$studentlogin->HoTenSV);
            session()->put('malop',$studentlogin->MaLop);
            return redirect()->to('/trang-chu');
         }
         else{
            if($teacherlogin !=null)
            {
                session()->put('teacherid',$teacherlogin->MSGV);
                session()->put('name',$teacherlogin->HoTenGV);
                session()->put('ChucVu',$teacherlogin->MaChucVu);
                return redirect()->to('/trang-chu');
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
        return redirect()->to('/');
    }




}
