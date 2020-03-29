<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Logs;

class LogsController extends Controller
{
    // TICKET LOGS
    public function logs(Request $request)
    {
        $logs = Logs::where('ticket_id', $request->id)->get();
        return response()->json($logs);
    }
}
