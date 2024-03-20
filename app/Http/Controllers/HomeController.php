<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\AccountController;
use App\Models\Schedule;
use App\Models\StudentModel;
use App\Models\TKB;

class HomeController extends Controller
{
    //
    public function trangchusv(){
        //Giới hạn thời gian login
            if(session()->has('clockUp') && Carbon::now()->greaterThan(Carbon::parse( session()->get('clockUp'))) == true)
            {
                return redirect()->action([
                    AccountController::class,
                    'logout'
                ]);

            }
            if(session()->exists('studentid')){
                $username = session()->get('studentid');
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();

                $allsubject = TKB::where('MSSV',$username)->distinct()->get();
                // $classname = DB::table('lop')->where('MaLop',session()->get('malop'))->get();
                // session()->put('allsubjectname',$subjectname->TenMH);
                return view('Student/index',['getallsubject' => $allsubject]);
            }
            elseif(session()->exists('teacherid')){
                //  elseif(session()->exists('teacherid') && $request->MonHoc){
                $teacherid = session()->get('teacherid');

                    $teacherid = session()->get('teacherid');
                    $findsubjectid = Schedule::where('MSGV',$teacherid)->distinct()->first();
                    if($findsubjectid != null)
                    {

                        // dd($firstsubjectid);
                            $allsubject = Schedule::where('MSGV',$teacherid)->where('MaBuoi',1)->latest('NgayDay')->distinct()->paginate(15);
                            if($allsubject != null)
                            {
                                return view('Teacher/class-list',['getallsubject' => $allsubject]);
                            }
                            else{
                                return redirect()->to('/');
                            }
                    }
                    else{
                       if(session()->get('ChucVu') == 'QL' || session()->get('ChucVu') == 'AM' )
                       {
                        return redirect()->to('/admin');
                       }
                       else
                       {
                            $allsubject = null;
                            return view('Teacher/class-list',['getallsubject' => $allsubject]);
                       }
                    }


            }
            else{
                return redirect()->to('/');
            }

        // }

    }

    public function backDay(Request $request)
    {
        // dd($request);
        $startDay = Carbon::parse($request->day)->subWeek(1);
        $endDay = Carbon::parse($request->toDay)->subWeek(1);
        session()->put('BatDauTuan',$startDay);
        session()->put('KetThucTuan',$endDay);
        return redirect()->to('/');
    }

    public function nextDay(Request $request)
    {
        // dd($request);
        $startDay = Carbon::parse($request->day)->addWeek(1);
        $endDay = Carbon::parse($request->toDay)->addWeek(1);
        session()->put('BatDauTuan',$startDay);
        session()->put('KetThucTuan',$endDay);
        return redirect()->to('/');
    }

    public function getapi()
    {
        $data = StudentModel::get();
        return response()->json($data);
    }
}
