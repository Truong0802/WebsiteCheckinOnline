<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function contact()
    {
        if(session()->has('clockUp') && Carbon::now()->greaterThan(Carbon::parse(session()->get('clockUp'))))
        {

                      return redirect()->action([
                          AccountController::class,
                          'logout'
                      ]);

       }
        return view('admin/contact');
    }
}
