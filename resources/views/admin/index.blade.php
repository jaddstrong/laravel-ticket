@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col align-self-start">
                        <h3>Ticket Poll</h3>
                    </div>
                    <div class="col align-self-end">
                        <input class="form-control" type="text" id="myInput" placeholder="Search...">
                    </div>
                </div>
            </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Comment</th>
                                <th>Assigned</th>
                                <th>Importance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            @foreach($tickets as $key)
                                <tr>
                                    <td>
                                        @if(date('Y-m-d', strtotime($key->updated_at)) < date('Y-m-d', strtotime(now())))
                                            {{date('j F, Y', strtotime($key->updated_at))}}
                                        @else
                                            {{ $key->updated_at->diffForHumans() }}
                                        @endif
                                    </td>
                                    <td>{{$key->ticket_status}}</td>
                                    <td>{{$key->ticket_title}}</td>
                                    <td>{{$key->ticket_description}}</td>
                                    @if(count($key->comments))
                                        @foreach($key->comments as $val)
                                            @if($val->comment)
                                                <td>{{$val->comment}}</td>
                                            @endif
                                            @break
                                        @endforeach
                                    @else
                                        <td></td>
                                    @endif
                                    <td>{{$key->ticket_assign}}</td>
                                    <td>{{$key->ticket_importance}}</td>
                                    <td>
                                        <a href="/admin/{{$key->id}}/show" class="btn btn-sm btn-primary">View</a>
                                        <a href="#" id="{{$key->id}}" class="btn btn-sm btn-secondary logs" data-toggle="modal" data-target="#myModal">Logs</a>
                                        <a href="/admin/{{$key->id}}/add" id="{{$key->id}}" class="btn btn-sm btn-success accept">Accept</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="pagination justify-content-center">{{ $tickets->links() }}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection