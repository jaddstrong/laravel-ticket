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
        $ticket->ticket_admin_id = 0;
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
                    ->whereIn('ticket_status', ['Open', 'Pending', 'Return', 'ReOpen'])
                    ->orderBy('created_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($time) {
                        return $time->created_at->format('Y/m/d H:i:s');
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
                $data = Ticket::where('user_id', Auth::user()->id)->where('ticket_status', 'Solve')->orderBy('updated_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y/m/d H:i:s');
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
                $data = Ticket::where('ticket_status', 'Solve')->orderBy('created_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y/m/d H:i:s');
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
                    ->whereIn('ticket_status', ['Open', 'Return', 'ReOpen'])
                    ->orderBy('updated_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                    return $user->created_at->format('Y/m/d H:i:s');
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
                        ->where('ticket_status', 'Pending')
                        ->orderBy('updated_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y/m/d H:i:s');
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
                        ->where('ticket_admin_id', $admin_id)
                        ->where('ticket_status', 'Solve')
                        ->orderBy('updated_at', 'desc')->get();
    
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y/m/d H:i:s');
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
