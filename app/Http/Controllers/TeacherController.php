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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
// use Jenssegers\Agent\Facades\Agent;
// use Stevebauman\Location\Facades\Location;
use App\Http\Controllers\HomeController;
class TeacherController extends Controller
{
    //Hồ sơ giảng dạy
        // public function hosogiangday()
        // {
        //     return redirect()->action([
        //         HomeController::class,
        //         'trangchusv'
        //     ]);
        // }

        //Của khoa Quản lý
        public function danhsachlop(Request $request)
        {
            if(session()->exists('teacherid')){
                if($request->lop){
                    $teacherid = session()->get('teacherid');
                    $allsubject = DB::table('lich_giang_day')
                    ->where('MSGV',$teacherid)
                    ->where('MaTTMH',$request->lop)
                    ->latest('NgayDay')
                    ->distinct()->paginate(5);
                }
                else{
                    $teacherid = session()->get('teacherid');
                    $allsubject = DB::table('lich_giang_day')->where('MaBuoi',1)->latest('NgayDay')->paginate(5);
                    //Thêm điều kiện else cho trường hợp quản lý truy cập sẽ lọc ra những lớp thuộc khoa của quản lý đó
                }


                return view('Teacher/class-list',['getallsubject'=>$allsubject]);
            }
            else{
                return redirect()->to('/');
            }
        }

        public function timkiem(Request $request)
        {
            if(session()->exists('teacherid') && session()->get('ChucVu') == 'GV'){
                $teacherid = DB::table('giang_vien')->where('HoTenGV', $request->lecturename)->first();
                $subjectname = DB::table('mon_hoc')->where('TenMH', $request->subjectname)->first();
                $coursename = DB::table('khoa_hoc')->where('KhoaHoc', $request->coursename)->first();
                $courselist = DB::table('khoa_hoc')->where('KhoaHoc', $request->courselist)->first();

                    $allsubject = DB::table('lich_giang_day')->where('MSGV',session()->get('teacherid'))->where('MaBuoi',1)
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

                    $checkTemp = [];
                    foreach($allsubject as $Try)
                    {
                        $checkTemp= $allsubject;
                    }
                    if($checkTemp == null)
                    {
                        return redirect()->to('/trang-chu')->with('errorClass1','Tìm kiếm rỗng!')->withInput();
                    }
                    else
                    {
                        return view('Teacher/class-list', ['getallsubject' => $allsubject]);
                    }

            }
            elseif(session()->exists('teacherid') && session()->get('ChucVu') != 'GV')
            {
                $teacherid = DB::table('giang_vien')->where('HoTenGV', $request->lecturename)->first();
                $subjectname = DB::table('mon_hoc')->where('TenMH', $request->subjectname)->first();
                $coursename = DB::table('khoa_hoc')->where('KhoaHoc', $request->coursename)->first();
                $courselist = DB::table('khoa_hoc')->where('KhoaHoc', $request->courselist)->first();

                    $allsubject = DB::table('lich_giang_day')->where('MaBuoi',1)
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

                    $checkTemp = [];
                    foreach($allsubject as $Try)
                    {
                        $checkTemp= $allsubject;
                    }
                    if($checkTemp == null)
                    {
                        return redirect()->to('/danh-sach-lop')->with('errorClass1','Tìm kiếm rỗng!')->withInput();
                    }
                    else
                    {
                        return view('Teacher/class-list', ['getallsubject' => $allsubject]);
                    }
            }
            else{
                return redirect()->to('/');
            }
        }

        public function removetimkiem()
        {
            if(session()->get('ChucVu') != 'GV')
            {
                return redirect()->to('/danh-sach-lop');
            }
            else
            {
                return redirect()->to('/trang-chu');
            }

        }
//
//Danh sách sinh viên

        public function danhsachsinhvien(Request $request)
        {
            if(session()->exists('teacherid')){
                // dd($request);
                if($request->lop){
                    $classlist = DB::table('danh_sach_sinh_vien')->where('MaTTMH',$request->lop)->where('MaHK',$request->HK)->distinct()->paginate(25);
                    session()->put('danh-sach-sinh-vien-lop',$request->lop);
                    session()->put('HKid',$request->HK);
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
                        return redirect()->to('/trang-chu')->with('errorClass1','Lớp chưa có danh sách!')->withInput();
                        // return redirect()->to('/trang-chu');
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
                        ->where('danh_sach_sinh_vien.MaTTMH',session('danh-sach-sinh-vien-lop'))
                        ->where('sinh_vien.HoTenSV', 'like', '%' . $request->studentname . '%');
                })
                ->when($request->mssv, function ($query) use ($request) {
                    return $query->where('danh_sach_sinh_vien.MaTTMH',session('danh-sach-sinh-vien-lop'))
                    ->where('danh_sach_sinh_vien.MSSV', $request->mssv);
                })
                ->paginate(10);
                //Kiểm tra tìm kiếm có rỗng không
                $checkTemp = [];
                foreach( $searchlist as $ResultData)
                {
                    $checkTemp = $ResultData;
                }
                if($checkTemp == null)
                {
                    return redirect()->to('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))
                                        ->with('errorClassList1','Lớp không tồn tại đối tượng '.$request->studentname.' với mã số '.$request->mssv)->withInput();
                }
                return view('Teacher/student-list', ['getinfoclass' => $searchlist]);
            }
            else{
                return redirect()->to('/');
            }

        }

        public function removetimkiemsv()
        {
            return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'));
        }

        public function trovedanhsach()
        {
            return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'));
        }
        //
        public function qrcodeGenerate()
        {
            if(session()->has('teacherid'))
            {
                $encryptedData = DB::table('checklog')->where('MSGV',session()->get('teacherid'))->orderByDesc('Id')->first();
                //Tạo mã QR
                $qrCodes = [];
                $url = '/diem-danh?data='.$encryptedData->URL;
                $urlconvert = url($url);
                // dd($urlconvert);
                $qrCodes['simple'] = QrCode::size(400)->generate($urlconvert);
                // $qrCodes['changeColor'] = QrCode::size(120)->color(255, 0, 0)->generate($url);
                // $qrCodes['changeBgColor'] = QrCode::size(120)->backgroundColor(255, 0, 0)->generate($url);

                // $qrCodes['styleDot'] = QrCode::size(120)->style('dot')->generate($url);
                // $qrCodes['styleSquare'] = QrCode::size(120)->style('square')->generate($url);
                // $qrCodes['styleRound'] = QrCode::size(120)->style('round')->generate($url);
                // $qrCodes['withImage'] = QrCode::size(200)->generate($urlconvert);
                return view('Teacher/empty-site-for-qr',$qrCodes);
            }
            else{
                return redirect()->to('/');
            }

        }

        public function DiemDanh(Request $request)
        {
            if(session()->get('teacherid'))
            {
                //tạo session lưu thời gian sau khi parse từ Carbo để đối chiếu so sánh với quyền học sinh lúc bấm
                $encryptedData = $request->input('data');
                // dd($encryptedData);
                // $data = decrypt($encryptedData);
                // dd($data["lop"]);
                $timestart = Carbon::now();

                $UpPathIntoDB = DB::table('checklog')->insert([
                    'MSGV' => session()->get('teacherid'),
                    'URL' => $encryptedData,
                    'TimeOpenLink' => $timestart
                ]);
                return redirect()->to('/form-diem-danh');
            }
            elseif(session()->has('studentid'))
            {

                //test dữ liệu trả về
                    // dd($request->lop);
                    // dd($request->buoi);

                //Ràng buộc thời gian sử dụng để được insert không được vượt thời gian lúc bấm (session ở quyền giảng viên) là 3p
                //Thực hiện hàm insert vào db theo MaDanhSach dối chiếu truy xuất theo MSSV a.k.a session()->get('studentid)



                    //lấy dữ liệu sau ?data=
                    $encryptedData = $request->data;
                    // dd($encryptedData);
                    //Tìm đường dẫn điểm danh thuộc buổi học...
                    $findPath = DB::table('checklog')->where('URL',$encryptedData)->orderByDesc('Id')->first();
                    if($findPath != null)
                    {
                        // dd($encryptedData);
                        //Giải mã dữ liệu đường dẫn
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

                                if( $diff->i <= 5){
                                    try
                                    {
                                        //Điểm danh
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


                                    }
                                    catch(\Illuminate\Database\QueryException $exception)
                                    {

                                        return back()->with('error',$exception->getMessage())->withInput();
                                    }

                                    return redirect()->to('/trang-chu')->with('success1','Điểm danh thành công')->withInput();




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
                    $TongSoBuoiUpDate = DB::table('danh_sach_sinh_vien')
                            ->where('MaDanhSach',$findlop->MaDanhSach)
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['TongSoBuoi' => $countChecked]);
                    //Đối với môn thực hành
                    $phanloailop = substr($resultCheck->MaDanhSach,3,1);
                    if($phanloailop == '3')
                    {
                        $latestpoint = $countChecked * 3/6;
                        $latestpoint = round($latestpoint, 2);
                        $latestpoint = ceil($latestpoint * 10) / 10; //Làm tròn lên số gần nhất
                        // $latestpoint = round($latestpoint + 0.5, 0, PHP_ROUND_HALF_UP);
                        if($countChecked  >= 5)
                        {
                            $row14UpDate = DB::table('danh_sach_sinh_vien')
                            ->where('MaDanhSach',$findlop->MaDanhSach)
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['Diem14' => $latestpoint]);

                            $checkDQT = DB::table("ket_qua")->where('MaKQSV',$findlop->MaKQSV)->first();
                            if($checkDQT)
                            {
                                if($findlop->Diem16 != null)
                                {
                                    $result = $findlop->Diem14 + $findlop->Diem16;
                                    // dd($result);
                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$findlop->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }
                                else{
                                    $result = $findlop->Diem14;
                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$findlop->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }

                            }

                        }
                    }
                    else{
                            //Đối với môn lý thuyết
                        $latestpoint = $countChecked * 3/9;
                        $latestpoint = round($latestpoint, 2);
                        $latestpoint = ceil($latestpoint * 10) / 10; //Làm tròn lên số gần nhất
                        if($countChecked  >= 7)
                        {

                            $row14UpDate = DB::table('danh_sach_sinh_vien')
                            ->where('MaDanhSach',$findlop->MaDanhSach)
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['Diem14' => $latestpoint]);


                            $checkDQT = DB::table("ket_qua")->where('MaKQSV',$findlop->MaKQSV)->first();
                            if($checkDQT)
                            {
                                if($findlop->Diem16 != null)
                                {
                                    $result = $findlop->Diem14 + $findlop->Diem16;
                                    // dd($result);
                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$findlop->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }
                                else{
                                    $result = $findlop->Diem14;
                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$findlop->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }

                            }
                        }
                    }



                }
                // $checkDQT = DB::table("ket_qua")->where('MaKQSV',$findlop->MaKQSV)->whereNotNull('DiemQT')->first();
                // if($checkDQT != null)
                // {
                //     $result = $findlop->Diem14 + $findlop->Diem16;
                //     // dd($result);
                //     $DQT = DB::table('ket_qua')
                //                 ->where('MaKQSV',$findlop->MaKQSV)
                //                 ->update(['DiemQT' => $result]);
                // }
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
                            ->where('MaDanhSach',$findlop->MaDanhSach)
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['Diem14' => $result]);

                            $checkDQT = DB::table("ket_qua")->where('MaKQSV',$findlop->MaKQSV)->first();
                            if($checkDQT)
                            {
                                if($findlop->Diem16 != null)
                                {
                                    $result = $findlop->Diem14 + $findlop->Diem16;
                                    // dd($result);
                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$findlop->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }
                                else{
                                    $result = $findlop->Diem14;
                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$findlop->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }

                            }
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
                            ->where('MaDanhSach',$findlop->MaDanhSach)
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['Diem14' => $result]);

                            $checkDQT = DB::table("ket_qua")->where('MaKQSV',$findlop->MaKQSV)->first();
                            if($checkDQT)
                            {
                                if($findlop->Diem16 != null)
                                {
                                    $result = $findlop->Diem14 + $findlop->Diem16;
                                    dd($result);
                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$findlop->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }
                                else{
                                    $result = $findlop->Diem14;
                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$findlop->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }

                            }
                        }
                    }


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
            //  dd(session()->get('row16'));
            if($request->row16)
            {

                    $limit = count($request->row16);

                    $listid = [];
                    $listid =session()->get('row16');

                    $i =0;
                    foreach($listid as $key)
                    {
                        if($i < $limit)
                        {
                            if(str_contains($request->row16[$i],",") == true)
                            {
                                $resultConvert = str::replace(",",".", $request->row16[$i]);
                            }
                            else
                            {
                                $resultConvert = $request->row16[$i];
                            }

                            if(is_numeric($resultConvert) == false )
                            {
                                if(preg_match('/^ [0-9]*$/',$resultConvert) == 1 && preg_match('/^[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]*$/',$resultConvert) == 0) //Kiểm tra có tồn tại số bên trong hay không
                                {
                                    if(str_contains($request->row16[$i],",") == true || str_contains($request->row16[$i],".") == true) //Nếu ký tự nhập vào là số thập phân
                                    {
                                        try{
                                            $resultConvert = number_format($resultConvert,2,'.',''); //sinh ra thêm số để chuẩn number sau khi thêm . hoặc , vd: 5.5 -> 5.50
                                        }catch(ModelNotFoundException $exception)
                                        {
                                            return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop'))->with('error-row16','Hãy nhập số chính xác!')->withInput();
                                        }
                                    }
                                    else //nếu nhập kí tự chữ cái
                                    {
                                        return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop'))->with('error-row16','Hãy nhập số chính xác!')->withInput();
                                    }
                                }
                                else //nếu nhập kí tự chữ cái
                                {
                                    return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop'))->with('error-row16','Hãy nhập số chính xác!')->withInput();
                                }


                            }

                            // dd($resultConvert);
                            if(is_numeric($resultConvert) == true && $resultConvert > 0 && $resultConvert <= 7)
                            {
                                $ThongTinMaDanhSach = str::before($key, '/');
                                $GetInfoStudent = str::after($key,'/');
                                $findrow14 = DB::table('danh_sach_sinh_vien')->where('MaDanhSach',$ThongTinMaDanhSach)->first();

                                //Mã kết quả của sinh viên trong db bảng điểm
                                $MaKQSV = $findrow14->MSSV.$findrow14->MaTTMH.$findrow14->MaHK;
                                //Điều kiện không cho phép tổng điểm quá 10

                                if($findrow14->Diem14 + round($resultConvert, 2) > 10)
                                {
                                    if(session()->has('error-row16'))
                                    {
                                        session()->forget('error-row16');
                                    }
                                    return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop'))->with('error-row16','Điểm tổng không vượt quá 10')->withInput();
                                }

                                //Điều kiện kiểm tra thời gian thỏa để sửa điểm hay không
                                if(session()->has('timeForChange') && Carbon::now()->greaterThan(Carbon::parse( session()->get('timeForChange'))))
                                {
                                    session()->forget('timeForChange');
                                    $setNullTimeForChange = DB::table('danh_sach_sinh_vien')
                                    ->where('MaDanhSach',$findrow14->MaDanhSach)
                                    ->where('MSSV',$GetInfoStudent)
                                    ->update([
                                        'TimeForChangeRow16'=> null
                                    ]);
                                    return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop'))->with('error-row16','Quá thời gian sửa điểm')->withInput();
                                }
                                else
                                {
                                    if($findrow14->Diem16 == null)
                                    {
                                        session()->put('timeForChange',Carbon::now()->addWeeks(2)); //Nếu chưa nhập điểm thì set thời gian cho phép sửa điểm là 2 tuần kế từ lúc nhập điểm
                                        $setNullTimeForChange = DB::table('danh_sach_sinh_vien')
                                                ->where('MaDanhSach',$findrow14->MaDanhSach)
                                                ->where('MSSV',$GetInfoStudent)
                                                ->update([
                                                    'TimeForChangeRow16'=> session()->get('timeForChange')
                                                ]);
                                    }
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
                                    ->where('MSSV',$GetInfoStudent)
                                    ->update(['Diem16' => round($resultConvert, 2)]);



                            //Tính ra điểm Qúa trình
                                $result = $findrow14->Diem14 + round($resultConvert, 2);
                                $DQT = DB::table('ket_qua')
                                ->where('MaKQSV',$MaKQSV)
                                ->update(['DiemQT' => $result]);

                                $array = session('row16');
                                $position = array_search($key, $array);
                                unset($array[$position]);
                                session(['row16' => $array]);
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
                    // return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop'));

            }
            else
            {
                return redirect()->back();
            }


        }
//Thêm danh sách
        public function frmAddStudentList(Request $request)
        {
            session()->put('classAddId',$request->lop);
            session()->put('HKid',$request->HK);

            return view('admin/student');
        }

        public function ThemDanhSachSV(Request $request)
        {
            if(session()->has('teacherid')){
                $validated = $request->validate([
                    'mssv' => 'required',
                    'studentname' => 'required',
                    'classname' => 'required'
                ]);
                if($request !=null)
                {
                    $MaTTMH = $request->classid;
                    $checkfindnameTeacher = DB::table('lich_giang_day')->where('MaTTMH',$MaTTMH)->orderBy('MaNgay','DESC')->first();
                    $cutHK= substr(session()->get('HKid'), 0, 2);
                    $CutYearOfClass = Str::after(session()->get('HKid'),$cutHK);
                    $temp = $request->classid.'HocKy'.$cutHK.'NamHoc'.$CutYearOfClass.'MSGV'.$checkfindnameTeacher->MSGV.'MSSV'.$request->mssv.'MaTTMH'.$MaTTMH.'HoTenSV'.$request->studentname;
                    session()->push('DanhSachSinhVienTam',$temp);
                    return redirect()->to('/Them-danh-sach-sv?lop='.session()->get('classAddId').'&HK='.session()->get('HKid'));
                }
                else
                {
                    return redirect()->to('/Them-danh-sach-sv?lop='.session()->get('classAddId').'&HK='.session()->get('HKid'))->with('error-AddDSSV','Lỗi nhập liệu')->withInput();
                }
            }
            else{
                return redirect()->to('/');
            }

        }
        public function XoaKhoiDanhSach(Request $request)
        {
            $array = session('DanhSachSinhVienTam');
            $position = array_search($request->id, $array);
            unset($array[$position]);
            session(['DanhSachSinhVienTam' => $array]);
            return redirect()->to('/Them-danh-sach-sv?lop='.session()->get('classAddId').'&HK='.session()->get('HKid'));
        }

        public function XacNhanThemSV(Request $request)
        {
            // dd(session('DanhSachSinhVienTam'));
            //XỬ lý cắt chuỗi lấy Mã Học kì tạo MaHK - Nếu có học kì trong db thì sử dụng, còn không thì tạo mới
            //Xử lý cắt chuỗi lấy MSSV để tạo mã danh sách
            //Xử lý cắt chuỗi ghép tạo MaKQSV = MSSV + MaTTMH + MaHK
            if(session('DanhSachSinhVienTam') != null)
            {
                //Xử lý sắp xếp trước khi add vào danh sách db
                $arrayTemp = [];
                $arrayTemp = session()->get('DanhSachSinhVienTam');
                $collection = collect($arrayTemp);

                // Sắp xếp theo giá trị ASCII của phần tử sau dấu "|"
                $sortedCollection = $collection->sortBy(function ($item) {
                    // Tách chuỗi thành mảng sử dụng dấu "|", và lấy phần tử thứ 1 (sau dấu "|") để sắp xếp
                    $parts = explode('HoTenSV', $item);
                    return $parts[1];
                })->values();

                // Chuyển Collection đã sắp xếp trở lại thành mảng
                $sortedArray = $sortedCollection->all();

                // dd($sortedArray);

                foreach(session('DanhSachSinhVienTam') as $key)
                {

                    //Lấy Mã SV
                    $Mssv = Str::between($key,'MSSV','MaTTMH');
                    //Lấy Mã môn
                    $CutClass = Str::before($key,'HocKy');
                    //Lấy MSGV
                    $CutMSGV = Str::between($key,'MSGV','MSSV');
                    //Lấy Học kỳ & năm học
                    $CutHK = Str::between($key,'HocKy','NamHoc');
                    $CutNamHoc = Str::between($key,'NamHoc','MSGV');
                    $MaHK = $CutHK.$CutNamHoc;
                    $findHK = DB::table('hoc_ky')->where('MaHK',$MaHK)->first();

                    if($findHK == null)
                    {
                        //Nếu tìm không có thì thêm mới Học kỳ
                        $InsertNewHK = DB::table('hoc_ky')->insert([
                            'MaHK' => $MaHK,
                            'HocKy' => $CutHK,
                            'NamHoc' => $CutNamHoc
                        ]);
                    }

                    //Tạo Mã Danh sách
                    //Kiểm tra đã tồn tại danh sách hay chưa
                    $MaDanhSachTam = $CutClass.$MaHK;
                    $checkDBDSSV = DB::table('danh_sach_sinh_vien')->where('MaDanhSach','like',$MaDanhSachTam.'%')->first();
                    if($checkDBDSSV != null)
                    {
                        $countValidList = DB::table('danh_sach_sinh_vien')->where('MaDanhSach','like',$MaDanhSachTam.'%')->distinct()->count('MaDanhSach');
                        $stt = $countValidList+1;
                    }
                    else
                    {
                         //Lấy số thứ tự nếu chưa tồn tại danh sách trong học kỳ
                        $array = session('DanhSachSinhVienTam');
                        $position = array_search($key, $array);
                        $stt = $position +1;
                    }
                    $MaDanhSach = $CutClass.$MaHK.$stt;

                    //Tạo MaKQSV
                    $MaKQSV = $Mssv.$CutClass.$MaHK;
                    try
                    {
                        $InsertDanhSachKetQua = DB::table('ket_qua')->insert([
                            'MaKQSV' => $MaKQSV
                        ]);
                    }
                    catch(Exception $ex)
                    {
                        return redirect()->to('/Them-danh-sach-sv?lop='.session()->get('classAddId').'&HK='.session()->get('HKid'))->with('error-AddDSSV','Đã tồn tại danh sách sinh viên '.$Mssv.' trong lớp này! ')->withInput();
                    }
                    //Insert danh sách
                    try{
                        $InsertDanhSachSV = DB::table('danh_sach_sinh_vien')->insert([
                            'MaDanhSach' => $MaDanhSach,
                            'MaTTMH' => $CutClass,
                            'MSSV' => $Mssv,
                            'MSGV' => $CutMSGV,
                            'MaHK' => $MaHK,
                            'MaKQSV' => $MaKQSV
                        ]);
                    }
                    catch(Exception $ex)
                    {
                        return redirect()->to('/Them-danh-sach-sv?lop='.session()->get('classAddId').'&HK='.session()->get('HKid'))->with('error-AddDSSV','Đã tồn tại danh sách sinh viên '.$Mssv.' trong lớp này! ')->withInput();
                    }
                    //Insert TKB:
                    $getAllLichGiangDay = DB::table('lich_giang_day')->where('MaTTMH',$CutClass)->get();
                    foreach($getAllLichGiangDay as $getList)
                    {
                        $InsertTKB = DB::table('tkb')->insert([
                            'MaNgay' => $getList->MaNgay,
                            'MSSV' => $Mssv
                        ]);
                    }
                }
                session()->forget('DanhSachSinhVienTam');
                return redirect()->to('/Them-danh-sach-sv?lop='.session()->get('classAddId').'&HK='.session()->get('HKid'))->with('success-AddDSSV','Thêm thành công');
            }
            else
            {
                return redirect()->to('/Them-danh-sach-sv?lop='.session()->get('classAddId').'&HK='.session()->get('HKid'))->with('error-AddDSSV','Không tồn tại danh sách cần xác nhận')->withInput();
            }
        }

        public function FrmThemGV()
        {
            return view('admin/teacher-add');
        }

        public function ThemGV(Request $request)
        {
            $validated = $request->validate([
                'msgv' => 'required',
                'teachername' => 'required',
                'password' => 'required'
            ]);
            if($request != null)
            {
                $temp = 'MSGV'.$request->msgv.'HoTen'.$request->teachername.'Pass'.$request->password.'Role'.$request->role.'KHOA'.$request->khoa;
                session()->push('DanhSachGVTam',$temp);
                return redirect()->to('/quan-ly-gv');
            }
            else
            {
                return redirect()->to('/quan-ly-gv')->with('error-Add-T',' Lỗi nhập liệu ')->withInput();
            }
        }

        public function XoaGVDSTam(Request $request)
        {
            $array = session('DanhSachGVTam');
            $position = array_search($request->id, $array);
            unset($array[$position]);
            session(['DanhSachGVTam' => $array]);
            return redirect()->to('/quan-ly-gv');
        }

        public function XacNhanThemGV(Request $request)
        {
            if(session('DanhSachGVTam') != null)
            {
                foreach (session()->get('DanhSachGVTam') as $temp)
                {
                        $MSGVCut = Str::between($temp,'MSGV','HoTen');
                        $HoTen = Str::between($temp,'HoTen','Pass');
                        $Password = Str::between($temp,'Pass','Role');
                        $CutRoleid = Str::between($temp,'Role','KHOA');
                        // $FindCV = DB::table('chuc_vu')->where('MaChucVu',$CutRoleid)->first();
                        $CutKhoa = Str::after($temp,'KHOA');

                    try{

                        //Insert GV
                        $InsertGV = DB::table('giang_vien')->insert([
                            'MSGV' => $MSGVCut,
                            'Password' => md5($Password),
                            'HoTenGV' => $HoTen,
                            'MaChucVu' => $CutRoleid,
                            'MaKhoa' => $CutKhoa
                        ]);
                    }
                    catch(Exception $ex)
                    {
                        return redirect()->to('/quan-ly-gv')->with('error-Add-T','Đã tồn tại giảng viên')->withInput();
                    }
                }
                session()->forget('DanhSachGVTam');
                return redirect()->to('/quan-ly-gv')->with('success-Add-T','Thêm thành công');

            }
            else
            {
                return redirect()->to('/quan-ly-gv')->with('error-Add-T','Không tồn tại danh sách cần xác nhận')->withInput();
            }
        }

        public function FrmThemLopNienKhoa()
        {
            return view('admin/class-add');
        }

        public function ThemLop(Request $request)
        {
            if($request != null)
            {
                $temp = 'Lop'.$request->classid.'KHOAHOC'.$request->KhoaHoc.'year'.$request->startYears.'-'.$request->endYears;
                // dd($temp);
                session()->push('DanhSachLopNKTam',$temp);
                return redirect()->to('/them-lop-nien-khoa');
            }
            else
            {
                return redirect()->to('/quan-ly-gv')->with('error-Add-C',' Lỗi nhập liệu ')->withInput();
            }
            return redirect()->to('/them-lop-nien-khoa');
        }

        public function XoaDSLopTam(Request $request)
        {
            $array = session('DanhSachLopNKTam');
            $position = array_search($request->id, $array);
            unset($array[$position]);
            session(['DanhSachLopNKTam' => $array]);
            return redirect()->to('/them-lop-nien-khoa');
        }

        public function XacNhanThemLopNK(Request $request)
        {
            if(session('DanhSachLopNKTam') != null)
            {
                foreach (session()->get('DanhSachLopNKTam') as $temp)
                {
                    $CutLop = Str::between($temp,'Lop','KHOAHOC');
                    $CutKhoaHoc = Str::between($temp,'KHOAHOC','year');
                    $CutNamBatDau = Str::between($temp,'year','-');
                    $CutNamKetThuc = Str::after($temp,'-');

                    //Kiểm tra có tồn tại năm học đó chưa
                    $CheckKH = DB::table('khoa_hoc')->where('KhoaHoc',$CutKhoaHoc)->first();
                    if($CheckKH == null)
                    {
                        //chưa tồn tại năm học => thêm mới
                        $InsertNewKH = DB::table('khoa_hoc')->insert([
                            'KhoaHoc' => $CutKhoaHoc,
                            'NamHocDuKien' => $CutNamBatDau.'-'.$CutNamKetThuc
                        ]);
                    }

                    //Thêm lớp học
                    $insertNewLopNK = DB::table('lop')->insert([
                        'MaLop' => $CutLop,
                        'TenLop' => $CutLop,
                        'KhoaHoc' => $CutKhoaHoc
                    ]);
                }
                session()->forget('DanhSachLopNKTam');
                return redirect()->to('/them-lop-nien-khoa')->with('success-Add-C','Thêm thành công');
            }
            else
            {
                return redirect()->to('/them-lop-nien-khoa')->with('error-Add-C','Không tồn tại danh sách cần xác nhận')->withInput();
            }
        }
}
