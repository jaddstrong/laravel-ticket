<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Ticket;
use App\Comment;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $user_type = Auth::user()->user_type;
        $query = Ticket::where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(10);
        return view('user.index')->with('query', $query);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        $ticket->ticket_active = false;
        $ticket->ticket_finish = false;
        $ticket->save();

        return redirect('/user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::find($id);
        return view('user.view')->with('ticket', $ticket);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ticket = Ticket::find($id);
        return response()->json($ticket);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        $ticket->delete();

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

        return redirect('/user/'.$request->id);
    }

    // TICKET ARCHIVE || LIST OF SOLVED TICKETS
    public function archive()
    {
        $query = Ticket::where('ticket_finish', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('user.archive')->with('query', $query);

    }

    //CLOSE THE TICKET
    public function solve($id)
    {
        $ticket = Ticket::find($id);
        $ticket->ticket_finish = 1;
        $ticket->save();

    }
}
