<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

class TeacherController extends Controller
{
    //
        //Của khoa Quản lý
        public function danhsachlop(Request $request)
        {
            if(session()->exists('teacherid')){
                if($request->lop){
                    $teacherid = session()->get('teacherid');
                    $allsubject = DB::table('lich_giang_day')
                    ->where('MSGV',$teacherid)
                    ->where('MaTTMH',$request->lop)
                    ->distinct()->paginate(5);
                }
                else{
                    $teacherid = session()->get('teacherid');
                    $allsubject = DB::table('lich_giang_day')->paginate(5);

                }


                return view('Teacher/class-list',['getallclass'=>$allsubject]);
            }
            else{
                return redirect()->to('/');
            }
        }

        public function timkiem(Request $request)
        {
            if(session()->exists('teacherid') && session()->get('ChucVu') != 'QL'){
                $teacherid = DB::table('giang_vien')->where('HoTenGV', $request->lecturename)->first();
                $subjectname = DB::table('mon_hoc')->where('TenMH', $request->subjectname)->first();
                $coursename = DB::table('khoa_hoc')->where('KhoaHoc', $request->coursename)->first();
                $courselist = DB::table('khoa_hoc')->where('KhoaHoc', $request->courselist)->first();

                    $allsubject = DB::table('lich_giang_day')->where('MSGV',session()->get('teacherid'))->where('MaNgay','like','1%')
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

                return view('Teacher/class-list', ['getallsubject' => $allsubject]);
            }
            elseif(session()->exists('teacherid') && session()->get('ChucVu') != 'GV')
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

                return view('Teacher/class-list', ['getallsubject' => $allsubject]);
            }
            else{
                return redirect()->to('/');
            }
        }

        public function removetimkiem()
        {
            return redirect()->to('/trang-chu');
        }
//
//Danh sách sinh viên

        public function danhsachsinhvien(Request $request)
        {
            if(session()->exists('teacherid')){
                if($request->lop){
                    $classlist = DB::table('danh_sach_sinh_vien')->where('MaTTMH',$request->lop)->distinct()->paginate(20);

                    $datatemp = [];
                    foreach($classlist as $checkData)
                    {
                        $datatemp = $checkData;
                    }
                    if($datatemp != null)
                    {
                        return view('Teacher/student-list',['getinfoclass' => $classlist] );
                    }
                    else
                    {
                        return redirect()->to('/trang-chu');
                    }

                }


            }
            else{
                return redirect()->to('/');
            }

        }
        public function timkiemsinhvien(Request $request)
        {
            if (session()->has('teacherid')) {
                $searchlist = DB::table('danh_sach_sinh_vien')
                ->when($request->studentname, function ($query) use ($request) {
                    return $query->join('sinh_vien', 'sinh_vien.MSSV', 'danh_sach_sinh_vien.MSSV')
                        ->where('sinh_vien.HoTenSV', 'like', '%' . $request->studentname . '%');
                })
                ->when($request->mssv, function ($query) use ($request) {
                    return $query->where('danh_sach_sinh_vien.MSSV', $request->mssv);
                })
                ->paginate(10);

                return view('Teacher/student-list', ['getinfoclass' => $searchlist]);
            }
        }

        public function removetimkiemsv()
        {
            return redirect()->back();
        }

        //

        public function DiemDanh(Request $request)
        {
            if(session()->get('teacherid'))
            {
                //test dữ liệu trả về
                    // dd($request->lop);
                    // dd($request->buoi);

                //Tạo mã QR
                //tạo session lưu thời gian sau khi parse từ Carbo để đối chiếu so sánh với quyền học sinh lúc bấm
                $timestart = Carbon::now();
                session()->put('countdown',$timestart);
            }
            elseif(session()->has('studentid'))
            {

                //test dữ liệu trả về
                    // dd($request->lop);
                    // dd($request->buoi);
                //Ràng buộc thời gian sử dụng để được insert không được vượt thời gian lúc bấm (session ở quyền giảng viên) là 3p
                //Thực hiện hàm insert vào db theo MaDanhSach dối chiếu truy xuất theo MSSV a.k.a session()->get('studentid)
                if(session()->get('countdown'))
                {
                    $findlistidofstudent = DB::table('danh_sach_sinh_vien')->where('MSSV',session()->get('studentid'))->first();
                    $timecheckin = Carbon::now();
                    $diff = $timecheckin->diff(session()->get('countdown'));
                    // dd($diff->i);
                    if( $diff->i <= 3){
                        $studentchecked = DB::table('diem_danh')->insert([
                            'MaDanhSach' => $findlistidofstudent->MaDanhSach,
                            'MaBuoi' => $request->buoi,
                            'NgayDiemDanh' => $timecheckin,
                        ]);
                    }

                    return redirect()->to('/trang-chu');
                }
                else{
                    return redirect()->to('/trang-chu');;
                }

            }
        }

        public function ChiaDiem(Request $request){
            if($request->input('divideall'))
            {
                $option1 = $request->input('divideall');
                $findlop = DB::table('danh_sach_sinh_vien')->where('MaDanhSach','like', $option1.'%')->distinct()->first();

                $listUpToRow14 = DB::table('danh_sach_sinh_vien')->where('MaDanhSach','like', $option1.'%')->distinct()->get();
                // dd($listUpToRow14);
                foreach($listUpToRow14 as $resultCheck)
                {

                    $countChecked = DB::table('diem_danh')->where('MaDanhSach',$resultCheck->MaDanhSach)->distinct()->count('MaBuoi');

                    $latestpoint = $countChecked * 3/9;
                    $latestpoint = round($latestpoint, 2);

                    if($countChecked  >= 7)
                    {

                        $row14UpDate = DB::table('danh_sach_sinh_vien')
                        ->where('MSSV',$resultCheck->MSSV)
                        ->update(['Diem14' => $latestpoint]);
                    }
                }
                return redirect('/danh-sach-sinh-vien?lop='.$findlop->MaTTMH);
            }
            elseif($request->input('divide3'))
            {
                $option2 = $request->input('divide3');
                $findlop = DB::table('danh_sach_sinh_vien')->where('MaDanhSach','like', $option2.'%')->distinct()->first();
                $listUpToRow14 = DB::table('danh_sach_sinh_vien')->where('MaDanhSach','like', $option2.'%')->distinct()->get();
                // dd($listUpToRow14);
                foreach($listUpToRow14 as $resultCheck)
                {

                    $countChecked = DB::table('diem_danh')->where('MaDanhSach',$resultCheck->MaDanhSach)->distinct()->count('MaBuoi');



                    if($countChecked  >= 7)
                    {
                        $input = $countChecked; // Tổng số buổi đã điểm danh
                        $divide = $input%3;
                        if($divide == 0) // nếu số buổi đi là 3 hoặc 6 hoặc 9
                        {
                            $result = $input/3;

                        }
                        else{
                            $afterDivide = $input-$divide; //Tách buổi %3 ra khỏi phần thừa


                            if($afterDivide %3 == 0)
                            {
                                $result = $afterDivide/3; //Điểm theo tiêu chuẩn 3 3 3

                                if($divide %2 == 0) //Nếu thừa 2 buổi thì cộng 2 buổi đó chỉ bằng 0.5
                                {
                                    $temp=0.5;
                                }
                                else{ //Nếu chỉ thừa ra 1 buổi thì điểm cộng thêm == 0
                                    $temp = 0;
                                }
                                $result = $result + $temp;

                            }


                        }

                        $row14UpDate = DB::table('danh_sach_sinh_vien')
                        ->where('MSSV',$resultCheck->MSSV)
                        ->update(['Diem14' => $result]);
                    }
                }
                return redirect('/danh-sach-sinh-vien?lop='.$findlop->MaTTMH);
            }


        }
}
