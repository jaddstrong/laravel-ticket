@extends('layouts.admin')

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
                                    <h5 class="mt-0">
                                        {{$query->ticket_title}} 
                                    </h5> 
                                    <p>
                                        {{$query->ticket_description}}
                                    </p>
                                    <small>{{$query->ticket_importance}}</small><br>
                                    <small>{{$query->created_at}}</small>
                                    <hr>
                                    
                                    {{-- Comment List --}}
                                    @foreach($query->comments as $key)
                                    <div class="media mt-3">
                                         <a class="pr-3" href="#"><img alt="Bootstrap Media Another Preview" src="https://www.layoutit.com/img/sports-q-c-64-64-2.jpg" /></a>
                                        <div class="media-body">
                                            <h5 class="mt-0">
                                                {{$key->user_name}}
                                            </h5> 
                                            <p>{{$key->comment}}</p>
                                            <small>{{$key->created_at}}</small><hr>
                                        </div>
                                    </div>
                                    @endforeach
                                    

                                   {{-- Create Comment --}}
                                   <div class="media mt-3">
                                        <div class="media-body">
                                            
                                            <input type="hidden" id="id" name="id" value="{{$query->id}}">
                                            @if($query->ticket_status == 'Solve')
                                                <button class="btn btn-primary open_ticket" id="open_ticket">Re-open Ticket</button>
                                            @elseif($query->ticket_admin_id == Auth::user()->id)
                                                <textarea class="form-control" rows="3" id="comment"></textarea><br>
                                                <button class="btn btn-primary send" id="send">Send</button>
                                                <button class="btn btn-primary return" id="return">Return</button>
                                                <button class="btn btn-primary solve" id="solve">Solve</button>
                                            @else
                                            <a href="/admin/{{$query->id}}/add" id="{{$query->id}}" class="btn btn-sm btn-success accept">Accept</a>
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