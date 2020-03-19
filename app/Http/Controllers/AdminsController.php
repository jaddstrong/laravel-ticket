<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminsController extends Controller
{
    public function index()
    {
        $user_type = Auth::user()->user_type;
        if($user_type == 'admin'){
            // $query = Ticket::all();
            return view('admin.index');
        }else{
            return redirect('/home');
        }
    }
}
