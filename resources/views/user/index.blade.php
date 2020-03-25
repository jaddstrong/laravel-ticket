@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col align-self-start">
                            <button class="btn btn-success" data-toggle="modal" data-target="#myModal" id="create">Create Ticket</button>
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
                                <th>Importance</th>
                                <th>Assigned</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">                            
                            @foreach($query as $key)
                                @if($key->ticket_finish == 0)  
                                    <tr>
                                        <td>
                                            @if(date('Y-m-d', strtotime($key->created_at)) < date('Y-m-d', strtotime(now())))
                                                {{date('j F, Y', strtotime($key->created_at))}}
                                            @else
                                                {{ $key->created_at->diffForHumans() }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($key->ticket_finish == 0)
                                                @if($key->ticket_active == 0)
                                                    <?php echo "Open"; ?>
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
                                            <a href="/user/{{$key->id}}" class="btn btn-sm btn-primary">View</a>
                                            <a id="{{$key->id}}" href="#" class="btn btn-sm btn-primary edit">Edit</a>
                                            <a id="{{$key->id}}" href="#" class="btn btn-sm btn-danger delete">Drop</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <div class="pagination justify-content-center">{{ $query->links() }}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection