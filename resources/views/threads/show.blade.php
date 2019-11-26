@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>
                                <a href="#">{{ $thread->creator->name }}</a> posted:    {{ $thread->title }}
                        </h4>
                    </div>

                    <div class="card-body">  
                        {{ $thread->body }}
                    </div>
                </div>
            
                <div>
                        @foreach ($thread->replies as $reply)
                        @include('threads.reply')
                                        
                    @endforeach
                </div>      
                
    
                    
            
            @if(auth()->check())
            <div>
                    <form method="POST" action="{{ $thread->path().'/replies'}}">
                        @csrf  
                        <div class="form-group">
                            
                            <textarea id="body" class="form-control" name="body" rows="5"  placeholder="have something to say?"></textarea>
                        </div>    
                        
                        <button type="submit" class="btn btn-primary">Post</button>  
                            
                    </form>
            </div>
            @else
            <p>Please <a href="{{ route('login')}}"> sign in </a> to participate in this discussion</p>
            @endif
        </div>
    </div>
      
    
   
</div>
@endsection
