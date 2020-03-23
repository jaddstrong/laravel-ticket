<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Ticket;
use App\Logs;
use App\Comment;

class AdminsController extends Controller
{
    public function index()
    {
        $admin_id = Auth::user()->id;
        $query = Ticket::with(array('comments' => function($q)
        {
            $q->orderBy('updated_at', 'desc');
        }))->where('ticket_admin_id', '!=', $admin_id)->where('ticket_active', '0')->where('ticket_finish', '0')->orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.index')->with('tickets', $query);

    }

    public function show($id)
    {
        $query = Ticket::find($id);
        return view('admin.view')->with('query', $query);
    }

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

    public function pending()
    {
        $admin_id = Auth::user()->id;
        $tickets = Ticket::with(array('comments' => function($q)
        {
            $q->orderBy('updated_at', 'desc');
        }))->where('ticket_admin_id', $admin_id)->orderBy('updated_at', 'desc')->get();

        return view('admin.pending')->with('pending', $tickets);
    }

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

    public function return(Request $request)
    {
        $ticket = Ticket::find($request->input('id'));
        $ticket->ticket_admin_id = 0;
        $ticket->ticket_active = 0;
        $ticket->save();

    }

    // UNFINISH
    public function logs($id)
    {
        $logs = Logs::where('ticket_id', $id)->get();
        return response()->json($logs);
    }
}
