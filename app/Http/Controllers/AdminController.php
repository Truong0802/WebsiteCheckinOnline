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

    public function frmAddSV()
    {
        if(session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
        {
            return view('admin/student-add');
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
            'password' => 'required'
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
                    session()->put('PassWord',$request->password);
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
        if(session()->get('DanhSachTam') != null)
        {
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

                try
                {
                    //Kiểm tra thời gian bắt đầu có phù hợp
                    $time = Carbon::parse($request->timestart);
                    $formattedTime = $time->format('dmYHi');
                    $timeForTemp = $time->format('d-m-Y H:i');
                    //Kiểm tra thời gian kết thúc có phù hợp
                    $Anothertime = Carbon::parse($request->timeend);
                    $formattedTimeEnd = $Anothertime->format('dmYHi');
                    $timeEndForTemp = $Anothertime->format('d-m-Y H:i');

                    $SubjectInfo = DB::table('mon_hoc')->where('MaTTMH',$request->subjectname)->first();
                    $temp= $timeForTemp.' MaMH'.$request->subjectname.'TenMH '.$SubjectInfo->TenMH.'Lop'.$request->classname.'GV'.$request->teacherid.'TimeEnd'.$timeEndForTemp.'HK'.$request->HKid;
                    // dd($CutClass);
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
        // dd($position);
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
                    $checkTypeTime = DB::table('tiet_hoc')->where('ThoiGianBatDau',$formatTime)->first();
                    if($checkTypeTime != null)
                    {
                        $formatCheckDate1 = $dateEndConvert->format('d-m-y');
                        $formatCheckDate = $datetimeConvert->format('d-m-y');

                        if( $formatCheckDate1 != $formatCheckDate)
                        {
                            return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Thời gian diễn ra tiết học quá xa')->withInput();
                        }

                        $formatTimeEnd = $dateEndConvert->format('H:i');
                        $checkTypeTimeEnd = DB::table('tiet_hoc')
                                            ->where('ThoiGianKetThuc',$formatTimeEnd)->first();
                        if($checkTypeTimeEnd == null )
                        {
                            return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Không tồn tại tiết học này')->withInput();
                        }



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
                            // dd($formatTimeEnd);
                            // dd($ex);
                            return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Lỗi nhập liệu danh sách'.' '.Str::between($temp,'MaMH','TenMH').'')->withInput();

                        }
                    }
                    else
                    {
                        return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Không tồn tại tiết học này')->withInput();
                    }

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
                        $checkTypeTime = DB::table('tiet_hoc')->where('ThoiGianBatDau',$formatTime)->first();
                        if($checkTypeTime != null)
                        {
                            $formatCheckDate1 = $dateEndConvert->format('d-m-y');
                            $formatCheckDate = $datetimeConvert->format('d-m-y');

                            if( $formatCheckDate1 != $formatCheckDate)
                            {
                                return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Thời gian diễn ra tiết học quá xa')->withInput();
                            }

                            $formatTimeEnd = $dateEndConvert->format('H:i');
                            $checkTypeTimeEnd = DB::table('tiet_hoc')
                                                ->where('ThoiGianKetThuc',$formatTimeEnd)->first();
                            if($checkTypeTimeEnd == null )
                            {
                                return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Không tồn tại tiết học này')->withInput();
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
                                //  dd($ex);
                                return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Lỗi nhập liệu lớp'.' '.Str::between($temp,'MaMH','TenMH').'')->withInput();

                            }
                        }
                        else
                        {
                            return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Không tồn tại tiết học này')->withInput();
                        }

                    }
                    else
                    {
                    //Thêm tiếp các buổi 2,3,4,5 theo stt lấy từ db +1
                        $stt = DB::table('lich_giang_day')->where('MaTTMH',$MaTTMH)->where('MaLop',$CutClass)->distinct()->count('MaNgay');
                        // dd(++$stt);
                        if($stt != 0)
                        {
                            ++$stt;
                        }else
                        {
                            $array = session('DanhSachLopTam');
                            $position = array_search($temp,$array);
                            $stt= $position+1;
                            //Lấy stt buổi
                        }

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
                                    'MaTietHoc' => $checkTypeTime->MaTietHoc,
                                    'MaBuoi' => $stt
                                ]);
                            }
                            catch(Exception $ex)
                            {
                                // dd($ex);
                                return redirect()->to('/quan-ly-lop-hoc')->with('error-AddClass','Lỗi nhập liệu danh sách lớp '.' '.Str::between($temp,'MaMH','TenMH').' đã tồn tại')->withInput();

                            }

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
        if(session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
        {
            return view('admin/student-add-to-class');
        }
        else
        {
            return redirect()->to("/");
        }

    }

    public function addStudentToClassBack(Request $request)
    {
        // $limit = count($request->mssv);
        dd($request);
    }

//Thêm danh sách
    public function frmAddStudentList(Request $request)
    {
        if(session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
        {
            if(session()->has('classAddId'))
            {
                if( session()->get('classAddId') != $request->lop)
                {

                    if(session()->has('DanhSachSinhVienTam'))
                    {
                        session()->forget('DanhSachSinhVienTam');
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
                    // dd($checkSVisAvailable);
                if($checkSVisAvailable == null)
                {
                    return redirect()->to('/quan-ly-sinh-vien')->with('error-Add','Không tồn tại sinh viên mã'.$request->mssv)->withInput();
                }



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
        // dd($request->id);
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
        else
        {
            return redirect()->to('/Them-danh-sach-sv?lop='.session()->get('classAddId').'&HK='.session()->get('HKid'))->with('error-AddDSSV','Không tồn tại danh sách cần xác nhận')->withInput();
        }
    }

    public function FrmThemGV()
    {
        if(session()->get('ChucVu') == 'AM' || session()->get('ChucVu') == 'QL')
        {
            return view('admin/teacher-add');
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
}
