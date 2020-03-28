<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use DataTables;
use App\Ticket;
use App\Logs;
use App\Comment;

class AdminsController extends Controller
{
    //ADMIN INDEX
    public function index()
    {
        return view('admin.index');
    }

    //LIST OF ACCEPTED TICKET
    public function pending()
    {
        return view('admin.pending');
    }

    // TICKET ARCHIVE || LIST OF SOLVED TICKETS
    public function archive()
    {
        return view('admin.archive');
    }

}
