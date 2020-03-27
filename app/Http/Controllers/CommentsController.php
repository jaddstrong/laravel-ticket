<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Comment;
use App\Logs;

class CommentsController extends Controller
{
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
        $logs->ticket_id = $request->id;
        $logs->user_id = Auth::user()->id;// USER_ID is for ethier admin or user
        $logs->name = Auth::user()->name;
        $logs->action = "commented on this ticket.";
        $logs->save();

        // return redirect('/ticket/'.$request->id);
    }
}
