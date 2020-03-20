<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
                        <li class="nav-item active">
                            <a class="nav-link" href="/admin">Tickets</a>
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
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                            <div class="form-group">
                                <label for="title">Title: </label>
                                <input type="text" id="title" name="title" class="form-control" placeholder="Ticket title">
                            </div>
                            <div class="form-group">
                                <label for="description">Title: </label>
                                <input type="text" id="description" name="description" class="form-control" placeholder="Ticket description">
                            </div>
                            <div class="form-group">
                                <label for="importance">Importance level: </label>
                                <select id="importance" name="importance" class="form-control">
                                    <option value="level1">Level 1</option>
                                    <option value="level2">Level 3</option>
                                    <option value="level3">Level 3</option>
                                  </select>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="update" class="btn btn-warning">Update</button>
                        <button type="button" id="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
              
            </div>
        </div>

    </div>
</body>
<script src="{{ asset('js/jquery.js') }}"></script>
<script>
    $(".edit").click(function(){
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var id = this.id;
        $.ajax({type:"GET", url: "/user/"+id+"/edit", success: function(result){
            // $('#modelHeading').val("Edit Ticket");
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
            $.ajax({url:"/user/"+id, type:"post", data:{title:title,description:description,importance:importance}, success: function(result){
                location.reload(true);
            }});
        });
        
    });

    $("#create").click(function(){
        $('#update').hide();
    });

    $("#submit").click(function(){
        var title = $('#title').val();
        var description = $('#description').val();
        var importance = $('#importance').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
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

    $(".delete").click(function(){
        var r = confirm("Confirm to delete ticket.");
        if (r == true) {
            var id = this.id;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:"DELETE",
                url:"/user/"+id,
                success: function(result){
                    
                }
            });
            location.reload(true);
        } else {
            window.close();
        }
    });

    function myFunction() {
    
    }
</script>
</html>


