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
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

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
                        
                        @if(url()->current() == "http://127.0.0.1:8000/admin")
                            <li class="nav-item active">
                        @else
                            <li class="nav-item">
                        @endif
                            <a class="nav-link" href="/admin">Tickets</a>
                        </li>

                        @if(url()->current() == "http://127.0.0.1:8000/admin/pending")
                            <li class="nav-item active">
                        @else
                            <li class="nav-item">
                        @endif
                            <a class="nav-link" href="/admin/pending">Pending Ticket</a>
                        </li>

                        @if(url()->current() == "http://127.0.0.1:8000/admin/archive")
                            <li class="nav-item active">
                        @else
                            <li class="nav-item">
                        @endif
                            <a class="nav-link" href="/admin/archive">Archive</a>
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

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-sm">
            
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ticket Logs</h4>
                        <button type="button" class="close cancel" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover" id="logs_table">
                            <thead>
                                <tr>
                                    <td>Date</td>
                                    <td>Assigned</td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary cancel" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
              
            </div>
        </div>

    </div>
</body>
{{-- <script src="{{ asset('js/jquery.js') }}"></script> --}}
<script>
    $( document ).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });

        $(function () {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.dataTables') }}",
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

        // SEND COMMENT TO A TICKET
        $("#send").click(function(){
            var id = $("#id").val();
            var comment = $("#comment").val();
            $.ajax({
                type:"POST",
                url: "/admin/comment",
                data:
                {
                    id:id,
                    comment:comment
                },
                success: function(result){
                    location.reload(true);
                }
            });
        });
        
        // RETURN TICKET TO THE POLL
        $("#return").click(function(){
            var id = $("#id").val();
            $.ajax({
                type:"POST",
                url: "/admin/"+id+"/return",
                data:
                {
                    id:id
                },
                success: function(result){
                    window.location.href = '/admin/pending';
                }
            });
        });

        //CLOSE/SOLVE THE TICKET
        $("#solve").click(function(){
            var id = $('#id').val();
            $.ajax({
                type:"POST",
                url: "/admin/"+id+"/solve",
                data:
                {
                    id:id
                },
                success: function(result){
                    window.location.href = '/admin/pending';
                }
            });
        });

        //RE-OPEN TICKET
        $('#open_ticket').click(function(){
            var id = $('#id').val();
            $.ajax({
                type:"POST",
                url: "/admin/"+id+"/open",
                data:
                {
                    id:id
                },
                success: function(result){
                    window.location.href = '/admin/archive';
                }
            });
        });

        //DISPLAY THE LOGS OF TICKET
        $(".logs").click(function(){
            var id = this.id;
            $.ajax({
                type:"GET",
                url: "/admin/"+id+"/logs",
                success: function(result){
                    var i;
                    for(i = 0; i < result.length; i++){
                        var format_date = new Date(result[i].updated_at);
                        var day = format_date.getDate();
                        var m = format_date.getMonth();
                        m += 1;
                        var y = format_date.getFullYear();
                        if (day < 10) {
                            day = "0" + day;
                        }
                        if (m < 10) {
                            m = "0" + m;
                        }
                        $("#logs_table").append("<tr><td>"+ day + "-" + m + "-" + y +"</td><td>"+result[i].admin_name+"</td></tr>");
                    }
                }
            });
        });

        //EMPTY THE MODAL OF LOGS
        $(".cancel").click(function(){
            $("#logs_table > tbody").empty();
        });

        //SEARCH IN THE TABLE
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
</html>
