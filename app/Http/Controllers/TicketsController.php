<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use DataTables;
use App\Ticket;
use App\Logs;

class TicketsController extends Controller
{
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
        $ticket->ticket_status = 'New';
        $ticket->save();

        //GET LAST INSERTED ID AND TIMESTAMP && SET FORMAT FOR TICKET CODE
        $get_last = Ticket::orderBy('id', 'desc')->first();
        $timestamp = str_replace(' ', '-', $get_last->created_at);
        $date = preg_replace('/[^A-Za-z0-9 ]/', '', $timestamp);
        $ticket_code = $date.$get_last->ticket_importance.$get_last->id;

        $update = Ticket::find($get_last->id);
        $update->ticket_code = $ticket_code;
        $update->save();

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $get_last->id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "created this ticket.";
        $logs->save();

        return redirect('/user');
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

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "edited this ticket.";
        $logs->save();

        return redirect('/user');
    }

    //SET THE TICKET STATUS TO DROP && THE TICKET STILL EXIST TO THE DATABASE
    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        $ticket->ticket_status = 'Drop';
        $ticket->save();

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "droped this ticket.";
        $logs->save();

        return redirect('/user');
    }
    
    //RETURN THE TICKET TO POLL
    public function return(Request $request)
    {
        $ticket = Ticket::find($request->id);
        $ticket->ticket_status = 'Return';
        $ticket->ticket_admin_id = 0;
        $ticket->save();

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $request->id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "returned this ticket.";
        $logs->save();

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

    //GET THE DATA OF A TICKET
    public function edit($id)
    {
        $ticket = Ticket::find($id);
        return response()->json($ticket);
        
    }

    //DISPLAY TICKET AND IT`S COMMENT
    public function show($id)
    {
        $ticket = Ticket::find($id);
        return view('ticket.view')->with('ticket', $ticket);
    }

    //CLOSE THE TICKET
    public function solve(Request $request)
    {
        $ticket = Ticket::find($request->id);
        $ticket->ticket_status = 'Solve';
        $ticket->save();

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $request->id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "has closed this ticket.";
        $logs->save();

    }

    //RE-OPEN THE TICKET
    public function reopen(Request $request)
    {
        $ticket = Ticket::find($request->id);
        $ticket->ticket_admin_id = 0;
        $ticket->ticket_status = 'ReOpen';
        $ticket->save();

        // Create logs
        $logs = new Logs;
        $logs->ticket_id = $request->id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "has re-open this ticket.";
        $logs->save();

    }

    //DYNAMIC DATA TABLE DISPLAY
    public function dataTables(Request $request)
    {
        $url = URL::previous();
        if($url == 'http://127.0.0.1:8000/user'){
            if ($request->ajax()) {
                $url = $request->url();
                $user_id = Auth::user()->id;
                $user_type = Auth::user()->user_type;
                $data = Ticket::where('user_id', $user_id)
                    ->whereIn('ticket_status', ['New', 'Open', 'Pending', 'Return', 'ReOpen'])->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($time) {
                        return $time->created_at->format('Y/m/d h:i:s');
                    })
                    ->editColumn('ticket_description', function ($des) {
                        $des = strip_tags($des->ticket_description);
                        return $des;
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/ticket/'.$row->id.'" class="btn btn-sm btn-primary">View</a> 
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
                $data = Ticket::where('user_id', Auth::user()->id)->where('ticket_status', 'Solve')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y/m/d h:i:s');
                    })
                    ->editColumn('ticket_description', function ($des) {
                        $des = strip_tags($des->ticket_description);
                        return $des;
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/ticket/'.$row->id.'" class="btn btn-sm btn-primary">View</a>';
    
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
                $data = Ticket::where('ticket_status', 'Solve')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y/m/d h:i:s');
                    })
                    ->editColumn('ticket_description', function ($des) {
                        $des = strip_tags($des->ticket_description);
                        return $des;
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/ticket/'.$row->id.'" class="btn btn-sm btn-primary">View</a>';
    
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('user.archive');
        }elseif($url == 'http://127.0.0.1:8000/admin'){
            if ($request->ajax()) {
                $admin_id = Auth::user()->id;
                $data = Ticket::with(array('comments' => function($q){ $q->orderBy('updated_at', 'desc')->first(); }))
                    ->where('ticket_admin_id', '!=', $admin_id)
                    ->whereIn('ticket_status', ['New', 'Open', 'Return', 'ReOpen'])->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                    return $user->created_at->format('Y/m/d h:i:s');
                    })
                    ->editColumn('ticket_description', function ($des) {
                        $des = strip_tags($des->ticket_description);
                        return $des;
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/ticket/'.$row->id.'" class="btn btn-sm btn-primary">View</a>
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
                        ->where('ticket_status', 'Pending')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y/m/d h:i:s');
                    })
                    ->editColumn('ticket_description', function ($des) {
                        $des = strip_tags($des->ticket_description);
                        return $des;
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/ticket/'.$row->id.'" class="btn btn-sm btn-primary">View</a>
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
                        ->where('ticket_status', 'Solve')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y/m/d h:i:s');
                    })
                    ->editColumn('ticket_description', function ($des) {
                        $des = strip_tags($des->ticket_description);
                        return $des;
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="/ticket/'.$row->id.'" class="btn btn-sm btn-primary">View</a>
                                <a href="#" id="'.$row->id.'" class="btn btn-sm btn-secondary logs" data-toggle="modal" data-target="#myModal">Logs</a>';
   
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.pending');
        }
        
    }


}
