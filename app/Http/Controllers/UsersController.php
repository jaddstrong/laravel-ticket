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
    public function index()
    {
        return view('user.index');
    }

    //CREATE TICKET
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $id = Auth::user()->id;
        
        $ticket = new Ticket;
        $ticket->user_id = $id;
        $ticket->ticket_title = $request->title;
        $ticket->ticket_description = $request->description;
        $ticket->ticket_importance = $request->importance;
        $ticket->ticket_admin_id = 0;
        $ticket->ticket_status = 'Open';
        $ticket->save();

        return redirect('/user');
    }

    //GET THE DATA OF A TICKET
    public function edit($id)
    {
        $ticket = Ticket::find($id);
        return response()->json($ticket);
        
    }

    //UPDATE A TICKET
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $user_id = Auth::user()->id;
        $ticket = Ticket::find($id);
        $ticket->ticket_title = $request->title;
        $ticket->ticket_description = $request->description;
        $ticket->ticket_importance = $request->importance;
        $ticket->save();

        return redirect('/user');
    }

    //SET THE TICKET STATUS TO DROP && THE TICKET STILL EXIST TO THE DATABASE
    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        $ticket->ticket_status = 'Drop';
        $ticket->save();

        return redirect('/user');
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
