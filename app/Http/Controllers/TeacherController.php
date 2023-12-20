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
use hisorange\BrowserDetect\Parser as Browser;
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
            if(session()->has('clockUp') && Carbon::now()->greaterThan(Carbon::parse(session()->get('clockUp'))) == true)
            {

                          return redirect()->action([
                              AccountController::class,
                              'logout'
                          ]);

           }

            if(session()->exists('teacherid')){
                if($request->lop){
                    $teacherid = session()->get('teacherid');
                    $allsubject = DB::table('lich_giang_day')
                    ->where('MSGV',$teacherid)
                    ->where('MaTTMH',$request->lop)
                    ->latest('NgayDay')
                    ->distinct()->paginate(15);
                }
                else{
                    if(session()->get('ChucVu') == 'QL' || session()->get('ChucVu') == 'AM')
                    {
                        $teacherid = session()->get('teacherid');
                        $allsubject = DB::table('lich_giang_day')->where('MaBuoi',1)->latest('NgayDay')->paginate(15);
                    }
                    else
                    {
                        $teacherid = session()->get('teacherid');
                        $allsubject = DB::table('lich_giang_day')->where('MSGV',$teacherid)->where('MaBuoi',1)->latest('NgayDay')->distinct()->paginate(5);
                    }
                    //Thêm điều kiện else cho trường hợp quản lý truy cập sẽ lọc ra những lớp thuộc khoa của quản lý đó
                }


                return view('Teacher/class-list',['getallsubject'=>$allsubject]);
            }
            else{
                if(session()->exists('studentid'))
                {

                    $checkLeaderIs = DB::table('sinh_vien')->where('MSSV',session()->get('studentid'))->first();
                    if($checkLeaderIs->BanCanSu != null)
                    {
                        $MaLop = $checkLeaderIs->MaLop;
                        $allsubject = DB::table('lich_giang_day')
                            ->select('lich_giang_day.*', DB::raw('CASE WHEN EXISTS (SELECT 1 FROM danh_sach_sinh_vien
                                JOIN sinh_vien ON danh_sach_sinh_vien.MSSV = sinh_vien.MSSV
                                WHERE sinh_vien.MaLop = "'.$checkLeaderIs->MaLop.'" AND danh_sach_sinh_vien.MaTTMH = lich_giang_day.MaTTMH
                                    AND danh_sach_sinh_vien.MaHK = lich_giang_day.MaHK
                                    AND danh_sach_sinh_vien.MSGV = lich_giang_day.MSGV
                                    AND lich_giang_day.MaBuoi = 1) THEN "Yes" ELSE "No" END AS ExistsInLop')) //Truyền tham số MaLop vào vị trí ? trên
                            ->whereExists(function ($query) use ($MaLop) {
                                $query->select(DB::raw(1))
                                    ->from('danh_sach_sinh_vien')
                                    ->join('sinh_vien', function ($join) use ($MaLop) {
                                        $join->on('danh_sach_sinh_vien.MSSV', '=', 'sinh_vien.MSSV')
                                            ->where('sinh_vien.MaLop', '=', $MaLop);
                                    })
                                    ->whereColumn('danh_sach_sinh_vien.MaTTMH', '=', 'lich_giang_day.MaTTMH')
                                    ->whereColumn('danh_sach_sinh_vien.MaHK', '=', 'lich_giang_day.MaHK')
                                    ->whereColumn('danh_sach_sinh_vien.MSGV', '=', 'lich_giang_day.MSGV')
                                    ->where('lich_giang_day.MaBuoi', '=', 1);
                            })
                            ->latest('MaHK')
                            ->paginate(15);
                    }
                    else
                    {
                        $allsubject = DB::table('danh_sach_sinh_vien')
                        ->join('lich_giang_day',function ($join){
                            $join->on('danh_sach_sinh_vien.MaTTMH','=','lich_giang_day.MaTTMH')
                                ->on('danh_sach_sinh_vien.MaHK','=','lich_giang_day.MaHK');
                        })
                        ->where('lich_giang_day.MaBuoi',1)
                        ->where(function ($query) {
                            //Có hoặc không làm Ban cán sự
                                $query->whereNotNull('danh_sach_sinh_vien.BanCanSuLop')
                                    ->orWhereNull('danh_sach_sinh_vien.BanCanSuLop');
                            })
                        ->where('danh_sach_sinh_vien.MSSV',session()->get('studentid'))
                        ->distinct()->paginate(15);
                    }


                    return view('Teacher/class-list',['getallsubject'=>$allsubject]);



                }
                return redirect()->to('/');
            }
        }

        public function timkiem(Request $request)
        {

            if(session()->exists('teacherid') && session()->get('ChucVu') == 'GV'){
                // $teacherid = DB::table('giang_vien')->where('HoTenGV', $request->lecturename)->first();
                // $subjectname = DB::table('mon_hoc')->where('TenMH', $request->subjectname)->first();
                // $coursename = DB::table('hoc_ky')->where('', $request->coursename)->first();
                $courselist = DB::table('khoa_hoc')->where('KhoaHoc', $request->courselist)->first();
                if($request->lecturename == null && $request->subjectname == null &&  $request->coursename == null
                    &&$request->courselist == null && $request->classname == null)
                {
                    return redirect()->to('/danh-sach-lop')->with('errorClass1','Tìm kiếm rỗng!')->withInput();
                }
                    $allsubject = DB::table('lich_giang_day')->where('MSGV',session()->get('teacherid'))->where('MaBuoi',1)
                    ->when($request->subjectname, function ($query) use ($request) {
                        return $query->join('mon_hoc', 'mon_hoc.MaTTMH', '=', 'lich_giang_day.MaTTMH')
                        ->where('mon_hoc.TenMH', 'like', '%'.$request->subjectname.'%')->distinct();
                    })
                    ->when($request->coursename, function ($query) use ($request) {
                        // $class = DB::table('lop')->where('KhoaHoc', $request->coursename)->first();
                        return $query->where('MaHK','like','%'.$request->coursename)->distinct();
                    })
                    ->when($courselist, function ($query) use ($courselist) {
                        $findclass = DB::table('lop')->where('KhoaHoc', $courselist->KhoaHoc)->first();
                        return $query->where('MaLop', $findclass->MaLop);
                    })
                    ->when($request->classname, function ($query) use ($request) {
                        return $query->where('MaLop','like','%'.$request->classname.'%')->distinct();
                    })
                    ->latest('NgayDay')
                    ->paginate(15);


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
            else if(session()->exists('teacherid') && session()->get('ChucVu') == 'AM')
            {
                // $teacherid = DB::table('giang_vien')->where('HoTenGV', $request->lecturename)->first();
                // $subjectname = DB::table('mon_hoc')->where('TenMH', $request->subjectname)->first();
                $coursename = DB::table('khoa_hoc')->where('KhoaHoc', $request->coursename)->first();
                $courselist = DB::table('khoa_hoc')->where('KhoaHoc', $request->courselist)->first();
                if($request->lecturename == null && $request->subjectname == null &&  $request->coursename == null
                &&$request->courselist == null && $request->classname == null)
                {
                    return redirect()->to('/danh-sach-lop')->with('errorClass1','Tìm kiếm rỗng!')->withInput();
                }
                    $allsubject = DB::table('lich_giang_day')->where('MaBuoi',1)
                    ->when($request->lecturename, function ($query) use ($request) {
                        return $query->join('giang_vien', 'giang_vien.MSGV', '=', 'lich_giang_day.MSGV')
                                ->where('giang_vien.HoTenGV', 'like', '%'.$request->lecturename.'%')->distinct();
                    })
                    ->when($request->subjectname, function ($query) use ($request) {
                        return $query->join('mon_hoc', 'mon_hoc.MaTTMH', '=', 'lich_giang_day.MaTTMH')
                        ->where('mon_hoc.TenMH', 'like', '%'.$request->subjectname.'%')->distinct();
                    })
                    ->when($coursename, function ($query) use ($coursename) {
                        $class = DB::table('lop')->where('KhoaHoc', $coursename->KhoaHoc)->first();
                        return $query->where('MaLop', $class->MaLop)->distinct();
                    })
                    ->when($courselist, function ($query) use ($courselist) {
                        $findclass = DB::table('lop')->where('KhoaHoc', $courselist->KhoaHoc)->first();
                        return $query->where('MaLop', $findclass->MaLop);
                    })
                    ->when($request->classname, function ($query) use ($request) {
                        return $query->where('MaLop','like','%'.$request->classname.'%')->distinct();
                    })
                    ->latest('NgayDay')
                    ->paginate(15);

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
            else if(!session()->exists('teacherid'))
            {
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
            if(session()->has('clockUp') && Carbon::now()->greaterThan(Carbon::parse(session()->get('clockUp'))) ==  true)
            {

                          return redirect()->action([
                              AccountController::class,
                              'logout'
                          ]);

           }
            if(session()->exists('teacherid')){

                if($request->lop){
                    $classlist = DB::table('danh_sach_sinh_vien')->where('MaTTMH',$request->lop)->where('MaHK',$request->HK)->orderby('MaKQSV','ASC')->distinct()->paginate(150);
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
                        return redirect()->back()->with('errorClass1','Lớp chưa có danh sách!')->withInput();
                        // return redirect()->to('/trang-chu');
                    }

                }


            }
            else{
                if(session()->exists('studentid'))
                {
                    //Kiểm tra trong lớp có sinh viên đang truy cập hay không
                    $getMaLop = DB::table('sinh_vien')->where('MSSV',session()->get('studentid'))->first();
                    if($getMaLop->BanCanSu != null || $getMaLop->BanCanSu != 0)
                    {
                        $checkLeader = DB::table('danh_sach_sinh_vien')
                        ->where('MaTTMH',$request->lop)->where('MaHK',$request->HK)
                        ->where('MSSV',session()->get('studentid'))->first();
                        if($checkLeader != null)
                        {
                            if($checkLeader->BanCanSuLop != null) //Nếu ban cán sự lớp tổng cũng là ban cán sự lớp môn của 1 môn nào đó
                            {
                                //Cho phép thấy tất cả thành viên trong lớp
                                $classlist= DB::table('danh_sach_sinh_vien')
                                ->join('sinh_vien', 'danh_sach_sinh_vien.MSSV', '=', 'sinh_vien.MSSV')
                                ->where('danh_sach_sinh_vien.MaTTMH',$request->lop)
                                ->where('danh_sach_sinh_vien.MaHK', $request->HK)->orderby('MaKQSV','ASC')->paginate(25);
                                session()->put('danh-sach-sinh-vien-lop',$request->lop);
                                session()->put('HKid',$request->HK);
                            }
                            else
                            {
                                //Nếu ban cán sự lớp tổng không phải ban cán sự lớp môn
                                $classlist= DB::table('danh_sach_sinh_vien')
                                ->join('sinh_vien', 'danh_sach_sinh_vien.MSSV', '=', 'sinh_vien.MSSV')
                                // set chỉ có bcs xem sinh viên thuộc lớp mình
                                ->where('sinh_vien.MaLop', $getMaLop->MaLop)
                                ->where('danh_sach_sinh_vien.MaTTMH',$request->lop)
                                ->where('danh_sach_sinh_vien.MaHK', $request->HK)->orderby('MaKQSV','ASC')->paginate(25);
                                session()->put('danh-sach-sinh-vien-lop',$request->lop);
                                session()->put('HKid',$request->HK);
                            }
                        }
                        else //Nếu sinh viên có chức ban cán sự lớp tổng không học trong lớp môn đó
                        {
                            //Thực hiện chức năng thông thường của ban cán sự lớp niên khóa
                            $classlist= DB::table('danh_sach_sinh_vien')
                            ->join('sinh_vien', 'danh_sach_sinh_vien.MSSV', '=', 'sinh_vien.MSSV')
                            // set chỉ có bcs xem sinh viên thuộc lớp mình
                            ->where('sinh_vien.MaLop', $getMaLop->MaLop)
                            ->where('danh_sach_sinh_vien.MaTTMH',$request->lop)
                            ->where('danh_sach_sinh_vien.MaHK', $request->HK)->orderby('MaKQSV','ASC')->paginate(25);
                            session()->put('danh-sach-sinh-vien-lop',$request->lop);
                            session()->put('HKid',$request->HK);
                        }
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
                    else
                    {
                        $checkLeader = DB::table('danh_sach_sinh_vien')
                        ->where('MaTTMH',$request->lop)->where('MaHK',$request->HK)
                        ->where('MSSV',session()->get('studentid'))->first();
                        if($checkLeader->BanCanSuLop != null)
                        {

                            $classlist= DB::table('danh_sach_sinh_vien')
                            ->join('sinh_vien', 'danh_sach_sinh_vien.MSSV', '=', 'sinh_vien.MSSV')
                            //set chỉ có bcs xem sinh viên thuộc lớp mình
                            // ->where('sinh_vien.MaLop', $getMaLop->MaLop)
                            ->where('danh_sach_sinh_vien.MaTTMH',$request->lop)
                            ->where('danh_sach_sinh_vien.MaHK', $request->HK)->orderby('MaKQSV','ASC')->paginate(25);
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
                        else
                        {
                            //Sinh viên thường -  chỉ xem được bản thân
                            $classlist = DB::table('danh_sach_sinh_vien')
                            ->where('MaTTMH',$request->lop)->where('MaHK',$request->HK)
                            ->where('MSSV',session()->get('studentid'))
                            ->distinct()->orderby('MaKQSV','ASC')->paginate(25);
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


                }
                else
                {
                    return redirect()->to('/');
                }

            }

        }
        public function timkiemsinhvien(Request $request)
        {

            if (session()->has('teacherid')) {
                if($request->studentname != null)
                {
                    $searchlist = DB::table('danh_sach_sinh_vien')
                    ->where('danh_sach_sinh_vien.MaTTMH',session('danh-sach-sinh-vien-lop'))
                    ->where('danh_sach_sinh_vien.MaHK',session()->get('HKid'))
                    ->when($request->studentname != null, function ($query) use ($request) {
                        return $query->join('sinh_vien', 'sinh_vien.MSSV', 'danh_sach_sinh_vien.MSSV')
                            ->where('sinh_vien.HoTenSV', 'like', '%' . $request->studentname . '%');
                    }) ->paginate(15);
                }
                else if($request->mssv != null)
                {
                    $searchlist = DB::table('danh_sach_sinh_vien')
                    ->where('danh_sach_sinh_vien.MaTTMH',session('danh-sach-sinh-vien-lop'))
                    ->where('danh_sach_sinh_vien.MaHK',session()->get('HKid'))
                    ->when($request->mssv != null, function ($query) use ($request) {
                        return $query
                        ->where('danh_sach_sinh_vien.MSSV', 'like', '%' .$request->mssv. '%');
                    }) ->paginate(15);
                }
                else if($request->mssv != null && $request->studentname != null)
                {
                    $searchlist = DB::table('danh_sach_sinh_vien')
                    ->where('danh_sach_sinh_vien.MaTTMH',session('danh-sach-sinh-vien-lop'))
                    ->where('danh_sach_sinh_vien.MaHK',session()->get('HKid'))
                    ->when($request->mssv != null, function ($query) use ($request) {
                        return $query
                        ->where('danh_sach_sinh_vien.MSSV','like', '%' . $request->mssv. '%');
                    })
                    ->when($request->studentname != null, function ($query) use ($request) {
                        return $query->join('sinh_vien', 'sinh_vien.MSSV', 'danh_sach_sinh_vien.MSSV')
                            ->where('sinh_vien.HoTenSV', 'like', '%' . $request->studentname . '%');
                    }) ->paginate(15);
                }
                else
                {
                    if($request->studentname == null && $request->mssv != null)
                    {
                        return redirect()->to('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))
                        ->with('errorClassList1','Lớp không tồn tại đối tượng với mã số '.$request->mssv)->withInput();
                    }
                    elseif($request->studentname != null && $request->mssv == null)
                    {
                        return redirect()->to('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))
                        ->with('errorClassList1','Lớp không tồn tại đối tượng '.$request->studentname)->withInput();
                    }
                    elseif($request->studentname == null && $request->mssv == null)
                    {
                        return redirect()->to('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))
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
                        return redirect()->to('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))
                        ->with('errorClassList1','Lớp không tồn tại đối tượng với mã số '.$request->mssv)->withInput();
                    }
                    elseif($request->studentname != null && $request->mssv == null)
                    {
                        return redirect()->to('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))
                        ->with('errorClassList1','Lớp không tồn tại đối tượng '.$request->studentname)->withInput();
                    }
                    elseif($request->studentname == null && $request->mssv == null)
                    {
                        return redirect()->to('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))
                        ->with('errorClassList1','Không có đối tượng tìm kiếm!')->withInput();
                    }
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

                $checkUserIsActive = DB::table('giang_vien')->where('MSGV',session()->get('teacherid'))->first();
                if($checkUserIsActive->LastActive == null || Carbon::now()->greaterThan(Carbon::parse($checkUserIsActive->LastActive)->addMonths(6)) == false)
                {
                    $upDateLastActiveData = DB::table('giang_vien')->where('MSGV',session()->get('teacherid'))
                        ->update([
                            'LastActive' => Carbon::now()->format('Y-m-d')
                        ]);
                }


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

                // dd($request);
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
                            // dd($data);
                            $datafromdb = decrypt($findPath->URL);
                        }
                        catch(Exception $ex)
                        {
                            return back()->with('error2','Sai đường dẫn')->withInput();
                        }
                            $findlistidofstudent = DB::table('danh_sach_sinh_vien')
                            // ->where('MaTTMH',session()->get('danh-sach-sinh-vien-lop'))
                            ->where('MaTTMH',$data["lop"])->where('MaHK',$data["HK"])
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


                                        //Đã điểm danh trước đó/1 tk
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
                                                //Nếu mã lớp và học kỳ của path giống với db trước đó lưu
                                                if( $data["lop"] == $datafromdb["lop"] && $data["HK"] == $datafromdb["HK"])
                                                {
                                                                                                        if(session()->has('checked'))
                                                    {
                                                        //Kiểm tra hiện tại đã vượt quá thời gian điểm danh hay chưa
                                                        if(Carbon::now()->greaterThan(Carbon::parse(session()->get('checked'))) == true)
                                                        {
                                                            session()->forget('checked');
                                                            //Điểm danh
                                                            $studentchecked = DB::table('diem_danh')->insert([
                                                                'MaDanhSach' => $findlistidofstudent->MaDanhSach,
                                                                'MaBuoi' => $data["buoi"],
                                                                'NgayDiemDanh' => $timecheckin,
                                                                "IpAddress" => $request->ip(),
                                                                "Browser" => Browser::browserName()
                                                            ]);

                                                            $checkUserIsActive = DB::table('sinh_vien')->where('MSSV',session()->get('studentid'))->first();
                                                            if($checkUserIsActive->LastActive == null || Carbon::now()->greaterThan(Carbon::parse($checkUserIsActive->LastActive)->addMonths(6)) == false)
                                                            {
                                                                $upDateLastActiveData = DB::table('sinh_vien')->where('MSSV',session()->get('studentid'))
                                                                                        ->update([
                                                                                            'LastActive' => Carbon::now()->format('Y-m-d')
                                                                                        ]);
                                                            }
                                                            return redirect()->to('/trang-chu')->with('success1','Điểm danh thành công')->withInput();
                                                        }
                                                        else
                                                        {
                                                            //Nếu thiết bị từng điểm danh trước đó chưa qua thời gian điểm danh
                                                            return back()->with('error2','Không được điểm danh 2 lần trên 1 thiết bị!')->withInput();
                                                        }
                                                    }
                                                    else{
                                                        //Kiểm tra xem thiết bị có từng sử dụng
                                                        $CheckIsCheating = DB::table('diem_danh')->where('IpAddress',$request->ip())->latest('NgayDiemDanh')->first();
                                                        if($CheckIsCheating == null)
                                                        {
                                                            //Nếu chưa từng tồn tại
                                                            //Gán vào session thời gian kết thúc buổi điểm danh
                                                            session()->put('checked',Carbon::parse($findPath->TimeOpenLink)->addMinutes(5));
                                                            //Điểm danh
                                                            $studentchecked = DB::table('diem_danh')->insert([
                                                                'MaDanhSach' => $findlistidofstudent->MaDanhSach,
                                                                'MaBuoi' => $data["buoi"],
                                                                'NgayDiemDanh' => $timecheckin,
                                                                "IpAddress" => $request->ip(),
                                                                "Browser" => Browser::browserName()
                                                            ]);
                                                            return redirect()->to('/trang-chu')->with('success1','Điểm danh thành công')->withInput();
                                                        }
                                                        else
                                                        {
                                                            //Thuật toán Ban 5 phút sau mỗi lần điểm danh trên thiết bị
                                                            //Nếu thiết bị từng tồn tại điểm danh
                                                            if(Carbon::parse($CheckIsCheating->NgayDiemDanh)->isToday() == true)
                                                            {
                                                                //Nếu vẫn trong ngày
                                                                //
                                                                if(Carbon::parse($CheckIsCheating->NgayDiemDanh)->greaterThan(Carbon::parse($CheckIsCheating->NgayDiemDanh)->addMinutes(5)) == false)
                                                                {
                                                                    //Nếu vẫn chưa vượt quá 5 phút từ lần điểm danh trước đó
                                                                    if($CheckIsCheating != Browser::browserName())
                                                                    {
                                                                        //Nếu sử dụng ứng dụng khác với ứng dụng lần trước sử dụng
                                                                        session()->put('checked',Carbon::parse($findPath->TimeOpenLink)->addMinutes(5));
                                                                        return back()->with('error2','Không được điểm danh 2 lần trên 1 thiết bị!')->withInput();
                                                                    }
                                                                    else
                                                                    {
                                                                        //Nếu vẫn dùng ứng dụng lần trước sử dụng
                                                                        session()->put('checked',Carbon::parse($findPath->TimeOpenLink)->addMinutes(5));
                                                                        return back()->with('error2','Không được điểm danh 2 lần trên 1 thiết bị!')->withInput();
                                                                    }
                                                                }
                                                                else
                                                                {
                                                                    //Nếu đã qua 5phut - tiếp tục kiểm tra và cho điểm danh bình thường
                                                                    if(session()->has('checked'))
                                                                    {
                                                                        //Kiểm tra hiện tại đã vượt quá thời gian điểm danh hay chưa
                                                                        if(Carbon::now()->greaterThan(Carbon::parse(session()->get('checked'))) )
                                                                        {
                                                                            session()->forget('checked');
                                                                            //Điểm danh
                                                                            $studentchecked = DB::table('diem_danh')->insert([
                                                                                'MaDanhSach' => $findlistidofstudent->MaDanhSach,
                                                                                'MaBuoi' => $data["buoi"],
                                                                                'NgayDiemDanh' => $timecheckin,
                                                                                "IpAddress" => $request->ip(),
                                                                                "Browser" => Browser::browserName()
                                                                            ]);
                                                                            return redirect()->to('/trang-chu')->with('success1','Điểm danh thành công')->withInput();
                                                                        }
                                                                        else
                                                                        {
                                                                            //Nếu thiết bị từng điểm danh trước đó chưa qua thời gian điểm danh
                                                                            return back()->with('error2','Không được điểm danh 2 lần trên 1 thiết bị!')->withInput();
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                        }


                                                    }
                                                }
                                                else if( $data["lop"] != $datafromdb["lop"])
                                                {
                                                    //Khác với lớp điểm danh trước đó, làm mới hoàn toàn

                                                            //Điểm danh

                                                            $studentchecked = DB::table('diem_danh')->insert([
                                                                'MaDanhSach' => $findlistidofstudent->MaDanhSach,
                                                                'MaBuoi' => $data["buoi"],
                                                                'NgayDiemDanh' => $timecheckin,
                                                                "IpAddress" => $request->ip(),
                                                                "Browser" => Browser::browserName()
                                                            ]);
                                                            return redirect()->to('/trang-chu')->with('success1','Điểm danh thành công')->withInput();

                                                }

                                            }
                                            else{
                                                if(session()->has('error2'))
                                                {
                                                    session()->forget('error2');
                                                }
                                                else{

                                                    // session()->forget('checked');
                                                    return back()->with('error2','Điểm danh thất bại')->withInput();
                                                }
                                            }
                                        }


                                    }
                                    catch(\Illuminate\Database\QueryException $exception)
                                    {

                                        return back()->with('error',$exception->getMessage())->withInput();
                                    }

                                }
                                elseif( $diff->i > 5){
                                    if(session()->has('error2'))
                                    {
                                        session()->forget('error2');
                                    }
                                    else{
                                        //Hết giờ điểm danh, xóa trực tiếp
                                        session()->forget('checked');
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
                $findlop = DB::table('danh_sach_sinh_vien')->where('MaTTMH', session()->get('danh-sach-sinh-vien-lop'))
                    ->where('MaHK',session()->get('HKid'))->distinct()->first();

                $listUpToRow14 = DB::table('danh_sach_sinh_vien')->where('MaTTMH', session()->get('danh-sach-sinh-vien-lop'))
                ->where('MaHK',session()->get('HKid'))->distinct()->get();
                // dd($listUpToRow14);
                foreach($listUpToRow14 as $resultCheck)
                {

                    $countChecked = DB::table('diem_danh')->where('MaDanhSach',$resultCheck->MaDanhSach)->distinct()->count('MaBuoi');
                    // dd($countChecked);
                    $TongSoBuoiUpDate = DB::table('danh_sach_sinh_vien')
                            ->where('MaDanhSach',$resultCheck->MaDanhSach)
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['TongSoBuoi' => $countChecked]);
                    //Đối với môn thực hành
                    $phanloailop = substr($resultCheck->MaDanhSach,3,1);
                    //
                    if($phanloailop == '3')
                    {
                        $latestpoint = $countChecked * 3/6;
                        $latestpoint = round($latestpoint, 2);
                        $latestpoint = ceil($latestpoint * 10) / 10; //Làm tròn lên số gần nhất
                        // $latestpoint = round($latestpoint + 0.5, 0, PHP_ROUND_HALF_UP);
                        if($countChecked  >= 5)
                        {
                            $row14UpDate = DB::table('danh_sach_sinh_vien')
                            ->where('MaDanhSach',$resultCheck->MaDanhSach)
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['Diem14' => $latestpoint]);

                            $checkDQT = DB::table("ket_qua")->where('MaKQSV',$resultCheck->MaKQSV)->first();
                            if($checkDQT != null)
                            {

                                if($findlop->Diem16 != null)
                                {
                                    $result = $latestpoint + $findlop->Diem16;

                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$resultCheck->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }
                                else{

                                    $result = $latestpoint;

                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$resultCheck->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }

                            }

                        }
                    }
                    else if($phanloailop == '1' || $phanloailop == '2')
                    {
                            //Đối với môn lý thuyết
                        $latestpoint = $countChecked * 3/9;
                        $latestpoint = round($latestpoint, 2);
                        $latestpoint = ceil($latestpoint * 10) / 10; //Làm tròn lên số gần nhất
                        // dd($latestpoint);
                        if($countChecked  >= 7)
                        {

                            $row14UpDate = DB::table('danh_sach_sinh_vien')
                            ->where('MaDanhSach',$resultCheck->MaDanhSach)
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['Diem14' => $latestpoint]);


                            $checkDQT = DB::table("ket_qua")->where('MaKQSV',$resultCheck->MaKQSV)->first();
                            if($checkDQT)
                            {
                                if($findlop->Diem16 != null)
                                {
                                    $result = $latestpoint + $resultCheck->Diem16;

                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$resultCheck->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }
                                else{
                                    $result = $latestpoint;
                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$resultCheck->MaKQSV)
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
                return redirect('/danh-sach-sinh-vien?lop='.$findlop->MaTTMH.'&HK='.session()->get('HKid'));
            }
            else if($request->input('divide3'))
            {
                $option2 = $request->input('divide3');

                $findlop = DB::table('danh_sach_sinh_vien')->where('MaTTMH', session()->get('danh-sach-sinh-vien-lop'))
                ->where('MaHK',session()->get('HKid'))->distinct()->first();
                $listUpToRow14 = DB::table('danh_sach_sinh_vien')->where('MaTTMH', session()->get('danh-sach-sinh-vien-lop'))
                ->where('MaHK',session()->get('HKid'))->distinct()->get();
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
                            ->where('MaDanhSach',$resultCheck->MaDanhSach)
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['Diem14' => $result]);

                            $checkDQT = DB::table("ket_qua")->where('MaKQSV',$resultCheck->MaKQSV)->first();
                            if($checkDQT)
                            {
                                if($findlop->Diem16 != null)
                                {
                                    $resultlatest = $result + $resultCheck->Diem16;

                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$resultCheck->MaKQSV)
                                                ->update(['DiemQT' => $resultlatest]);
                                }
                                else{
                                    // $result = $findlop->Diem14;
                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$resultCheck->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }

                            }
                        }
                    }
                    else if($phanloailop == '1' || $phanloailop == '2')
                    {
                        //Nếu là môn lý thuyết
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
                            ->where('MaDanhSach',$resultCheck->MaDanhSach)
                            ->where('MSSV',$resultCheck->MSSV)
                            ->update(['Diem14' => $result]);

                            $checkDQT = DB::table("ket_qua")->where('MaKQSV',$resultCheck->MaKQSV)->first();
                            if($checkDQT)
                            {
                                if($findlop->Diem16 != null)
                                {
                                    $resultlatest = $result + $resultCheck->Diem16;

                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$resultCheck->MaKQSV)
                                                ->update(['DiemQT' => $resultlatest]);
                                }
                                else{
                                    // $result = $findlop->Diem14;
                                    $DQT = DB::table('ket_qua')
                                                ->where('MaKQSV',$resultCheck->MaKQSV)
                                                ->update(['DiemQT' => $result]);
                                }

                            }
                        }
                    }


                }

                return redirect('/danh-sach-sinh-vien?lop='.$findlop->MaTTMH.'&HK='.session()->get('HKid'));
            }
            else //Nếu không chọn gì trong các option tính điểm
            {
                return redirect()->back()->withInput();

            }


        }

        public function DiemCot16(Request $request)
        {

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
                                if(preg_match('/^ [0-9]*$/',$resultConvert) == 1 && preg_match('/^[a-zA-Z!@#$%^&*()_+\-=\[\]{};:\'"\<>\/?\\|~]*$/',$resultConvert) != 1) //Kiểm tra có tồn tại số bên trong hay không
                                {
                                    if(str_contains($request->row16[$i],",") == true || str_contains($request->row16[$i],".") == true) //Nếu ký tự nhập vào là số thập phân
                                    {
                                        try{
                                            $resultConvert = number_format($resultConvert,2,'.',''); //sinh ra thêm số để chuẩn number sau khi thêm . hoặc , vd: 5.5 -> 5.50
                                        }catch(ModelNotFoundException $exception)
                                        {
                                            return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))->with('error-row16','Hãy nhập số chính xác!')->withInput();
                                        }
                                    }
                                    else //nếu nhập kí tự chữ cái
                                    {
                                        return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))->with('error-row16','Hãy nhập số chính xác!')->withInput();
                                    }
                                }
                                else //nếu nhập kí tự chữ cái
                                {
                                    return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))->with('error-row16','Hãy nhập số chính xác!')->withInput();
                                }


                            }


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
                                    return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))->with('error-row16','Điểm tổng không vượt quá 10')->withInput();
                                }

                            //Điều kiện kiểm tra thời gian thỏa để sửa điểm hay không
                                // if(session()->has('timeForChange') && Carbon::now()->greaterThan(Carbon::parse( session()->get('timeForChange'))))
                                // {
                                //     session()->forget('timeForChange');
                                //     $setNullTimeForChange = DB::table('danh_sach_sinh_vien')
                                //     ->where('MaDanhSach',$findrow14->MaDanhSach)
                                //     ->where('MSSV',$GetInfoStudent)
                                //     ->update([
                                //         'TimeForChangeRow16'=> null
                                //     ]);
                                //     return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))->with('error-row16','Quá thời gian sửa điểm')->withInput();
                                // }
                                // else
                                // {
                                //     if($findrow14->Diem16 == null)
                                //     {
                                //         session()->put('timeForChange',Carbon::now()->addWeeks(2)); //Nếu chưa nhập điểm thì set thời gian cho phép sửa điểm là 2 tuần kế từ lúc nhập điểm
                                //         $setNullTimeForChange = DB::table('danh_sach_sinh_vien')
                                //                 ->where('MaDanhSach',$findrow14->MaDanhSach)
                                //                 ->where('MSSV',$GetInfoStudent)
                                //                 ->update([
                                //                     'TimeForChangeRow16'=> session()->get('timeForChange')
                                //                 ]);
                                //     }
                                // }

                            // else
                            // {
                                // if(session()->has('timeForChange'))
                                // {
                                    // session()->forget('timeForChange'); //Nếu đã có điểm tồn tại mà sửa điểm thì không cho sửa nữa
                                    //}
                            // }

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
                                return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))->with('error-row16','Hãy nhập số chính xác!')->withInput();
                            }
                        }



                    }
                    session()->forget('row16');// Sau khi xử lý xóa session
                    return redirect('/danh-sach-sinh-vien?lop='.$findrow14->MaTTMH.'&HK='.session()->get('HKid'));
                    // return redirect('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop'));

            }
            else
            {
                return redirect()->back();
            }


        }

        public function CheckToPutLeader(Request $request)
        {

            if($request->LTnum)
            {
                $ChooseClassManage = DB::table('danh_sach_sinh_vien')
                                        ->where('MaDanhSach',$request->LTnum)
                                        ->update(['BanCanSuLop' => 1]);
                return redirect()->to('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))->withInput();
            }
            else
            {
                return redirect()->to('/danh-sach-sinh-vien?lop='.session()->get('danh-sach-sinh-vien-lop').'&HK='.session()->get('HKid'))
                ->with('error-row16','Không được để trống lựa chọn!')->withInput();
            }
        }

        public function AddnewComment(Request $request){

            $messages = [
                'inputcomments.required' => 'Không được để trống nội dung',
            ];
            $validated = $request->validate([
                'inputcomments' => 'required',
                ], $messages);
            if($request->inputcomments)
            {
                if(session()->has('studentid'))
                {
                    $addnewcomment = DB::table('comments')->insert([
                        'MSSV' => session()->get('studentid'),
                        'MSGV'=>null,
                        'NoiDung' =>$request->inputcomments,
                        'MaTTMH' => session()->get('danh-sach-sinh-vien-lop'),
                        'MaHK' => session()->get('HKid'),
                        'NgayComment' => Carbon::now()
                    ]);
                }
                else if(session()->has('teacherid'))
                {
                    $addnewcomment = DB::table('comments')->insert([
                        'MSSV'=>null,
                        'MSGV' => session()->get('teacherid'),
                        'NoiDung' =>$request->inputcomments,
                        'MaTTMH' => session()->get('danh-sach-sinh-vien-lop'),
                        'MaHK' => session()->get('HKid'),
                        'NgayComment' => Carbon::now(),
                    ]);
                }
            }

            return redirect()->back();
        }
}
