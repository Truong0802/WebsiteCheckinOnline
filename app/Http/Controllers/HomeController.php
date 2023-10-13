<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\AccountController;

class HomeController extends Controller
{
    //
    public function trangchusv(Request $request){
            if(session()->has('clockUp') && Carbon::now()->greaterThan(Carbon::parse( session()->get('clockUp'))))
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

                $allsubject = DB::table('tkb')->where('MSSV',$username)->distinct()->get();
                // $classname = DB::table('lop')->where('MaLop',session()->get('malop'))->get();
                // session()->put('allsubjectname',$subjectname->TenMH);
                return view('Student/index',['getallsubject' => $allsubject]);
            }
            elseif(session()->exists('teacherid')){
                //  elseif(session()->exists('teacherid') && $request->MonHoc){
                $teacherid = session()->get('teacherid');

                    $teacherid = session()->get('teacherid');
                    $findsubjectid = DB::table('lich_giang_day')->where('MSGV',$teacherid)->distinct()->first();
                    if($findsubjectid != null)
                    {

                        // dd($firstsubjectid);
                            $allsubject = DB::table('lich_giang_day')->where('MSGV',$teacherid)->where('MaBuoi',1)->distinct()->paginate(5);
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
}
