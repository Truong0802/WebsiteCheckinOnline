<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailerController extends Controller
{
    //
    public function sendMail(Request $request)
    {
        $username = session()->pull('username');
        $mail = session()->pull('mail');
        Mail::send('Mail.mail-test', compact('username'), function($email) use($username,$mail){
            $email->subject('Yêu cầu thay đổi mật khẩu');
            $email->to($mail ,$username);
        });
        return redirect()->to('/');
    }
}
