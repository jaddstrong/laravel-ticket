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

    //DATA-TABLES TICKET POOL
    public function dataTables(Request $request)
    {
        $url = URL::previous();
        if($url == 'http://127.0.0.1:8000/admin'){
            if ($request->ajax()) {
                $admin_id = Auth::user()->id;
                $data = Ticket::with(array('comments' => function($q){ $q->orderBy('updated_at', 'desc')->first(); }))
                    ->where('ticket_admin_id', '!=', $admin_id)
                    ->where('ticket_status', 'Open')
                    ->orWhere('ticket_status', 'Return')
                    ->orWhere('ticket_status', 'ReOpen')
                    ->orderBy('updated_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/admin/'.$row->id.'/show" class="btn btn-sm btn-primary">View</a>
                                <a href="#" id="'.$row->id.'" class="btn btn-sm btn-secondary logs" data-toggle="modal" data-target="#myModal">Logs</a>
                                <a href="/admin/'.$row->id.'/add" id="'.$row->id.'" class="btn btn-sm btn-success accept">Accept</a>';
    
                                            
    
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.index');
        }elseif($url == 'http://127.0.0.1:8000/admin/pending'){
            if ($request->ajax()) {
                $admin_id = Auth::user()->id;
                $data = Ticket::with(array('comments' => function($q){ $q->orderBy('updated_at', 'desc')->first(); }))
                        ->where('ticket_admin_id', $admin_id)
                        ->where('ticket_status', 'Pending')
                        ->orderBy('updated_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/admin/'.$row->id.'/show" class="btn btn-sm btn-primary">View</a>
                                <a href="#" id="'.$row->id.'" class="btn btn-sm btn-secondary logs" data-toggle="modal" data-target="#myModal">Logs</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.pending');
        }elseif($url == 'http://127.0.0.1:8000/admin/archive'){
            if ($request->ajax()) {
                $admin_id = Auth::user()->id;
                $data = Ticket::with(array('comments' => function($q){ $q->orderBy('updated_at', 'desc')->first(); }))
                        ->where('ticket_admin_id', $admin_id)
                        ->where('ticket_status', 'Solve')
                        ->orderBy('updated_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/admin/'.$row->id.'/show" class="btn btn-sm btn-primary">View</a>
                                <a href="#" id="'.$row->id.'" class="btn btn-sm btn-secondary logs" data-toggle="modal" data-target="#myModal">Logs</a>';
   
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.pending');
        }

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

        return redirect('/admin/'.$id.'/show');
    }

    //LIST OF ACCEPTED TICKET
    public function pending()
    {
        $admin_id = Auth::user()->id;
        $tickets = Ticket::with(array('comments' => function($q){ $q->orderBy('updated_at', 'desc'); }))
                ->where('ticket_admin_id', $admin_id)
                ->where('ticket_status', 'Pending')
                ->orderBy('updated_at', 'desc')->get();

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

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $request->input('id');
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "commented on this ticket.";
        $logs->save();

        return redirect('/admin/'.$request->input('id').'/show');
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

    //CLOSE THE TICKET
    public function solve($id)
    {
        $ticket = Ticket::find($id);
        $ticket->ticket_status = 'Solve';
        $ticket->save();

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "closed this ticket.";
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
        $query = Ticket::where('ticket_status', 'Solve')->orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.archive')->with('query', $query);

    }
}
