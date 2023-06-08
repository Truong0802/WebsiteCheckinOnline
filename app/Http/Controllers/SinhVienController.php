<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
Use Exception;
use Illuminate\Support\Str;
class SinhVienController extends Controller
{
    public function admin()
    {
        return view('admin/student-add');
    }

    public function frmAddSV()
    {
        return view('admin/student-add');
    }

    public function themSinhVien(Request $request)
    {
        // $sinhVien = DB::table('sinh_vien');

        // $sinhVien -> MSSV = $request->input('MSSV');
        // $sinhVien -> HoTenSV = $request->input('HoTenSV');
        // $sinhVien -> TenLop = $request->input('TenLop');

        // $sinhVien->save(); //Update database
        if($request != null)
        {
            if(session()->has('teacherid') && session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
            {
                try
                {
                    session()->put('MSSV',$request->mssv);
                    session()->put('Name',$request->studentname);
                    session()->put('ClassName',$request->classname);
                    session()->put('PassWord',$request->password);
                    // session()->put('Phuong',$request->phuong);
                    // session()->put('Quan',$request->quan);
                    // session()->put('TP',$request->TP);
                    // session()->put('DiaChi',$request->DiaChi);

                }
                catch(Exception $ex)
                {
                    return back()->with('error-Add','lỗi nhập liệu!')->withInput();
                }
            }
            // $temp[] = session()->get('MSSV').'  '.session()->get('Name').' '.session()->get('ClassName').' '.session()->get('PassWord').' '.session()->get('Phuong').' '.session()->get('Quan').' '.session()->get('TP').' '.session()->get('DiaChi');
            $temp= session()->get('MSSV').session()->get('Name').'MK'.session()->get('PassWord').session()->get('ClassName');
            session()->push('DanhSachTam',$temp); //Thêm vào danh sách tạm
            // dd(session()->get('DanhSachTam')); //Cắt chuỗi
            // $MSSVCut = substr($temp,0,10);
            // $CutClass = substr($temp,-7);
            // $HoTen = Str::between($temp,$MSSVCut,'MK');
            // $Password = Str::between($temp,'MK',$CutClass);
            // dd($Password);
            // dd($MSSVCut);
            // dd($temp);
            // dd($CutClass);
            return view('admin/student-add');
        }
        else{
            return view('admin/student-add')->with('error-Add','lỗi nhập liệu!');
        }

    }

    public function confirmAddStudent()
    {
        //Xử lý insert

        foreach(session()->get('DanhSachTam') as $temp)
        {
             $MSSVCut = substr($temp,0,10);
             $CutClass = substr($temp,-7);
             $HoTen = Str::between($temp,$MSSVCut,'MK');
             $Password = Str::between($temp,'MK',$CutClass);
            try
            {
                $InsertDSDiaChi = DB::table('dia_chi')->insert([
                    'MaDiaChi' => $MSSVCut.$CutClass
                ]);
                $InsertSV = DB::table('sinh_vien')->insert([
                    'MSSV' => $MSSVCut,
                    'HoTenSV' => $HoTen,
                    'Password' => md5($Password),
                    'MaLop' => $CutClass,
                    'BanCanSu' => 0,
                    'MaDiaChi' => $MSSVCut.$CutClass
                ]);
            } catch(Exception $ex)
            {
                // dd($ex);
                return redirect()->to('/dang-ky-sinh-vien')->with('error-Add','đã tồn tại sinh viên'.' '.substr($temp,0,10).'')->withInput();

            }

        }

        //Hủy bỏ session sau khi add vào db
        session()->forget('MSSV');
        session()->forget('Name');
        session()->forget('ClassName');
        session()->forget('PassWord');
        // session()->forget('Phuong');
        // session()->forget('Quan');
        // session()->forget('TP');
        // session()->forget('DiaChi');
        session()->forget('DanhSachTam');
        return redirect()->to('/dang-ky-sinh-vien')->with('success-Add','Thêm thành công');
    }

    public function DeletSinhVien(Request $request)
    {
        $array = session('DanhSachTam');
        $position = array_search($request->id, $array);
        // dd($position);
        unset($array[$position]);
        session(['DanhSachTam' => $array]);
        return redirect()->to('/dang-ky-sinh-vien');
    }
}
