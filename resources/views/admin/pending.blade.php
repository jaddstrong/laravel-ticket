@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Pending Tickets</div>
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
                                <th>Importance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($query as $key)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{$key}}</td>
                                    <td>{{$key}}</td>
                                    <td><a role="button" class="btn btn-sm btn-primary">Edit</a></td>
                                    <td><a role="button" class="btn btn-sm btn-primary">Delete</a></td>
                                </tr>
                            @endforeach --}}
                            <tr>
                                <td>Title of the ticket</td>
                                <td>Description of the ticket</td>
                                <td>Comment of the last assigned admin</td>
                                <td>Importance level of the ticket</td>
                                <td>
                                    <a href="/admin/pending/id" class="btn btn-sm btn-primary">View</a> 
                                    <a href="#" class="btn btn-sm btn-danger">Drop</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection