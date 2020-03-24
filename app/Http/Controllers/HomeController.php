<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->user_type == 'user'){
            return redirect('/user');
        }else{
            return redirect('/admin');
        }   
    }
    // public function admin(Request $req){
    //     return view('middleware');
    // }
    // public function user(Request $req){
    //     return view('middleware');
    // }
}
