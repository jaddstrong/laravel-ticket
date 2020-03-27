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
    
    //DISPLAY THE TICKET INFORMATION AND COMMENTS
    public function show($id)
    {
        $query = Ticket::find($id);
        return view('admin.view')->with('query', $query);
    }

    //PICK-UP TICKET FROM THE POLL
    public function add($id)
    {
        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "has accepted on this ticket.";
        $logs->save();

        //Update ticket
        $ticket = Ticket::find($id);
        $ticket->ticket_assign = Auth::user()->name;
        $ticket->ticket_admin_id = Auth::user()->id;
        $ticket->ticket_status = 'Pending';
        $ticket->save();

        return redirect('/ticket/'.$id);
    }

    //RETURN THE TICKET TO POLL
    public function return(Request $request)
    {
        $ticket = Ticket::find($request->input('id'));
        $ticket->ticket_status = 'Return';
        $ticket->ticket_admin_id = 0;
        $ticket->save();

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $request->input('id');
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "returned this ticket.";
        $logs->save();

    }

    //RE-OPEN THE TICKET
    public function open($id)
    {
        $ticket = Ticket::find($id);
        $ticket->ticket_status = 'ReOpen';
        $ticket->ticket_admin_id = 0;
        $ticket->save();

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "re-open this ticket.";
        $logs->save();
    }


}
