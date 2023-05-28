<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class TeacherController extends Controller
{
    //
        //Của khoa Quản lý
        public function danhsachlop(Request $request)
        {
            if($request->lop){
                $teacherid = session()->get('teacherid');
                $allsubject = DB::table('lich_giang_day')->where('MSGV',$teacherid)->where('MaTTMH',$request->lop)->distinct()->paginate(5);
            }
            else{
                $teacherid = session()->get('teacherid');
                $allsubject = DB::table('lich_giang_day')->paginate(5);

            }


            return view('Teacher/class-list',['getallclass'=>$allsubject]);
        }

        public function timkiem(Request $request)
        {

            $teacherid = DB::table('giang_vien')->where('HoTenGV', $request->lecturename)->first();
            $subjectname = DB::table('mon_hoc')->where('TenMH', $request->subjectname)->first();
            $coursename = DB::table('khoa_hoc')->where('KhoaHoc', $request->coursename)->first();
            $courselist = DB::table('khoa_hoc')->where('KhoaHoc', $request->courselist)->first();

                $allsubject = DB::table('lich_giang_day')
                ->when($teacherid, function ($query) use ($teacherid) {
                    return $query->where('MSGV', $teacherid->MSGV)->distinct();
                })
                ->when($subjectname, function ($query) use ($subjectname) {
                    return $query->where('MaTTMH', $subjectname->MaTTMH)->distinct();
                })
                ->when($coursename, function ($query) use ($coursename) {
                    $class = DB::table('lop')->where('KhoaHoc', $coursename->KhoaHoc)->first();
                    return $query->where('MaLop', $class->MaLop)->distinct();
                })
                ->when($courselist, function ($query) use ($courselist) {
                    $findclass = DB::table('lop')->where('KhoaHoc', $courselist->KhoaHoc)->first();
                    return $query->where('MaLop', $findclass->MaLop);
                })
                ->when(!$teacherid && !$subjectname && !$coursename && !$courselist, function ($query) use ($request) {
                    return $query->where('MaLop', $request->classname)->distinct();
                })
                ->paginate(5);

            return view('Teacher/class-list', ['getallclass' => $allsubject]);
        }

        public function removetimkiem()
        {
            return redirect()->to('/danh-sach-lop');
        }

        public function danhsachsinhvien(Request $request)
        {
            if($request->lop){
                $classlist = DB::table('danh_sach_sinh_vien')->where('MaTTMH',$request->lop)->distinct()->get();
                return view('Teacher/student-list',['getinfoclass' => $classlist] );
            }


        }

}
