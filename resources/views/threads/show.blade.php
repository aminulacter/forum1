@extends('layouts.app')

@section('content')
<thread-view  initialreplycount="{{ $thread->replies_count }}" inline-template>
<div class="container">
    <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                <a href="{{route('profile', $thread->creator)}}">{{ $thread->creator->name }}</a> posted:    {{ $thread->title }}
                            </span>
                              @can('update', $thread)
                                <form method="post" action="{{ $thread->path() }}">
                                    @csrf 
                                    @method('delete') 
                                    <button type="submit" class="btn btn-link">Delete Thread</button>      
                                </form>
                                    
                                @endcan
                       
                        </div>
                        
                    </div>

                    <div class="card-body">  
                        {{ $thread->body }}
                    </div>
                </div>
                       
                <div>
                    <replies :data= "{{ $thread->replies }}" @removed="repliesCount--"></replies>
                   
                    {{ $replies->links()}} --}}
                </div>      
                
    
                    
            
            @if(auth()->check())
            <div class="mt-3">
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
        <div class="col-md-4">
            <div class="card">
               
                <div class="card-body">
                   
                    <p class="card-text">
                        This thread was published {{ $thread->created_at->diffForHumans() }} by 
                        <a href="#"> {{ $thread->creator->name }}</a> and currently has <span v-text="repliesCount"></span>
                       {{ Str::plural('comment', $thread->replies_count)}}
                    </p>
                </div>
            </div>
        </div>
    </div>
      
    
   
</div>
</thread-view>
@endsection
