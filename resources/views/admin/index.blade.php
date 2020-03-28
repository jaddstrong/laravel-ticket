@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <div class="card-header">
                <h3>Ticket Pool</h3>
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
                                <th>Ticket Code</th>
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
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $( document ).ready(function(){
        // ACCEPT TICKET FROM THE POLL
        $(".accept").click(function(){
            var id = this.id;
            $.ajax({
                type:"POST",
                url: "/admin/"+id+"/add",
                data:
                {
                    id:id
                },
                success: function(result){}
            });
        });
    });

</script>
@endsection