<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
Use Exception;
use Illuminate\Support\Facades\Crypt;

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
                    session()->put('danh-sach-sinh-vien-lop',$request->lop);
                    $datatemp = [];
                    foreach($classlist as $checkData)
                    {
                        $datatemp = $checkData;
                    }
                    if($datatemp != null) //Nếu lớp có danh sách
                    {
                        return view('Teacher/student-list',['getinfoclass' => $classlist] );
                    }
                    else //Nếu lớp chưa có danh sách
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
            return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop'));
        }

        //

        public function DiemDanh(Request $request)
        {
            if(session()->get('teacherid'))
            {

                //Tạo mã QR
                //tạo session lưu thời gian sau khi parse từ Carbo để đối chiếu so sánh với quyền học sinh lúc bấm
                $encryptedData = $request->input('data');
                // dd($encryptedData);
                $data = decrypt($encryptedData);
                // dd($data["lop"]);
                $timestart = Carbon::now();

                $UpPathIntoDB = DB::table('checklog')->insert([
                    'MSGV' => session()->get('teacherid'),
                    'URL' => $encryptedData,
                    'TimeOpenLink' => $timestart
                ]);
            }
            elseif(session()->has('studentid'))
            {

                //test dữ liệu trả về
                    // dd($request->lop);
                    // dd($request->buoi);
                //Ràng buộc thời gian sử dụng để được insert không được vượt thời gian lúc bấm (session ở quyền giảng viên) là 3p
                //Thực hiện hàm insert vào db theo MaDanhSach dối chiếu truy xuất theo MSSV a.k.a session()->get('studentid)

                    $encryptedData = $request->data;
                    // dd($encryptedData);
                    $findPath = DB::table('checklog')->where('URL',$encryptedData)->orderByDesc('Id')->first();
                    if($findPath != null)
                    {
                        // dd($encryptedData);
                        try{
                            $data = decrypt($encryptedData);
                            $datafromdb = decrypt($findPath->URL);
                        }
                        catch(Exception $ex)
                        {
                            return back()->with('error2','Sai đường dẫn')->withInput();
                        }
                            $findlistidofstudent = DB::table('danh_sach_sinh_vien')
                            // ->where('MaTTMH',session()->get('danh-sach-sinh-vien-lop'))
                            ->where('MaTTMH',$data["lop"])
                            ->where('MSSV',session()->get('studentid'))->first();

                            if($findlistidofstudent != null)
                            {
                                $timecheckin = Carbon::now();
                                $diff = $timecheckin->diff(Carbon::parse($findPath->TimeOpenLink));
                                // dd($diff->i);
                                if( $diff->i <= 5){
                                    try
                                    {
                                        $checkrequest = DB::table('diem_danh')
                                        ->where('MaDanhSach',$findlistidofstudent->MaDanhSach)
                                        ->where('MaBuoi',$data["buoi"])->first();
                                        // dd($checkrequest);
                                        if($checkrequest != null)
                                        {
                                            if(session()->has('error2'))
                                            {
                                                session()->forget('error2');
                                            }
                                            else{
                                                return back()->with('error2','Không được điểm danh 2 lần!!!')->withInput();
                                            }

                                        }
                                        elseif($checkrequest == null)
                                        {
                                            if($data["buoi"] == $datafromdb["buoi"])
                                            {
                                                $studentchecked = DB::table('diem_danh')->insert([
                                                    'MaDanhSach' => $findlistidofstudent->MaDanhSach,
                                                    'MaBuoi' => $data["buoi"],
                                                    'NgayDiemDanh' => $timecheckin,
                                                ]);
                                            }
                                            else{
                                                    if(session()->has('error2'))
                                                {
                                                    session()->forget('error2');
                                                }
                                                else{
                                                    return back()->with('error2','Điểm danh thất bại')->withInput();
                                                }
                                            }
                                        }

                                        // dd($checkrequest);
                                    }
                                    catch(\Illuminate\Database\QueryException $exception)
                                    {
                                        // dd($exception->getMessage());
                                        return back()->with('error',$exception->getMessage())->withInput();
                                    }

                                    return redirect()->to('/trang-chu');




                                }
                                elseif( $diff->i > 5){
                                    if(session()->has('error2'))
                                    {
                                        session()->forget('error2');
                                    }
                                    else{
                                        return back()->with('error2','Điểm danh quá hạn')->withInput();
                                    }

                                }
                            }
                            else{
                                if(session()->has('error2'))
                                    {
                                        session()->forget('error2');
                                    }
                                    else{
                                        return back()->with('error2','Điểm danh thất bại')->withInput();
                                    }
                            }

                    }
                    else
                    {
                        if(session()->has('error2'))
                        {
                            session()->forget('error2');
                        }
                        else{
                            return back()->with('error2','Không tồn tại lớp học này!')->withInput();
                        }
                    }
            }
            else{
                return redirect()->to('/');
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
                    //Đối với môn thực hành
                    $phanloailop = substr($resultCheck->MaDanhSach,3,1);
                    if($phanloailop == '3')
                    {
                        $latestpoint = $countChecked * 3/6;
                        $latestpoint = round($latestpoint, 2);
                        // $latestpoint = round($latestpoint + 0.5, 0, PHP_ROUND_HALF_UP);
                        if($countChecked  >= 5)
                        {

                            $row14UpDate = DB::table('danh_sach_sinh_vien')
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['Diem14' => $latestpoint]);
                        }
                    }
                    else{
                            //Đối với môn lý thuyết
                        $latestpoint = $countChecked * 3/9;
                        // $input = 2.33;
                        // $latestpoint = round($input+0.20, 2, PHP_ROUND_HALF_UP);
                        // $latestpoint = (int)$latestpoint;
                        $latestpoint = round($latestpoint, 2);
                        // dd($latestpoint);
                        if($countChecked  >= 7)
                        {

                            $row14UpDate = DB::table('danh_sach_sinh_vien')
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['Diem14' => $latestpoint]);
                        }
                    }



                }
                $checkDQT = DB::table("ket_qua")->where('MaKQSV',$findlop->MaKQSV)->whereNotNull('DiemQT')->first();
                if($checkDQT != null)
                {
                    $result = $findlop->Diem14 + $findlop->Diem16;
                    // dd($result);
                    $DQT = DB::table('ket_qua')
                                ->where('MaKQSV',$findlop->MaKQSV)
                                ->update(['DiemQT' => $result]);
                }
                else{

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
                    //Đối với môn thực hành
                    $phanloailop = substr($resultCheck->MaDanhSach,3,1);
                    if($phanloailop == '3')
                    {
                        if($countChecked  >= 5)
                        {
                            $input = $countChecked; // Tổng số buổi đã điểm danh
                            $divide = $input%2;
                            if($divide == 0) // nếu số buổi đi là 2 hoặc 4 hoặc 6
                            {
                                $result = $input/2;

                            }
                            else{
                                $afterDivide = $input-$divide; //Tách buổi %2 ra khỏi phần thừa


                                if($afterDivide %2 == 0)
                                {
                                    $result = $afterDivide/2; //Điểm theo tiêu chuẩn 3 3 3

                                    if($divide %1 == 0) //Nếu thừa 1 buổi thì cộng 1 buổi đó chỉ bằng 0.5
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
                    else{ //Nếu là môn lý thuyết
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


                }
                $checkDQT = DB::table("ket_qua")->where('MaKQSV',$findlop->MaKQSV)->whereNotNull('DiemQT')->first();
                if($checkDQT != null)
                {
                    $result = $findlop->Diem14 + $findlop->Diem16;
                    // dd($result);
                    $DQT = DB::table('ket_qua')
                                ->where('MaKQSV',$findlop->MaKQSV)
                                ->update(['DiemQT' => $result]);
                }
                else{

                }
                return redirect('/danh-sach-sinh-vien?lop='.$findlop->MaTTMH);
            }
            else //Nếu không chọn gì trong các option tính điểm
            {
                return redirect()->back()->withInput();

            }


        }

        public function DiemCot16(Request $request)
        {
            // dd($request->row16);
            if($request->row16)
            {

                    $limit = count($request->row16);

                    $listid = [];
                    $listid =session()->get('row16');

                    $i =0;

                    // dd($request->row16);
                    foreach($listid as $key)
                    {
                        if($i < $limit)
                        {
                            if(is_numeric($request->row16[$i]) && $request->row16[$i] > 0 && $request->row16[$i] <= 7)
                            {
                                $findrow14 = DB::table('danh_sach_sinh_vien')->where('MaDanhSach',$key)->first();
                                //Mã kết quả của sinh viên trong db bảng điểm
                                $MaKQSV = $findrow14->MSSV.$findrow14->MaTTMH.$findrow14->MaHK;
                                //Điều kiện không cho phép tổng điểm quá 10
                                if($findrow14->Diem14 + round($request->row16[$i], 2) > 10)
                                {
                                    if(session()->has('error-row16'))
                                    {
                                        session()->forget('error-row16');
                                    }
                                    return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop'))->with('error-row16','Điểm tổng không vượt quá 10')->withInput();
                                }
                                if($findrow14->Diem16 == null)
                                {
                                    session()->put('timeForChange', Carbon::now()->addWeeks(2)); //Nếu chưa nhập điểm thì set thời gian cho phép sửa điểm là 2 tuần kế từ lúc nhập điểm

                                }
                            // else
                            // {
                                // if(session()->has('timeForChange'))
                                // {
                                    // session()->forget('timeForChange'); //Nếu đã có điểm tồn tại mà sửa điểm thì không cho sửa nữa
                                    //}
                            // }
                                // dd($MaKQSV);
                                    $row16UpDate = DB::table('danh_sach_sinh_vien')
                                    ->where('MaDanhSach',$findrow14->MaDanhSach)
                                    ->update(['Diem16' => round($request->row16[$i], 2)]);



                            //Tính ra điểm Qúa trình
                                $result = $findrow14->Diem14 + round($request->row16[$i], 2);
                                $DQT = DB::table('ket_qua')
                                ->where('MaKQSV',$MaKQSV)
                                ->update(['DiemQT' => $result]);
                                $i++;
                            }
                            else{
                                if(session()->has('error-row16'))
                                {
                                    session()->forget('error-row16');
                                }
                                return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop'))->with('error-row16','Hãy nhập số chính xác!')->withInput();
                            }
                        }



                    }
                    session()->forget('row16');// Sau khi xử lý xóa session
                    return redirect('/danh-sach-sinh-vien?lop='.$findrow14->MaTTMH);


            }
            else
            {
                return redirect()->back();
            }

        }
}
