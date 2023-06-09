<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SinhVienController extends Controller
{
    public function admin()
    {
        return view('admin/student-add');
    }

    public function adminClass()
    {
        return view('admin/class');
    }

    public function adminStudent()
    {
        return view('admin/student');
    }

    public function themSinhVien()
    {
        $sinhVien = DB::table('sinh_vien');

        $sinhVien -> MSSV = $request->input('MSSV');
        $sinhVien -> HoTenSV = $request->input('HoTenSV');
        $sinhVien -> TenLop = $request->input('TenLop');

        $sinhVien->save();

        return view('admin/student-add');
    }
}   
