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
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Title</th>
                                <th>description</th>
                                <th>Importance</th>
                                <th>Assign</th>
                                <th width="125px">Action</th>
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

 {{-- MODAL FOR CREATE --}}
 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalHeading">Create Ticket</h4>
                <button type="button" class="close cancel" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Ticket title: </label>
                        <input type="text" id="title" name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description">Ticket description: </label>
                        <textarea type="text" id="article-ckeditor" name="article-ckeditor" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="importance">Importance level: </label>
                        <select id="importance" name="importance" class="form-control">
                            <option value="level1">Level 1</option>
                            <option value="level2">Level 2</option>
                            <option value="level3">Level 3</option>
                          </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="update" class="btn btn-success">Update</button>
                <button type="button" id="submit" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-secondary cancel" data-dismiss="modal">Cancel</button>
            </div>
        </div>
      
    </div>
</div>

<script>
    CKEDITOR.replace( 'article-ckeditor' );
    // EDIT TICKET
    $(".data-table").on('click', '.edit', function(){
        $('#submit').hide();
        $('#update').show();
        var id = this.id;
        $.ajax({type:"GET", url: "/user/"+id+"/edit", success: function(result){
            $('#submit').val("Update");
            $('#title').val(result.ticket_title);
            $('#description').val(result.ticket_description);
            $('#importance').val(result.ticket_importance);
            $('#myModal').modal("show");
        }});

        $("#update").click(function(){
            var title = $('#title').val();
            var description = $('#description').val();
            var importance = $('#importance').val();
            $.ajax({
                url:"/user/"+id+"/update",
                type:"post", 
                data:
                {
                    title:title,
                    description:description,
                    importance:importance
                }, 
                success: function(result){
                    location.reload(true);
            }});
        });
        
    });

    // TO HIDE UPDATE BUTTON IN MODAL
    $("#create").click(function(){
        $('#update').hide();
        $('#submit').show();
    });

    // CREATE TICKET
    $("#submit").click(function(){
        CKEDITOR.instances['article-ckeditor'].updateElement();
        var title = $('#title').val();
        var description = $('#article-ckeditor').val();
        var importance = $('#importance').val();
        $.ajax({
            type:"POST",
            url: "/user",
            data:
            {
                title:title,
                description:description,
                importance:importance
            },
            success: function(result){
                location.reload(true);
            }
        });
    });
    
    // DELETE TICKET
    $(".data-table").on('click', '.delete', function(){
        var r = confirm("Confirm to delete ticket.");
        if (r == true) {
            var id = this.id;
            $.ajax({
                type:"DELETE",
                url:"/user/"+id+"/delete",
                success: function(result){
                    
                }
            });
            location.reload(true);
        } else {
            window.close();
        }
    });
</script>
@endsection