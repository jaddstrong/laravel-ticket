<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    

    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        textarea{
            resize:none;
        }
        th, td{
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-dark bg-dark bg-light shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{-- {{ config('app.name', 'App-Name') }} --}}
                    App-Name
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">   

                        @if(url()->current() == "http://127.0.0.1:8000/user")
                            <li class="nav-item active">
                        @else
                            <li class="nav-item">
                        @endif
                            <a class="nav-link" href="/user">Tickets</a>
                        </li>


                        @if(url()->current() == "http://127.0.0.1:8000/userArchive")
                            <li class="nav-item active">
                        @else
                            <li class="nav-item">
                        @endif
                            <a class="nav-link" href="/userArchive">My Archive</a>
                        </li>

                        @if(url()->current() == "http://127.0.0.1:8000/archive")
                            <li class="nav-item active">
                        @else
                            <li class="nav-item">
                        @endif
                            <a class="nav-link" href="/archive">Ticket Archive</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

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

    </div>
</body>
{{-- <script src="{{ asset('js/jquery.js') }}"></script> --}}
<script  type="text/javascript">
    $( document ).ready(function(){
        CKEDITOR.replace( 'article-ckeditor' );
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $(function () {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.dataTables') }}",
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'ticket_status', name: 'ticket_status'},
                    {data: 'ticket_title', name: 'ticket_title'},
                    {data: 'ticket_description', name: 'ticket_description'},
                    {data: 'ticket_importance', name: 'ticket_importance'},
                    {data: 'ticket_assign', name: 'ticket_assign'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });    
        });

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
            var title = $('#title').val();
            var description = $('#description').val();
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

        // SEND COMMENT IN TICKET
        $("#send").click(function(){
            CKEDITOR.instances['article-ckeditor'].updateElement();
            var id = $("#id").val();
            var comment = $("#article-ckeditor").val();
            $.ajax({
                type:"POST",
                url: "/user/"+id+"/comment",
                data:
                {
                    id:id,
                    comment:comment
                },
                success: function(result){
                }
            });
            location.reload(true);
        });

        //CLOSE/SOLVE THE TICKET
        $("#solve").click(function(){
            var id = $('#id').val();
            $.ajax({
                type:"POST",
                url: "/user/solve",
                data:{ id:id },
                success: function(result){
                }
            });
            window.location.href = '/userArchive';
        });

        //RE-OPEN TICKET
        $('#open_ticket').click(function(){
            var id = $('#id').val();
            $.ajax({
                type:"POST",
                url: "/user/reopen",
                data: { id:id },
                success: function(result){
                }
            });
            window.location.href = '/user';
        });
        
    });
</script>
</html>


