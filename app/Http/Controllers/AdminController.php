<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
Use Exception;
use Illuminate\Support\Str;
use Carbon\Carbon;
class AdminController extends Controller
{
    public function admin()
    {
        if(session()->get('ChucVu') == 'AM' )
            return view('admin/student-add');
        elseif(session()->get('ChucVu') == 'QL')
            return redirect()->to('/danh-sach-lop');
        else
            return redirect()->to('/');
    }

    public function frmAddSV(Request $request)
    {
        if(session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
        {
            if($request->mssv)
            {
                $showSV = DB::table('sinh_vien')->where('MSSV',$request->mssv)->first();
                return view('admin/student-add',['StudentToChange' => $showSV]);
            }
            else
            {
                return view('admin/student-add');
            }

        }
        else
        {
            return redirect()->to("/");
        }
    }

    public function themSinhVien(Request $request)
    {
        // $sinhVien = DB::table('sinh_vien');

        // $sinhVien -> MSSV = $request->input('MSSV');
        // $sinhVien -> HoTenSV = $request->input('HoTenSV');
        // $sinhVien -> TenLop = $request->input('TenLop');

        // $sinhVien->save(); //Update database
        //Điều kiện lọc lỗi
        $validated = $request->validate([
            'mssv' => 'required',
            'studentname' => 'required',
            'classname' => 'required',
            // 'password' => 'required'
        ]);
        if(preg_match('/^[0-9]*$/',$request->mssv) == 0 && preg_match('/^[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]*$/',$request->mssv) == 0)
        {
            // dd(preg_match('/^ [0-9]*$/',$request->mssv));
            return redirect()->to('/quan-ly-sinh-vien')->with('error-Add','Mã số sinh viên không hợp lệ!')->withInput();
        }

        if($request != null)
        {


            if(session()->has('teacherid') && session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
            {
                try
                {
                    session()->put('MSSV',$request->mssv);
                    session()->put('Name',$request->studentname);
                    session()->put('ClassName',$request->classname);
                    session()->put('PassWord',$request->mssv); //Mật khẩu tạo mặc định == MSSV
                    // session()->put('Phuong',$request->phuong);
                    // session()->put('Quan',$request->quan);
                    // session()->put('TP',$request->TP);
                    // session()->put('DiaChi',$request->DiaChi);

                }
                catch(Exception $ex)
                {
                    return redirect()->to('/quan-ly-sinh-vien')->with('error-Add','lỗi nhập liệu!')->withInput();
                }
            }
            if($request->reset == null)
            {
                $resetkey = 0;
            }
            else
            {
                $resetkey = $request->reset;
            }
            // $temp[] = session()->get('MSSV').'  '.session()->get('Name').' '.session()->get('ClassName').' '.session()->get('PassWord').' '.session()->get('Phuong').' '.session()->get('Quan').' '.session()->get('TP').' '.session()->get('DiaChi');
            $temp= session()->get('MSSV').'HoTen'.session()->get('Name').'MK'.session()->get('PassWord').'ResetKey'.$resetkey.session()->get('ClassName');
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

    public function confirmAddStudent(Request $request)
    {
        //Xử lý insert
        if(session()->get('DanhSachTam') != null)
        {

            foreach(session()->get('DanhSachTam') as $temp)
            {
                // dd($temp);
                //  $MSSVCut = substr($temp,0,10);
                $MSSVCut = Str::before($temp,'HoTen');
                $CutClass = substr($temp,-7);
                $HoTen = Str::between($temp,'HoTen','MK');
                $Password = Str::between($temp,'MK','ResetKey');
                $IsResetPassReq = Str::between($temp,'ResetKey',$CutClass);


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
                    $checkUserIsActive = DB::table('sinh_vien')->where('MSSV',$MSSVCut)->first();
                    if($IsResetPassReq != 0)
                    {
                        if($checkUserIsActive->HoTenSV != $HoTen)
                            {
                                $upDateAnotherPass =  DB::table('sinh_vien')->where('MSSV',$MSSVCut)
                                                        ->update([
                                                            'HoTenSV' => $HoTen
                                                        ]);
                            }
                        $ResetToDefaultPass = DB::table('sinh_vien')->where('MSSV',$MSSVCut)
                                            ->update([
                                                'Password' => md5($Password),
                                                'Confirmed' => 0,
                                                'LastActive' => Carbon::now()->format('Y-m-d')
                                            ]);

                        $array = session()->get('DanhSachTam');
                        $position = array_search($temp, $array);
                        unset($array[$position]);
                        session(['DanhSachTam' => $array]);
                        return redirect()->to('/quan-ly-sinh-vien')->with('success-Add','Reset tài khoản '.$MSSVCut.' thành công');
                    }


                    $checkUserIsActive = DB::table('sinh_vien')->where('MSSV',$MSSVCut)->first();
                    //Nếu tồn tại tk và người dùng đã qua 6 tháng chưa quay lại
                    if($checkUserIsActive->MSSV != null && Carbon::now()->greaterThan(Carbon::parse($checkUserIsActive->LastActive)->addMonths(6)) == true)
                    {
                        //Khôi phục người dùng
                        $upDateLastActiveData = DB::table('sinh_vien')->where('MSSV',$MSSVCut)
                                                                    ->update([
                                                                        'LastActive' => Carbon::now()->format('Y-m-d')
                                                                    ]);

                        $array = session()->get('DanhSachTam');
                        $position = array_search($temp, $array);
                        unset($array[$position]);
                        session(['DanhSachTam' => $array]);
                        return redirect()->to('/quan-ly-sinh-vien')->with('success-Add','tái kích hoạt sinh viên '.$MSSVCut.' thành công');
                    }

                    return redirect()->to('/quan-ly-sinh-vien')->with('error-Add','Đã tồn tại sinh viên'.' '.substr($temp,0,10).'')->withInput();

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
            return redirect()->to('/quan-ly-sinh-vien')->with('success-Add','Thêm thành công');
        }
        else
        {
           return redirect()->to('/quan-ly-sinh-vien')->with('error-Add','Không tồn tại danh sách cần xác nhận')->withInput();
        }


    }

    public function DeletSinhVien(Request $request)
    {
        $array = session('DanhSachTam');
        $position = array_search($request->id, $array);
        // dd($position);
        unset($array[$position]);
        session(['DanhSachTam' => $array]);
        return redirect()->to('/quan-ly-sinh-vien');
    }

    public function FrmThemLopHoc()
    {
        // dd($request);
        if(session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
        {
            return view('admin/class-subject-add');
        }
        else
        {
            return redirect()->to("/");
        }
    }
    public function ThemDanhSach(Request $request)
    {
        if($request != null)
        {
            if(session()->has('teacherid') && session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
            {
                if($request->teacherid == null)
                {
                    return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Không được bỏ trống giảng viên!')->withInput();
                }
                if($request->subjectname == null)
                {
                    return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Không được bỏ trống môn học!')->withInput();
                }
                if($request->classname == null)
                {
                    return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Không được bỏ trống Lớp học!')->withInput();
                }
                if($request->HKid == null)
                {
                    return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Không được bỏ trống Học kỳ!')->withInput();

                }
                if($request->tiethocid == null)
                {
                    return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Không được bỏ trống Tiết học!')->withInput();

                }

                try
                {
                    //Bổ sung thời gian tiết học
                    $checkclasstime = DB::table('tiet_hoc')->where('MaTietHoc',$request->tiethocid)->first();
                    //Kiểm tra thời gian bắt đầu có phù hợp
                    $time = Carbon::parse(Carbon::parse($request->timestart.' '.$checkclasstime->ThoiGianBatDau));
                    $formattedTime = $time->format('dmYHi');
                    $timeForTemp = $time->format('d-m-Y H:i');
                    //Kiểm tra thời gian kết thúc có phù hợp
                    $Anothertime = Carbon::parse($request->timestart.' '.$checkclasstime->ThoiGianKetThuc);
                    $formattedTimeEnd = $Anothertime->format('dmYHi');
                    $timeEndForTemp = $Anothertime->format('d-m-Y H:i');

                    $SubjectInfo = DB::table('mon_hoc')->where('MaTTMH',$request->subjectname)->first();
                    $temp= $timeForTemp.' MaMH'.$request->subjectname.'TenMH '.$SubjectInfo->TenMH.'Lop'.$request->classname.'GV'.$request->teacherid.'TimeEnd'.$timeEndForTemp.'HK'.$request->HKid;

                    session()->push('DanhSachLopTam',$temp); //Thêm vào danh sách tạm
                }
                catch(Exception $ex)
                {
                    return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','lỗi nhập liệu!')->withInput();
                }
            }
            return redirect()->to('/quan-ly-lop-hoc');
        }
        else{
            return view('admin/class-subject-add')->with('error-AddClass','lỗi nhập liệu!');
        }

        // $time = Carbon::parse($request->timestart);
        // $formattedTime = $time->format('dmYhm');

        // dd($request);
    }

    public function DeletLop(Request $request)
    {

        $array = session('DanhSachLopTam');
        $position = array_search($request->id, $array);
        unset($array[$position]);
        session(['DanhSachLopTam' => $array]);
        return redirect()->to('/quan-ly-lop-hoc');
    }

    public function ConfirmAddClass()
    {
        // dd(session()->get('DanhSachLopTam'));
        if(session()->get('DanhSachLopTam') != null)
        {
            foreach(session()->get('DanhSachLopTam') as $temp)
            {
                $date = Str::before($temp,'MaMH');
                $dateEnd = Str::between($temp,'TimeEnd','HK');
                $datetimeConvert = Carbon::parse($date);
                $dateEndConvert = Carbon::parse($dateEnd);
                $formattedTime = $datetimeConvert->format('dmYHi');
                $CutHK = Str::after($temp,'HK');
                $CutClass = Str::between($temp,'Lop','GV');
                $CutMSGV = Str::between($temp,'GV','TimeEnd');
                $MaTTMH = Str::between($temp,'MaMH','TenMH');
                $checkLop = DB::table('lich_giang_day')->where('MaTTMH',$MaTTMH)->where('MaLop',$CutClass)
                            ->where('MaBuoi',1)->first();

                if($checkLop == null)
                {
                    $stt='1';
                    $formatTime = $datetimeConvert->format('H:i');

                        $formatCheckDate1 = $dateEndConvert->format('d-m-y');
                        $formatCheckDate = $datetimeConvert->format('d-m-y');

                        if( $formatCheckDate1 != $formatCheckDate)
                        {
                            return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Thời gian diễn ra tiết học quá xa')->withInput();
                        }

                        $formatTimeEnd = $dateEndConvert->format('H:i');



                        $checkHKisAvailable = DB::table('hoc_ky')->where('MaHK',$CutHK)->first();
                        $CutNamHoc = substr($temp,-4);
                        $CutHocKy = Str::between($temp,'HK',$CutNamHoc);
                        if($checkHKisAvailable == null)
                        {
                            //Nếu chưa tồn tại học kì đó thì sẽ insert vào trước
                            $PutHK = DB::table('hoc_ky')->insert([
                                'MaHK' => $CutHK,
                                'HocKy' => $CutHocKy,
                                'NamHoc'=>  $CutNamHoc,
                            ]);
                        }

                        $GetTimeForInsert = DB::table('tiet_hoc')->where('ThoiGianBatDau',$formatTime)
                        ->where('ThoiGianKetThuc',$formatTimeEnd)->first();

                        // dd($formatTime);
                        try
                        {
                            $insertTheClassList = DB::table('lich_giang_day')->insert([
                                'MaNgay' => $stt.$MaTTMH.$formattedTime,
                                'NgayDay' => $datetimeConvert,
                                'MaTTMH' => $MaTTMH,
                                'MSGV' =>  $CutMSGV,
                                'MaLop' => $CutClass,
                                'MaHK' => $CutHK,
                                'MaTietHoc' => $GetTimeForInsert->MaTietHoc,
                                'MaBuoi' => $stt
                            ]);
                        }
                        catch(Exception $ex)
                        {
                            // dd($ex);
                            return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Lỗi nhập liệu danh sách'.' '.Str::between($temp,'MaMH','TenMH').'')->withInput();

                        }
                        $array = session('DanhSachLopTam');
                        $position = array_search($temp, $array);
                        unset($array[$position]);
                        session(['DanhSachLopTam' => $array]);

                }
                else{
                    $date = Str::before($temp,'MaMH');
                    $datetimeConvert = Carbon::parse($date);
                    $formattedDateTime = $datetimeConvert->format('d-m-Y');
                    $cutNgay = substr($checkLop->MaNgay,-12);
                    $CutNgayConvert = Carbon::createFromFormat('dmYHi',$cutNgay);
                    $formattedDate = $CutNgayConvert->format('d-m-Y');
                    $dateResign = Carbon::parse($formattedDateTime);
                    $dateOld = Carbon::parse($formattedDate);

                    if($dateOld->diffInMonths($dateResign) >= 5)
                    {
                        //Tháng đăng ký hơn 5 tháng a.k.a đã qua học kì mới
                        //Bắt đầu lại từ buổi số 1
                        $stt='1';
                        $formatTime = $datetimeConvert->format('H:i');

                            $formatCheckDate1 = $dateEndConvert->format('d-m-y');
                            $formatCheckDate = $datetimeConvert->format('d-m-y');

                            if( $formatCheckDate1 != $formatCheckDate)
                            {
                                return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Thời gian diễn ra tiết học quá xa')->withInput();
                            }

                            $formatTimeEnd = $dateEndConvert->format('H:i');



                            $checkHKisAvailable = DB::table('hoc_ky')->where('MaHK',$CutHK)->first();
                            $CutNamHoc = substr($temp,-4);
                            $CutHocKy = Str::between($temp,'HK',$CutNamHoc);
                            if($checkHKisAvailable == null)
                            {
                                //Nếu chưa tồn tại học kì đó thì sẽ insert vào trước
                                $PutHK = DB::table('hoc_ky')->insert([
                                    'MaHK' => $CutHK,
                                    'HocKy' => $CutHocKy,
                                    'NamHoc'=>  $CutNamHoc,
                                ]);
                            }

                            $GetTimeForInsert = DB::table('tiet_hoc')->where('ThoiGianBatDau',$formatTime)
                            ->where('ThoiGianKetThuc',$formatTimeEnd)->first();

                            try
                            {
                                $insertTheClassList = DB::table('lich_giang_day')->insert([
                                    'MaNgay' => $stt.$MaTTMH.$formattedTime,
                                    'NgayDay' => $datetimeConvert,
                                    'MaTTMH' => $MaTTMH,
                                    'MSGV' =>  $CutMSGV,
                                    'MaLop' => $CutClass,
                                    'MaHK' => $CutHK,
                                    'MaTietHoc' => $GetTimeForInsert->MaTietHoc,
                                    'MaBuoi' => $stt
                                ]);
                            }
                            catch(Exception $ex)
                            {

                                return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Lỗi nhập liệu danh sách'.' '.Str::between($temp,'MaMH','TenMH').'')->withInput();

                            }

                            $array = session('DanhSachLopTam');
                            $position = array_search($temp, $array);
                            unset($array[$position]);
                            session(['DanhSachLopTam' => $array]);
                    }
                    else
                    {
                    //Thêm tiếp các buổi 2,3,4,5 theo stt lấy từ db +1
                        $stt = DB::table('lich_giang_day')->where('MaTTMH',$MaTTMH)->where('MaHK',$CutHK)->distinct()->count('MaNgay');

                        $phanloailop = substr($CutClass, 3, 1);
                        if ($phanloailop == '1' || $phanloailop == '2')
                        {
                            if($stt >= 9 )
                            {
                                return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Lớp '.' '.Str::between($temp,'TenMH','Lop').' đã đủ số buổi')->withInput();
                            }
                        }
                        else if($phanloailop == '3')
                        {
                            if($stt >= 6 )
                            {
                                return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Lớp '.' '.Str::between($temp,'TenMH','Lop').' đã đủ số buổi')->withInput();
                            }
                        }
                        if($stt != 0)
                        {
                            ++$stt;
                        }
                        // dd($stt);
                        $formatTime = $datetimeConvert->format('H:i');
                        $checkTypeTime = DB::table('tiet_hoc')->where('ThoiGianBatDau',$formatTime)->first();
                        if($checkTypeTime != null)
                        {
                            // dd($formatTime);
                            try
                            {
                                $insertTheClassList = DB::table('lich_giang_day')->insert([
                                    'MaNgay' => $stt.$MaTTMH.$formattedTime,
                                    'NgayDay' => $datetimeConvert,
                                    'MaTTMH' => $MaTTMH,
                                    'MSGV' =>  $CutMSGV,
                                    'MaLop' => $CutClass,
                                    'MaHK' => $CutHK,
                                    'MaTietHoc' => $checkTypeTime->MaTietHoc,
                                    'MaBuoi' => $stt
                                ]);
                            }
                            catch(Exception $ex)
                            {

                                return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Lỗi nhập liệu danh sách lớp '.' '.Str::between($temp,'MaMH','TenMH').' đã tồn tại')->withInput();

                            }
                            $array = session('DanhSachLopTam');
                            $position = array_search($temp, $array);
                            unset($array[$position]);
                            session(['DanhSachLopTam' => $array]);
                        }
                        else
                        {
                            return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Không tồn tại tiết học này')->withInput();
                        }
                    }


                }

            }
            session()->forget('DanhSachLopTam');
            return redirect()->to('/quan-ly-lop-hoc')->with('success-AddClass','Thêm thành công');
        }
        else
        {
            return redirect()->to('/quan-ly-lop-hoc')->with('error-AddCLass','Không tồn tại danh sách cần xác nhận')->withInput();
        }
    }

    public function addStudentToClass()
    {
        if(session()->has('teacherid'))
        {
            if(session()->has('TimeAddDSTam'))
            {
                if(Carbon::now()->greaterThan(Carbon::parse(session()->get('TimeAddDSTam'))->addMinutes(1)) == true)
                {
                    session()->forget('DanhSachSinhVienTam');
                    session()->forget('textByScan');
                }
            }
            return view('admin/student-add-to-class');
        }
        else
        {
            return redirect()->to("/");
        }

    }

    public function addStudentToClassBack(Request $request)
    {
        // dd(session()->forget('DanhSachSinhVienTam'));
        // $limit = count($request->mssv);
        // dd($request);
        $messages = [
            'classname.required' => 'Không được bỏ trống Mã môn học',
            'classgroup.required' => 'Không được bỏ trống Nhóm môn học',
        ];
        $validated = $request->validate([
            'classname' => 'required',
            'classgroup' => 'required',
        ], $messages);
        //Mã MH
        if(preg_match('/^[a-zA-Z0-9]*$/',$request->classname) == 1 && preg_match('/^[!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]*$/',$request->classname) != 1)
        {
            $MaMH = $request->classname;
        }
        else
        {
            return redirect()->back()->with('error-input','Nhập sai mã môn học')->withInput();
        }
        //Nhóm MH
        if(preg_match('/^[1-9]*$/',$request->classgroup) == 1 && preg_match('/^[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]*$/',$request->classgroup) != 1)
        {
            $NMH = $request->classgroup;

        }
        else
        {
            return redirect()->back()->with('error-input','Nhập sai nhóm môn học')->withInput();
        }

        //Số tín chỉ
        if($request->STC)
        {
            if(preg_match('/^[1-9]*$/',$request->STC) == 1 && preg_match('/^[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]*$/',$request->STC) != 1)
            {
                $STC = $request->STC;

            }
            else
            {
                return redirect()->back()->with('error-input','Nhập sai số tín chỉ môn học')->withInput();
            }
        }


        if($request->subjectname)
        {
            $tenMH = $request->subjectname;
        }


        if ($NMH >= 1 && $NMH <= 9) {
            $NMH = "0".$NMH;
        }
        if($MaMH != null && $NMH != null)
        {
            $MaTTMH = $MaMH.$NMH;
        }
        if($request->student_info_MSSV != null && $request->student_info_Student_Name != null
            && $request->student_info_Birthday != null && $request->student_info_Class != null)
        {

            // dd($request);
            $MonthCheck = Carbon::now()->month;
            //Kiểm tra xem tháng hiện tại thuộc học kỳ mấy
            switch($MonthCheck)
            {
                case 1:
                    $hocky = "1B";
                    break;
                case 2:
                    $hocky = "2A";
                    break;
                case 3:
                    $hocky = "2A";
                    break;
                case 4:
                    $hocky = "2A";
                    break;
                case 5:
                    $hocky = "2B";
                    break;
                case 6:
                    $hocky = "2B";
                    break;
                case 7:
                    $hocky = "2B";
                    break;
                case 8:
                    $hocky = "1A";
                    break;
                case 9:
                    $hocky = "1A";
                    break;
                case 10:
                    $hocky = "1A";
                    break;
                case 11:
                    $hocky = "1B";
                    break;
                case 12:
                    $hocky = "1B";
                    break;
            }

            $HocKyCheck = $hocky.Carbon::now()->year;
            $CheckHKIsAvailableOrNot = DB::table('hoc_ky')->where('MaHK',$HocKyCheck)->first();

            if($CheckHKIsAvailableOrNot == null )
            {
                //Thêm học kỳ nếu chưa tồn tại
               $insertHK = DB::table('hoc_ky')->insert([
                'MaHK'=>$HocKyCheck,
                'HocKy' => $hocky,
                'NamHoc' => Carbon::now()->year
               ]);
            }
            else
            {
                //Nothing
            }

            //Kiểm tra xem lớp (môn + nhóm môn có tồn tại?)
            $checkSubjectClassIsVailable = DB::table('mon_hoc')->where('MaMH',$MaMH)->where('NhomMH',$NMH)->first();
            if($checkSubjectClassIsVailable == null)
            {
                if($request->subjectname)
                {
                    if($request->STC)
                    {
                        //Chưa tồn tại lớp, tạo mới
                        try{
                            $insertSubjectClass = DB::table('mon_hoc')->insert([
                                'MaTTMH' => $MaTTMH,
                                'MaMH' => $MaMH,
                                'NhomMH' => $NMH,
                                'TenMH' => $request->subjectname,
                                'STC' => $STC
                            ]);
                        }
                        catch(Exception $ex)
                        {
                            return redirect()->back()->with('error-input','Lỗi nhập liệu thông tin môn học')->withInput();
                        }

                    }
                    else
                    {
                        return redirect()->back()->with('error-input','Nhóm học hoặc môn học chưa tồn tại, xin hãy số tín chỉ')->withInput();;
                    }

                }
                else
                {
                    return redirect()->back()->with('error-input','Nhóm học hoặc môn học chưa tồn tại, xin hãy nhập tên')->withInput();;
                }
            }

            //Kiểm tra xem lớp đã tồn tại chưa
            $checkClassIsAvailable = DB::table('lop')->where('MaLop',$request->student_info_Class[0])
                ->first();
            if($checkClassIsAvailable == null)
            {
                $NamHoc = "20".substr($request->student_info_Class[0],0,2);
                //Kiểm tra khóa học
                $CheckKHIsAvailable= DB::table('khoa_hoc')->where('KhoaHoc',$NamHoc)->first();
                if($CheckKHIsAvailable == null)
                {
                    $insertKhoaHOC = DB::table('khoa_hoc')->insert([
                        'KhoaHoc' => $NamHoc,
                        'NamHocDuKien' => $NamHoc.'-'.Carbon::parse($NamHoc)->addYears(3)->year
                    ]);
                }
                //Insert
                $insertClassINDB = DB::table('lop')->insert([
                    'MaLop' => $request->student_info_Class[0],
                    'TenLop' => $request->student_info_Class[0],
                    'KhoaHoc' => $NamHoc
                ]);
            }


            //Kiểm tra lịch giảng dạy môn A HK B đã tồn tại hay chưa
            $checkScheduleIsAvailable = DB::table('lich_giang_day')->where('MaTTMH',$MaTTMH)->where('MaHK',$HocKyCheck)->first();
            if($checkScheduleIsAvailable == null)
            {
                //Nếu chưa tồn tại tạo lịch buổi 1 cho lớp
                //Tạo lịch tự động cho 9 buổi

                $formatTime = Carbon::parse('07:30')->format('H:i');
                $TimeToAdd = Carbon::now();
                $phanloailop = substr($MaTTMH, 3, 1);
                if ($phanloailop == '1' || $phanloailop == '2') //Môn lý thuyết
                {
                    for($i = 0;$i<9;$i++)
                    {
                        $stt=$i+1;
                        if($stt != 1)
                        {
                            $TimeToAdd = $TimeToAdd->addWeeks(1);
                        }
                        $formatedTime = $TimeToAdd->format('dmYHi');
                        $checkTypeTime = DB::table('tiet_hoc')->where('ThoiGianBatDau',$formatTime)->first();
                        $insertTheClassList = DB::table('lich_giang_day')->insert([
                            'MaNgay' => $stt.$MaTTMH.$formatedTime,
                            'NgayDay' => $TimeToAdd,
                            'MaTTMH' => $MaTTMH,
                            'MSGV' =>  session()->get('teacherid'),
                            'MaLop' => $request->student_info_Class[0],
                            'MaHK' => $HocKyCheck,
                            'MaTietHoc' => $checkTypeTime->MaTietHoc,
                            'MaBuoi' => $stt
                        ]);
                    }
                }
                else if($phanloailop == '3') //Môn thực hành
                {
                    for($i = 0;$i<6;$i++)
                    {
                        $stt=$i+1;
                        if($stt != 1)
                        {
                            $TimeToAdd = $TimeToAdd->addWeeks(1);
                        }
                        $formatedTime = $TimeToAdd->format('dmYHi');
                        $checkTypeTime = DB::table('tiet_hoc')->where('ThoiGianBatDau',$formatTime)->first();
                        $insertTheClassList = DB::table('lich_giang_day')->insert([
                            'MaNgay' => $stt.$MaTTMH.$formatedTime,
                            'NgayDay' => $TimeToAdd,
                            'MaTTMH' => $MaTTMH,
                            'MSGV' =>  session()->get('teacherid'),
                            'MaLop' => $request->student_info_Class[0],
                            'MaHK' => $HocKyCheck,
                            'MaTietHoc' => $checkTypeTime->MaTietHoc,
                            'MaBuoi' => $stt
                        ]);
                    }
                }
            }
            else
            {
                $getTimeFromClass = DB::table('tiet_hoc')->where('MaTietHoc',$checkScheduleIsAvailable->MaTietHoc)->first();
                $formatTime = Carbon::parse($getTimeFromClass->ThoiGianBatDau)->format('H:i');
                $TimeToAdd = Carbon::now();
                $stt = DB::table('lich_giang_day')->where('MaTTMH',$MaTTMH)->where('MaHK',$HocKyCheck)->distinct()->count('MaNgay');
                        $phanloailop = substr($MaTTMH, 3, 1);
                        if ($phanloailop == '1' || $phanloailop == '2')
                        {
                            if($stt < 9 )
                            {
                                $limit = 9-$stt; //Kiểm tra xem lớp còn lại bao nhiêu buổi

                                for($i = 0;$i<$limit;$i++)
                                {
                                    $stt=$stt+1;
                                    if($stt != 1)
                                    {
                                        $TimeToAdd = $TimeToAdd->addWeeks(1);
                                    }
                                    $formatedTime = $TimeToAdd->format('dmYHi');
                                    $checkTypeTime = DB::table('tiet_hoc')->where('ThoiGianBatDau',$formatTime)->first();
                                    $insertTheClassList = DB::table('lich_giang_day')->insert([
                                        'MaNgay' => $stt.$MaTTMH.$formatedTime,
                                        'NgayDay' => $TimeToAdd,
                                        'MaTTMH' => $MaTTMH,
                                        'MSGV' =>  session()->get('teacherid'),
                                        'MaLop' => $request->student_info_Class[0],
                                        'MaHK' => $HocKyCheck,
                                        'MaTietHoc' => $checkTypeTime->MaTietHoc,
                                        'MaBuoi' => $stt
                                    ]);
                                }
                            }
                        }
                        else if($phanloailop == '3')
                        {
                            if($stt >= 6 )
                            {

                                $limit = 6-$stt; //Kiểm tra xem lớp còn lại bao nhiêu buổi
                                for($i = 0;$i<$limit;$i++)
                                {
                                    $stt=$stt+1;
                                    if($stt != 1)
                                    {
                                        $TimeToAdd = $TimeToAdd->addWeeks(1);
                                    }
                                    $formatedTime = $TimeToAdd->format('dmYHi');
                                    $checkTypeTime = DB::table('tiet_hoc')->where('ThoiGianBatDau',$formatTime)->first();
                                    $insertTheClassList = DB::table('lich_giang_day')->insert([
                                        'MaNgay' => $stt.$MaTTMH.$formatedTime,
                                        'NgayDay' => $TimeToAdd,
                                        'MaTTMH' => $MaTTMH,
                                        'MSGV' =>  session()->get('teacherid'),
                                        'MaLop' => $request->student_info_Class[0],
                                        'MaHK' => $HocKyCheck,
                                        'MaTietHoc' => $checkTypeTime->MaTietHoc,
                                        'MaBuoi' => $stt
                                    ]);
                                }
                            }
                        }

            }

            $limit = count($request->student_info_MSSV);

            $i =0;
            while($i<$limit)
            {
                if($request->student_info_MSSV[$i] != null && $request->student_info_Student_Name[$i] != null
                    && $request->student_info_Birthday[$i] != null && $request->student_info_Class[$i] != null)
                {
                    $sttds = $i + 1 ;
                    if(preg_match('/^[0-9]*$/',$request->student_info_MSSV[$i]) == 0 && preg_match('/^[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]*$/',$request->student_info_MSSV[$i]) == 0)
                    {
                        return redirect()->back()->with('error-input','Mã số sinh viên số thứ tự '.$sttds.' không hợp lệ')->withInput();
                    }
                    $temp = $MaTTMH.'HocKy'.$hocky.'NamHoc'.Carbon::now()->year.'MSGV'.session()->get('teacherid').'MSSV'.$request->student_info_MSSV[$i].'MaTTMH'.$MaTTMH.'HoTenSV'.$request->student_info_Student_Name[$i].'NgayThangNamSinh'.$request->student_info_Birthday[$i].'MaLop'.$request->student_info_Class[$i];
                    session()->push('DanhSachSinhVienTam',$temp);
                }
                $i++;
            }



            // dd($i);
            session()->put('textByScan',"true");
            // dd(session()->forget('DanhSachSinhVienTam'));
            session()->put('classAddId',$MaTTMH);
            session()->put('HKid',$HocKyCheck);
            session()->put('TimeAddDSTam',Carbon::now());
            return redirect()->to('/Them-danh-sach-sv?lop='.session()->get('classAddId').'&HK='.session()->get('HKid'));
        }
        else
        {

            return redirect()->back();
        }
    }

//Thêm danh sách
    public function frmAddStudentList(Request $request)
    {
        if(session()->has('teacherid'))
        {
            if(session()->has('classAddId'))
            {
                if( session()->get('classAddId') != $request->lop)
                {

                    if(session()->has('DanhSachSinhVienTam'))
                    {
                        session()->forget('DanhSachSinhVienTam');
                    }
                    elseif(session()->has('textByScan'))
                    {
                            session()->forget('DanhSachSinhVienTam');
                            session()->forget('textByScan');
                    }
                }
                else
                {
                    if(session()->has('HKid') && session()->get('HKid') != $request->HK)
                    {
                        if(session()->has('DanhSachSinhVienTam'))
                        {
                            session()->forget('DanhSachSinhVienTam');
                        }
                        elseif(session()->has('textByScan'))
                        {
                            session()->forget('DanhSachSinhVienTam');
                            session()->forget('textByScan');
                        }
                    }
                }
            }
            session()->put('classAddId',$request->lop);
            session()->put('HKid',$request->HK);

            return view('admin/student');
        }
        else
        {
            return redirect()->to("/");
        }

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

                $checkSVisAvailable = DB::table('sinh_vien')->where('MSSV',$request->mssv)->first();
                if($checkSVisAvailable == null)
                {
                    return redirect()->to('/quan-ly-sinh-vien')->with('error-Add','Không tồn tại sinh viên mã'.$request->mssv)->withInput();
                }



                $temp = $request->classid.'HocKy'.$cutHK.'NamHoc'.$CutYearOfClass.'MSGV'.$checkfindnameTeacher->MSGV.'MSSV'.$request->mssv.'MaTTMH'.$MaTTMH.'HoTenSV'.$request->studentname.'NgayThangNamSinh';
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
            // $arrayTemp = [];
            // $arrayTemp = session()->get('DanhSachSinhVienTam');
            // $collection = collect($arrayTemp);
            //  // Sắp xếp theo giá trị ASCII của phần tử
            // $sortedCollection = $collection->sortBy(function ($item) {
            //     // Tách chuỗi thành mảng sử dụng dấu "|", và lấy phần tử thứ 1 (sau dấu "|") để sắp xếp
            //     $between = Str::between($item, 'NgayThangNamSinh', 'HoTenSV');
            //     return $between;
            // })->values();

            // // Chuyển Collection đã sắp xếp trở lại thành mảng
            // $sortedArray = $sortedCollection->all();


            if(session()->has('textByScan'))
            {
                foreach(session()->get('DanhSachSinhVienTam') as $key)
                {
                    //Lấy Mã SV
                    $Mssv = Str::between($key,'MSSV','MaTTMH');
                    //Lấy họ tên SV
                    $findStudentName = Str::between($key,'HoTenSV','NgayThangNamSinh');
                    //Lấy Mã môn
                    $CutClass = Str::before($key,'HocKy');
                    //Lấy MSGV
                    $CutMSGV = Str::between($key,'MSGV','MSSV');
                    //Lấy Học kỳ & năm học
                    $CutHK = Str::between($key,'HocKy','NamHoc');
                    $CutNamHoc = Str::between($key,'NamHoc','MSGV');
                    $MaHK = $CutHK.$CutNamHoc;
                    $CutClassId = Str::after($key,'MaLop');

                    //Kiểm tra sinh viên đã tồn tại tài khoản hay chưa
                    $CheckUserIsAvailableOrNot = DB::table('sinh_vien')->where('MSSV',$Mssv)->first();
                    if($CheckUserIsAvailableOrNot == null)
                    {
                        $insertDiaChi = DB::table('dia_chi')->insert([
                            "MaDiaChi" => $Mssv.$CutClassId
                        ]);

                        $CreateNewUser = DB::table('sinh_vien')->insert([
                            "MSSV" => $Mssv,
                            "password" => md5($Mssv),
                            "HoTenSV" => $findStudentName,
                            "MaLop" => $CutClassId,
                            "MaDiaChi" => $Mssv.$CutClassId,
                            "BanCanSu" => 0
                        ]);
                    }


                    //Tạo Mã Danh sách
                    //Kiểm tra đã tồn tại danh sách hay chưa
                    $MaDanhSachTam = $CutClass.$MaHK;
                    $checkDBDSSV = DB::table('danh_sach_sinh_vien')->where('MaDanhSach','like',$MaDanhSachTam.'%')
                    ->where('MaTTMH',session()->get('classAddId'))->where('MaHK',session()->get('HKid'))->first();
                    if($checkDBDSSV != null)
                    {
                        $countValidList = DB::table('danh_sach_sinh_vien')->where('MaDanhSach','like',$MaDanhSachTam.'%')
                        ->where('MaTTMH',session()->get('classAddId'))->where('MaHK',session()->get('HKid'))->distinct()->count('MaDanhSach');
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
                    $getAllLichGiangDay = DB::table('lich_giang_day')->where('MaTTMH',$CutClass)->where('MaHK',$MaHK)->get();
                    foreach($getAllLichGiangDay as $getList)
                    {
                        $InsertTKB = DB::table('tkb')->insert([
                            'MaNgay' => $getList->MaNgay,
                            'MSSV' => $Mssv
                        ]);
                    }
                }
                session()->forget('DanhSachSinhVienTam');
                session()->forget('textByScan');
                return redirect()->to('/')->with('SuccessClass1','Thêm danh sách thành công!')->withInput();
            }
            else
            {

                foreach(session()->get('DanhSachSinhVienTam') as $key)
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
                    $getAllLichGiangDay = DB::table('lich_giang_day')->where('MaTTMH',$CutClass)->where('MaHK',$MaHK)->get();
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

        }
        else
        {
            return redirect()->to('/Them-danh-sach-sv?lop='.session()->get('classAddId').'&HK='.session()->get('HKid'))->with('error-AddDSSV','Không tồn tại danh sách cần xác nhận')->withInput();
        }
    }

    public function FrmThemGV(Request $request)
    {
        if(session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
        {
            if($request->msgv)
            {
                $getInfoToChange = DB::table('giang_vien')->where('MSGV',$request->msgv)
                ->join('chuc_vu','chuc_vu.MaChucVu','=','giang_vien.MaChucVu')
                ->join('khoa','khoa.MaKhoa','=','giang_vien.MaKhoa')
                ->first();
                return view('admin/teacher-add',['TeacherToChange' => $getInfoToChange]);
            }
            else
            {
                return view('admin/teacher-add');
            }

        }
        else
        {
            return redirect()->to("/");
        }

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
            if($request->role == null )
            {
                return redirect()->to('/quan-ly-gv')->with('error-Add-T',' Không được bỏ trống ô chức vụ')->withInput();
            }
            if($request->khoa == null)
            {
                return redirect()->to('/quan-ly-gv')->with('error-Add-T',' Không được bỏ trống Khoa')->withInput();
            }
            if($request->reset == null)
            {
                $resetkey = 0;
            }
            else
            {
                $resetkey = $request->reset;
            }
            $temp = 'ResetKey'.$resetkey.'MSGV'.$request->msgv.'HoTen'.$request->teachername.'Pass'.$request->password.'Role'.$request->role.'KHOA'.$request->khoa;
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
                    $keyToReset = Str::between($temp,'ResetKey','MSGV');
                try{
                    $InsertDSDiaChi = DB::table('dia_chi')->insert([
                        'MaDiaChi' =>  $MSGVCut
                    ]);
                    //Insert GV
                    $InsertGV = DB::table('giang_vien')->insert([
                        'MSGV' => $MSGVCut,
                        'Password' => md5($Password),
                        'HoTenGV' => $HoTen,
                        'MaChucVu' => $CutRoleid,
                        'MaDiaChi' =>  $MSGVCut,
                        'MaKhoa' => $CutKhoa
                    ]);
                }
                catch(Exception $ex)
                {

                    $checkUserIsActive = DB::table('giang_vien')->where('MSGV',$MSGVCut)->first();
                    if($checkUserIsActive->MSGV != null)
                    {
                        if($keyToReset != 0)
                        {
                            if($checkUserIsActive->HoTenGV != $HoTen)
                            {
                                $upDateAnotherPass = DB::table('giang_vien')->where('MSGV',$MSGVCut)
                                                        ->update([
                                                            'HoTenGV' => $HoTen
                                                        ]);
                            }

                            $upDateAnotherPass = DB::table('giang_vien')->where('MSGV',$MSGVCut)
                                                        ->update([
                                                            'LastActive' => Carbon::now()->format('Y-m-d'),
                                                            'Confirmed' => 0,
                                                            'Password' => md5($Password)
                                                        ]);
                            $array = session('DanhSachGVTam');
                            $position = array_search($temp, $array);
                            unset($array[$position]);
                            session(['DanhSachGVTam' => $array]);
                            return redirect()->to('/quan-ly-gv')->with('success-Add-T','Reset dữ liệu tài khoản '.$MSGVCut.' thành công');
                        }
                    }
                    //Nếu tồn tại tk và người dùng đã qua 6 tháng chưa quay lại
                    if($checkUserIsActive->MSGV != null && Carbon::now()->greaterThan(Carbon::parse($checkUserIsActive->LastActive)->addMonths(6)) == true)
                    {
                        //Khôi phục người dùng
                        $upDateLastActiveData = DB::table('giang_vien')->where('MSGV',$MSGVCut)
                                                                    ->update([
                                                                        'LastActive' => Carbon::now()->format('Y-m-d')
                                                                    ]);
                            $array = session('DanhSachGVTam');
                            $position = array_search($temp, $array);
                            unset($array[$position]);
                            session(['DanhSachGVTam' => $array]);
                        return redirect()->to('/quan-ly-gv')->with('success-Add-T','tái kích hoạt giảng viên '.$MSGVCut.' thành công');
                    }
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
        if(session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
        {
            return view('admin/class-add');

        }
        else
        {
            return redirect()->to("/");
        }
    }

    public function ThemLop(Request $request)
    {
        // dd(preg_match('/[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]/',$request->startYears).'-'.preg_match('/[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]/',$request->endYears).'-'.preg_match('/[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]/',$request->KhoaHoc));
        // dd(preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]/',$request->classid));
        $validated = $request->validate([
            'KhoaHoc' => 'required',
            'startYears' => 'required',
            'endYears' => 'required',
            'classid' => 'required'
        ]);

        if($request != null)
        {
            if(preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]/',$request->classid) == 1)
            {
                return redirect()->to('/them-lop-nien-khoa')->with('error-Add-C',' Lỗi nhập thông tin lớp ')->withInput();
            }
            if( preg_match('/[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]/',$request->startYears) == 1)
            {
                    // dd( preg_match('/^[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]*$/',$request->startYears));
                    return redirect()->to('/them-lop-nien-khoa')->with('error-Add-C',' Lỗi nhập năm học bắt đầu ')->withInput();
            }
            if(preg_match('/[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]/',$request->endYears) == 1)
            {
                    // dd( preg_match('/^[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]*$/',$request->startYears));
                    return redirect()->to('/them-lop-nien-khoa')->with('error-Add-C',' Lỗi nhập năm học kết thúc')->withInput();
            }
            if(preg_match('/[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]/',$request->KhoaHoc) == 1)
            {
                    // dd( preg_match('/^[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]*$/',$request->startYears));
                    return redirect()->to('/them-lop-nien-khoa')->with('error-Add-C',' Lỗi nhập Niên khóa')->withInput();
            }

            //Kiểm tra tồn tại lớp hay chưa
            $checkLopIsAvailable = DB::table('lop')->where('MaLop',$request->classid)->first();
            if($checkLopIsAvailable != null)
            {
                return redirect()->to('/them-lop-nien-khoa')->with('error-Add-C','Đã tồn tại lớp '.$request->classid)->withInput();
            }


            $temp = 'Lop'.$request->classid.'KHOAHOC'.$request->KhoaHoc.'year'.$request->startYears.'-'.$request->endYears;
            // dd($temp);
            session()->push('DanhSachLopNKTam',$temp);
            return redirect()->to('/them-lop-nien-khoa');
        }
        else
        {
            return redirect()->to('/them-lop-nien-khoa')->with('error-Add-C',' Lỗi nhập liệu ')->withInput();
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

    public function FrmAddClassManage()
    {
        if(session()->has('teacherid'))
        {
            return view('admin/class-manager-add');

        }

    }

    public function ConfirmAddClassManage(Request $request)
    {
        // dd($request);
        if($request->student_info_MSSV != null && $request->student_info_Student_Name != null
        && $request->student_info_Birthday != null && $request->student_info_Class != null)
        {
            $i=0;
            $limit = count($request->student_info_MSSV);
            while($i<$limit)
            {
                if($request->student_info_MSSV[$i] != null && $request->student_info_Student_Name[$i] != null
                    && $request->student_info_Birthday[$i] != null && $request->student_info_Class[$i] != null)
                {
                    $MaLop = $request->student_info_Class[$i];
                    //Kiểm tra sinh viên có tồn tại hay không
                    $checkSVIsAvailable = DB::table('sinh_vien')->where('MSSV',$request->student_info_MSSV[$i])->first();
                    if($checkSVIsAvailable != null)
                    {
                        //Kiểm tra xem lớp đó đã tồn tại Ban cán sự chưa
                        $CheckManageOfClassIsAvailable = DB::table('sinh_vien')->where('MaLop', $MaLop )
                        ->where('BanCanSu',1)->first();
                        if($CheckManageOfClassIsAvailable == null)
                        {
                            //Chưa có ban cán sự => thêm mới
                            $AddClassManage = DB::table('sinh_vien')->where('MSSV',$request->student_info_MSSV[$i])->where('MaLop', $MaLop )
                                            ->update([
                                                'BanCanSu' => 1
                                            ]);
                        }
                        else
                        {
                            return redirect()->back()->with('error-inputLeader','Lớp '.$MaLop.' đã có ban cán sự!')->withInput();
                        }

                    }
                    else
                    {
                        return redirect()->back()->with('error-inputLeader','Không tồn tại sinh viên '.$request->student_info_MSSV[$i].' - '.$request->student_info_Student_Name[$i])->withInput();
                    }
                }
                $i++;
            }
            return redirect()->back()->with('success-AddLeader','Đã thêm toàn bộ danh sách')->withInput();;
        }
        else
        {

            return redirect()->back();
        }

    }

    public function GetAllTeacherList()
    {
        if(session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
        {
            $getAllTeacher = DB::table('giang_vien')->join('chuc_vu','chuc_vu.MaChucVu','=','giang_vien.MaChucVu')
            ->join('khoa','khoa.MaKhoa','=','giang_vien.MaKhoa')
            ->where('giang_vien.MaChucVu','!=','AM')
            ->paginate(15);
            return view('admin/teacher-list',['listTeacher' => $getAllTeacher]);
        }
        else
        {
            return redirect()->to("/");
        }
    }
    public function ResetFindTeacherList()
    {
        return redirect()->to('/teacher-list');
    }
     public function FindTeacherFromList(Request $request)
     {

        if (session()->has('teacherid')) {
            if($request->teachername != null)
            {
                $searchlist = DB::table('giang_vien')->join('chuc_vu','chuc_vu.MaChucVu','=','giang_vien.MaChucVu')
                ->join('khoa','khoa.MaKhoa','=','giang_vien.MaKhoa')
                ->where('giang_vien.MaChucVu','!=','AM')
                ->when($request->teachername != null, function ($query) use ($request) {
                    return $query->where('HoTenGV', 'like', '%' . $request->teachername . '%');
                }) ->paginate(15);
            }
            else if($request->msgv != null)
            {
                $searchlist = DB::table('giang_vien')->join('chuc_vu','chuc_vu.MaChucVu','=','giang_vien.MaChucVu')
                ->join('khoa','khoa.MaKhoa','=','giang_vien.MaKhoa')
                ->where('giang_vien.MaChucVu','!=','AM')
                ->when($request->msgv != null, function ($query) use ($request) {
                    return $query->where('MSGV', 'like', '%' .$request->msgv. '%');
                }) ->paginate(15);
            }
            else if($request->msgv != null && $request->teachername != null)
            {
                $searchlist = DB::table('giang_vien')->join('chuc_vu','chuc_vu.MaChucVu','=','giang_vien.MaChucVu')
                ->join('khoa','khoa.MaKhoa','=','giang_vien.MaKhoa')
                ->where('giang_vien.MaChucVu','!=','AM')
                ->when($request->mssv != null, function ($query) use ($request) {
                    return $query->where('MSGV','like', '%' . $request->msgv. '%');
                })
                ->when($request->teachername != null, function ($query) use ($request) {
                    return $query->join('sinh_vien', 'sinh_vien.MSSV', 'danh_sach_sinh_vien.MSSV')
                        ->where('HoTenGV', 'like', '%' . $request->teachername . '%');
                }) ->paginate(15);
            }
            else
            {
                if($request->teachername == null && $request->msgv != null)
                {
                    return redirect()->back()
                    ->with('errorClassList1','Lớp không tồn tại đối tượng với mã số '.$request->msgv)->withInput();
                }
                elseif($request->teachername != null && $request->msgv == null)
                {
                    return redirect()->back()
                    ->with('errorClassList1','Lớp không tồn tại đối tượng '.$request->teachername)->withInput();
                }
                elseif($request->teachername == null && $request->msgv == null)
                {
                    return redirect()->back()
                    ->with('errorClassList1','Không có đối tượng tìm kiếm!')->withInput();
                }

            }

            //Kiểm tra tìm kiếm có rỗng không

            $checkTemp = [];
            foreach( $searchlist as $ResultData)
            {
                $checkTemp = $ResultData;
            }
            if($checkTemp == null)
            {
                if($request->teachername == null && $request->msgv != null)
                {
                    return redirect()->back()
                    ->with('errorClassList1','Lớp không tồn tại đối tượng với mã số '.$request->msgv)->withInput();
                }
                elseif($request->teachername != null && $request->msgv == null)
                {
                    return redirect()->back()
                    ->with('errorClassList1','Lớp không tồn tại đối tượng '.$request->teachername)->withInput();
                }
                elseif($request->teachername == null && $request->msgv == null)
                {
                    return redirect()->back()
                    ->with('errorClassList1','Không có đối tượng tìm kiếm!')->withInput();
                }
            }
            return view('admin/teacher-list',['listTeacher' => $searchlist]);
        }
        else{
            return redirect()->to('/');
        }

     }

     //Danh sách tất cả sinh viên
     public function GetAllStudentList()
     {
         if(session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
         {
             $getAllStudent = DB::table('sinh_vien')
             ->paginate(15);
             return view('admin/all-student-list',['listStudent' => $getAllStudent]);
         }
         else
         {
             return redirect()->to("/");
         }
     }
    public function ResetFindStudentList()
     {
         return redirect()->to('/all-student-list');
     }

    public function FindStudentFromList(Request $request)
     {
        if (session()->has('teacherid')) {
                if($request->studentname != null)
                {
                    $searchlist = DB::table('sinh_vien')
                    ->when($request->studentname != null, function ($query) use ($request) {
                        return $query->where('sinh_vien.HoTenSV', 'like', '%' . $request->studentname . '%');
                    }) ->paginate(15);
                }
                else if($request->mssv != null)
                {
                    $searchlist =  DB::table('sinh_vien')
                    ->when($request->mssv != null, function ($query) use ($request) {
                        return $query->where('MSSV', 'like', '%' .$request->mssv. '%');
                    }) ->paginate(15);
                }
                else if($request->mssv != null && $request->studentname != null)
                {
                    $searchlist =  DB::table('sinh_vien')
                    ->when($request->mssv != null, function ($query) use ($request) {
                        return $query->where('MSSV','like', '%' . $request->mssv. '%');
                    })
                    ->when($request->studentname != null, function ($query) use ($request) {
                        return $query->where('HoTenSV', 'like', '%' . $request->studentname . '%');
                    }) ->paginate(15);
                }
                else
                {
                    if($request->studentname == null && $request->mssv != null)
                    {
                        return redirect()->to('/all-student-list')
                        ->with('errorClassList1','Lớp không tồn tại đối tượng với mã số '.$request->mssv)->withInput();
                    }
                    elseif($request->studentname != null && $request->mssv == null)
                    {
                        return redirect()->to('/all-student-list')
                        ->with('errorClassList1','Lớp không tồn tại đối tượng '.$request->studentname)->withInput();
                    }
                    elseif($request->studentname == null && $request->mssv == null)
                    {
                        return redirect()->to('/all-student-list')
                        ->with('errorClassList1','Không có đối tượng tìm kiếm!')->withInput();
                    }

                }

                //Kiểm tra tìm kiếm có rỗng không

                $checkTemp = [];
                foreach( $searchlist as $ResultData)
                {
                    $checkTemp = $ResultData;
                }
                if($checkTemp == null)
                {
                    if($request->studentname == null && $request->mssv != null)
                    {
                        return redirect()->to('/all-student-list')
                        ->with('errorClassList1','Lớp không tồn tại đối tượng với mã số '.$request->mssv)->withInput();
                    }
                    elseif($request->studentname != null && $request->mssv == null)
                    {
                        return redirect()->to('/all-student-list')
                        ->with('errorClassList1','Lớp không tồn tại đối tượng '.$request->studentname)->withInput();
                    }
                    elseif($request->studentname == null && $request->mssv == null)
                    {
                        return redirect()->to('/all-student-list')
                        ->with('errorClassList1','Không có đối tượng tìm kiếm!')->withInput();
                    }
                }
                return view('admin/all-student-list', ['listStudent' => $searchlist]);
            }
            else{
                return redirect()->to('/');
            }
     }


}
