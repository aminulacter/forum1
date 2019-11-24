@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Forum Threads</div>

                <div class="card-body">
                    
                        <article>
                            <h4>{{ $thread->title }}</h4>
                            <div class="body">{{ $thread->body }}</div>
                            
                        </article>
                  

                   
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
                <div class="col-md-8">
                    
                       @foreach ($thread->replies as $reply)
                       <div class="card mt-3" >
                            <div class="card-header">
                               <a href="#">{{ $reply->owner->name }}</a>  said {{ $reply->created_at->diffForHumans() }} ...
                            </div>
                            <div class="card-body">
                                    {{ $reply->body }}
                            </div>
                       </div>
                      
                                          
                       @endforeach
        
                        
                </div>
    </div>
</div>
@endsection
