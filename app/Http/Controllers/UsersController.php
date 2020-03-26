<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use DataTables;
use App\User;
use App\Ticket;
use App\Comment;

class UsersController extends Controller
{
    public function index()
    {
        return view('user.index');
    }
    //DISPLAY CREATED TICKET THAT ARE OPEN,PENDING,RETURN,REOPEN
    public function dataTables(Request $request)
    {
        $url = URL::previous();
        if($url == 'http://127.0.0.1:8000/user'){
            if ($request->ajax()) {
                $url = $request->url();
                $user_id = Auth::user()->id;
                $user_type = Auth::user()->user_type;
                $data = Ticket::where('user_id', $user_id)
                    ->where('ticket_status', 'Open')
                    ->orWhere('ticket_status', 'Pending')
                    ->orWhere('ticket_status', 'Return')
                    ->orWhere('ticket_status', 'ReOpen')
                    ->orderBy('created_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y/m/d H:i:s');
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/user/'.$row->id.'" class="btn btn-sm btn-primary">View</a> 
                                <a id="'.$row->id.'" href="#" class="btn btn-sm btn-primary edit">Edit</a>
                                <a id="'.$row->id.'" href="#" class="btn btn-sm btn-danger delete">Drop</a>';
    
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('user.index');
        }elseif($url == 'http://127.0.0.1:8000/userArchive'){
            if ($request->ajax()) {
                $data = Ticket::where('user_id', Auth::user()->id)->where('ticket_status', 'Solve')->orderBy('updated_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y/m/d H:i:s');
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/user/'.$row->id.'" class="btn btn-sm btn-primary">View</a>';
    
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('user.myArchive');
        }elseif($url == 'http://127.0.0.1:8000/archive'){
            if ($request->ajax()) {
                $user_id = Auth::user()->id;
                $user_type = Auth::user()->user_type;
                $data = Ticket::where('ticket_status', 'Solve')->orderBy('created_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y/m/d H:i:s');
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/user/'.$row->id.'" class="btn btn-sm btn-primary">View</a>';
    
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('user.archive');
        }
        
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

    public function show($id)
    {
        $ticket = Ticket::find($id);
        return view('user.view')->with('ticket', $ticket);
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

    // STORING COMMENT TO A TICKET
    public function comment(Request $request)
    {
        $comment = new Comment;
        $comment->ticket_id = $request->id;
        $comment->user_id = Auth::user()->id;
        $comment->user_name = Auth::user()->name;
        $comment->comment = $request->comment;
        $comment->save();

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "commented on this ticket.";
        $logs->save();

        return redirect('/user/'.$request->id);
    }

    // TICKET ARCHIVE || LIST OF SOLVED TICKETS
    public function archive(Request $request)
    {
        return view('user.archive');
    }

    // // USER`S TICKET ARCHIVE || LIST OF SOLVED TICKETS OF THE USER
    public function userArchive(Request $request)
    {
        return view('user.myArchive');
    }

    //CLOSE THE TICKET
    public function solve(Request $request)
    {
        $ticket = Ticket::find($request->id);
        $ticket->ticket_status = 'Solve';
        $ticket->save();

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "has closed this ticket.";
        $logs->save();

    }

    //RE-OPEN THE TICKET
    public function reopen(Request $request)
    {
        $ticket = Ticket::find($request->id);
        $ticket->ticket_status = 'ReOpen';
        $ticket->save();

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "has re-open this ticket.";
        $logs->save();

    }
  
}
