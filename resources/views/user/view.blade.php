@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                        <a class="btn btn-secondary" href="{!! URL::previous() !!}">Back</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="media">
                                <img class="mr-3" alt="Bootstrap Media Preview" src="https://www.layoutit.com/img/sports-q-c-64-64-8.jpg" />
                                {{-- Ticket Information --}}
                                <div class="media-body">
                                    <h5 class="mt-0">{{$ticket->ticket_title}}</h5> 
                                    <p class="">{{$ticket->ticket_description}}</p>
                                    <small>{{$ticket->ticket_importance}}</small><br>
                                    <small>
                                        @if(date('Y-m-d', strtotime($ticket->created_at)) < date('Y-m-d', strtotime(now())))
                                                {{date('j F, Y', strtotime($ticket->created_at))}}
                                            @else
                                                {{ $ticket->created_at->diffForHumans() }}
                                            @endif
                                    </small>
                                    
                                    {{-- Comment List --}}
                                    @foreach($ticket->comments as $comment)
                                    <div class="media mt-3">
                                         <a class="pr-3" href="#"><img alt="Bootstrap Media Another Preview" src="https://www.layoutit.com/img/sports-q-c-64-64-2.jpg" /></a>
                                        <div class="media-body">
                                            <h5 class="mt-0">
                                                {{$comment->user_name}}
                                            </h5> 
                                            <p>{{$comment->comment}}</p>
                                            <small>
                                                @if(date('Y-m-d', strtotime($comment->created_at)) < date('Y-m-d', strtotime(now())))
                                                    {{date('j F, Y', strtotime($comment->created_at))}}
                                                @else
                                                    {{ $comment->created_at->diffForHumans() }}
                                                @endif
                                            </small><hr>
                                        </div>
                                    </div>
                                    @endforeach

                                   {{-- Create Comment --}}
                                   <div class="media mt-3">
                                        <div class="media-body">
                                            <input type="hidden" id="id" name="id" value="{{$ticket->id}}">
                                            @if($ticket->ticket_status == 'Solve' && $ticket->user_id == Auth::user()->id)
                                                <button class="btn btn-primary open_ticket" id="open_ticket">Re-open Ticket</button>
                                            @elseif($ticket->user_id == Auth::user()->id)
                                                <textarea class="form-control" rows="3" id="comment"></textarea><br>
                                                <button class="btn btn-primary send" id="send">Send</button>
                                                <button class="btn btn-primary solve" id="solve">Solve</button>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection