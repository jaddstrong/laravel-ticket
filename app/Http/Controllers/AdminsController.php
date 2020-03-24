<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Ticket;
use App\Logs;
use App\Comment;

class AdminsController extends Controller
{
    //TICKET POLL
    public function index()
    {
        $admin_id = Auth::user()->id;
        $query = Ticket::with(array('comments' => function($q)
        {
            $q->orderBy('updated_at', 'desc');
        }))->where('ticket_admin_id', '!=', $admin_id)->where('ticket_active', '0')->where('ticket_finish', '0')->orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.index')->with('tickets', $query);

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
        $admin_id = Auth::user()->id;
        $admin_name = Auth::user()->name;

        // Create logs
        $logs = new Logs;
        $logs->active_id = $admin_id;
        $logs->ticket_id = $id;
        $logs->admin_id = $admin_id;
        $logs->admin_name = $admin_name;
        $logs->save();

        //Update ticket
        $ticket = Ticket::find($id);
        $ticket->ticket_assign = $admin_name;
        $ticket->ticket_admin_id = $admin_id;
        $ticket->ticket_active = true;
        $ticket->save();

        return redirect('/admin/'.$id.'/show');
    }

    //LIST OF ACCEPTED TICKET
    public function pending()
    {
        $admin_id = Auth::user()->id;
        $tickets = Ticket::with(array('comments' => function($q)
        {
            $q->orderBy('updated_at', 'desc');
        }))->where('ticket_admin_id', $admin_id)->where('ticket_finish', 0)->orderBy('updated_at', 'desc')->get();

        return view('admin.pending')->with('pending', $tickets);
    }

    //COMMENT TO THE TICKET
    public function comment(Request $request)
    {
        $comment = new Comment;
        $comment->ticket_id = $request->input('id');
        $comment->user_id = Auth::user()->id;
        $comment->user_name = Auth::user()->name;
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect('/admin/'.$request->input('id').'/show');
    }

    //RETURN THE TICKET TO POLL
    public function return(Request $request)
    {
        $ticket = Ticket::find($request->input('id'));
        $ticket->ticket_admin_id = 0;
        $ticket->ticket_active = 0;
        $ticket->save();

    }

    //CLOSE THE TICKET
    public function solve($id)
    {
        $ticket = Ticket::find($id);
        $ticket->ticket_finish = 1;
        $ticket->save();

    }

    //RE-OPEN THE TICKET
    public function open($id)
    {
        $ticket = Ticket::find($id);
        $ticket->ticket_finish = 0;
        $ticket->save();
    }

    // TICKET LOGS
    public function logs($id)
    {
        $logs = Logs::where('ticket_id', $id)->orderBy('created_at', 'desc')->get();
        $array = array($logs);
        return response()->json($logs);
    }

    // TICKET ARCHIVE || LIST OF SOLVED TICKETS
    public function archive()
    {
        $query = Ticket::where('ticket_finish', 1)->orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.archive')->with('query', $query);

    }
}
