<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class InfoController extends Controller
{
    //
    public function FrmAcessInfo()
    {
        if(session()->exists('studentid'))
        {
            $InfoOfStudent = DB::table('sinh_vien')->where('MSSV',session()->get('studentid'))->first();
            //dd($InfoOfStudent);
            return view('Student/student-info',['getInfoFromObject' => $InfoOfStudent]);
        }
        elseif(session()->exists('teacherid')){
            $InfoOfteacher = DB::table('giang_vien')->where('MSGV',session()->get('teacherid'))->first();
            //dd( $InfoOfteacher);
            return view('Student/student-info',['getInfoFromObject' => $InfoOfteacher]);
        }
    }

    //Chỉnh sửa thông tin
        //Thông tin người dùng
        public function FrmChangeInfo(){
            if(session()->exists('studentid') || session()->exists('teacherid')){
                if(session()->exists('studentid'))
                {
                    $dataBeforeChange = DB::table('sinh_vien')->where('MSSV',session()->get('studentid'))->first();
                }
                else if(session()->exists('teacherid'))
                {
                    $dataBeforeChange = DB::table('giang_vien')->where('MSGV',session()->get('teacherid'))->first();
                }
                return view('Student.change-info',['dataBefore'=> $dataBeforeChange]);

            }
            else
            {
                return redirect()->to('/')->with('error-Login','Yêu cầu đăng nhập để thực hiện')->withInput();
            }
        }

        public function ChangeInfoFunc(Request $request)
        {
            //Dữ liệu update
            $updateData = [];

            if($request->has('imagePath'))
            {
                $dataFile = $request->imagePath;
                $file_ext = $request->imagePath->extension();
                $timeOpen = Carbon::now()->format('dmY-his');
                // $file_name = $dataFile->getClientoriginalName();
                if(session()->exists('studentid'))
                {
                    $file_name = time().'-'.session()->get('studentid').'.'.$file_ext;
                }
                else if(session()->exists('teacherid'))
                {
                    $file_name = $timeOpen.'-'.session()->get('teacherid').'.'.$file_ext;
                }

                $dataFile->move(public_path('img/Avatar'), $file_name);

                $updateData['HinhDaiDien'] = $file_name;
            }

            $messages = [
                'mailDetail.email' => 'Email không hợp lệ',
                'phoneNum.regex' => 'Số điện thoại không hợp lệ',

            ];
            $validated = $request->validate([
                'mailDetail' => 'email|nullable',
                'phoneNum' => 'regex:/^[0-9]{10}$/|nullable',
            ], $messages);



            if($request->has('mailDetail') && $request->mailDetail != null)
            {
                $updateData['Email'] = $request->mailDetail;

            }

            if($request->has('phoneNum') && $request->phoneNum != null )
            {
                $phoneNumber = $request->phoneNum;
                $updateData['SDT'] = $phoneNumber;

            }

            if(session()->exists('studentid'))
            {
                $checkAccountOf = DB::table('sinh_vien')
                ->where('MSSV',session()->get('studentid'))->update($updateData);

            }
            else if(session()->exists('teacherid'))
            {
                $checkAccountOf = DB::table('giang_vien')
                ->where('MSGV',session()->get('teacherid'))
                ->update($updateData);

            }
            return redirect()->to('/thong-tin-ca-nhan');
        }

}
