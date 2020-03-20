@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success" data-toggle="modal" data-target="#myModal" id="create">Create Ticket</button>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table table-hover" id="tab">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Title</th>
                                <th>Importance</th>
                                <th>Assigned</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>                            
                            @foreach($query as $key)
                                <tr>
                                    <td>{{$key->created_at}}</td>
                                    <td>
                                        @if($key->ticket_finish == 0)
                                            @if($key->ticket_active == 0)
                                                <?php echo "Not active"; ?>
                                            @endif
                                            @if($key->ticket_active == 1)
                                                <?php echo "Active"; ?>
                                            @endif
                                        @elseif($key->ticket_finish == 1)
                                        <?php echo "Solve"; ?>
                                        @endif
                                    </td>
                                    <td>{{$key->ticket_title}}</td>
                                    <td>{{$key->ticket_importance}}</td>
                                    <td>{{$key->ticket_assign}}</td>
                                    <td>
                                        {{-- {{ Form::open(array('action' => ['UsersController@destroy', $key->id], 'method' => 'POST')) }} --}}
                                            <a href="/user/{{$key->id}}" class="btn btn-sm btn-primary">View</a>
                                            <a id="{{$key->id}}" class="btn btn-sm btn-warning edit">Edit</a>
                                            <a id="{{$key->id}}" class="btn btn-sm btn-danger delete">Drop</a>
                                            {{-- {{ Form::hidden('_method', 'DELETE') }}
                                            {{ Form::submit('Drop', ['class' => 'btn btn-sm btn-danger drop']) }}
                                        {{ Form::close() }} --}}
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