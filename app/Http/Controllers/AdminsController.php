<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Ticket;

class AdminsController extends Controller
{
    public function index()
    {
        $user_type = Auth::user()->user_type;
        if($user_type == 'admin'){
            $query = Ticket::all();
            return view('admin.index')->with('tickets', $query);
        }else{
            return redirect('/home');
        }
    }
}
