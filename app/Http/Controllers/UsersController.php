<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use DataTables;
use App\User;
use App\Ticket;
use App\Comment;
use App\Logs;

class UsersController extends Controller
{
    //USER`S TICKET
    public function index()
    {
        return view('user.index');
    }

    // TICKET ARCHIVE || LIST OF SOLVED TICKETS
    public function archive()
    {
        return view('user.archive');
    }

    // // USER`S TICKET ARCHIVE || LIST OF SOLVED TICKETS OF THE USER
    public function userArchive()
    {
        return view('user.myArchive');
    }
  
}
