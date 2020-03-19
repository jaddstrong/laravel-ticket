@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Ticket Title</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="media">
                                <img class="mr-3" alt="Bootstrap Media Preview" src="https://www.layoutit.com/img/sports-q-c-64-64-8.jpg" />
                                {{-- Ticket Information --}}
                                <div class="media-body">
                                    <h5 class="mt-0">
                                        Title of the Ticket 
                                    </h5> 
                                    <p>THIS IS DESCRIPTION: Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.</p>
                                    <span>Importance</span><br>
                                    <span>Date(Timestamp)</span>
                                    
                                    {{-- Comment List --}}
                                    <div class="media mt-3">
                                         <a class="pr-3" href="#"><img alt="Bootstrap Media Another Preview" src="https://www.layoutit.com/img/sports-q-c-64-64-2.jpg" /></a>
                                        <div class="media-body">
                                            <h5 class="mt-0">
                                                Name of the Admin(latest comment)
                                            </h5> 
                                            <p>THIS IS THE COMMENT: Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.</p>
                                            <span>Date(Timestamp)</span>
                                        </div>
                                    </div>
                                    <div class="media mt-3">
                                        <a class="pr-3" href="#"><img alt="Bootstrap Media Another Preview" src="https://www.layoutit.com/img/sports-q-c-64-64-2.jpg" /></a>
                                        <div class="media-body">
                                            <h5 class="mt-0">
                                                Name of the Admin(latest comment)
                                            </h5> 
                                            <p>THIS IS THE COMMENT: Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.</p>
                                            <span>Date(Timestamp)</span>
                                        </div>
                                   </div>

                                   {{-- Create Comment --}}
                                   <div class="media mt-3">
                                        <div class="media-body">
                                            <textarea class="form-control" rows="3"></textarea><br>
                                            <button>Send</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection