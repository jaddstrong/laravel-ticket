@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-secondary" href="/user">Back</a>
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
                                    <small>{{$ticket->created_at}}</small>
                                    
                                    {{-- Comment List --}}
                                    @foreach($ticket->comments as $key)
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
                                            <textarea class="form-control" rows="3"></textarea><br>
                                            <button>Send</button>
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