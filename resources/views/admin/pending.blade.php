@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Pending Tickets</h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Importance</th>
                                <th>Assign</th>
                                <th width="150px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    {{-- <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Comment</th>
                                <th>Importance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            @foreach($pending as $key)
                                <tr>
                                    <td>
                                        @if(date('Y-m-d', strtotime($key->updated_at)) < date('Y-m-d', strtotime(now())))
                                            {{date('j F, Y', strtotime($key->updated_at))}}
                                        @else
                                            {{ $key->updated_at->diffForHumans() }}
                                        @endif
                                    </td>
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
                                    
                                    <td>{{$key->ticket_importance}}</td>
                                    <td>
                                        <a href="/admin/{{$key->id}}/show" class="btn btn-sm btn-primary">View</a> 
                                        <a id="return" href="#" class="btn btn-sm btn-danger">Return</a>
                                        <input type="hidden" id="id" name="id" value="{{$key->id}}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection