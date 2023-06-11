<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    public function trangchusv(Request $request){
        // if($request->start && $request->end){
        //     if(session()->exists('studentid')){
        //         $username = session()->get('studentid');

        //         $allsubject = DB::table('lich_giang_day')->where('MSSV',$username)->distinct()->get();
        //         // $classname = DB::table('lop')->where('MaLop',session()->get('malop'))->get();
        //         // session()->put('allsubjectname',$subjectname->TenMH);
        //         return view('Student/index',['getallsubject' => $allsubject,'getstartdate'=>$request->start,'getenddate'=>$request->end]);
        //     }
        //     elseif(session()->exists('teacherid')){
        //         $teacherid = session()->get('teacherid');

        //         $allsubject = DB::table('lich_giang_day')->where('MSGV',$teacherid)->distinct()->get();
        //         return view('Student/index',['getallsubject' => $allsubject]);
        //     }
        //     else{
        //         return redirect()->to('/');
        //     }
        // }
        // else{
            if(session()->exists('studentid')){
                $username = session()->get('studentid');

                $allsubject = DB::table('tkb')->where('MSSV',$username)->distinct()->get();
                // $classname = DB::table('lop')->where('MaLop',session()->get('malop'))->get();
                // session()->put('allsubjectname',$subjectname->TenMH);
                return view('Student/index',['getallsubject' => $allsubject]);
            }
            elseif(session()->exists('teacherid')){
                //  elseif(session()->exists('teacherid') && $request->MonHoc){
                $teacherid = session()->get('teacherid');

                //$allsubject = DB::table('lich_giang_day')->where('MSGV',$teacherid)->where('MaTTMH',$request->MonHoc)->distinct()->get();
                // $allsubject = DB::table('lich_giang_day')->where('MSGV',$teacherid)->distinct()->get();

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
                    }


            }
            else{
                return redirect()->to('/');
            }

        // }

    }
}
