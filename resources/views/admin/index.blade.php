@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <div class="card-header">Tickets</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Comment</th>
                                <th>Assigned</th>
                                <th>Importance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $key)
                                <tr>
                                    <td>{{$key->ticket_title}}</td>
                                    <td>{{$key->ticket_description}}</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{$key->ticket_importance}}</td>
                                    <td>
                                        <a href="/admin/pending/{{$key->id}}" class="btn btn-sm btn-primary">View</a>
                                        <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#myModal">Logs</a>
                                        <a href="/admin/pending/id" class="btn btn-sm btn-success">Accept</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection