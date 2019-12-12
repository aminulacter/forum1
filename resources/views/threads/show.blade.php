@extends('layouts.app')
@section('header')
<link rel="stylesheet" href="{{ asset('css/vendor/jquery.atwho.css') }}">
<script>
    window.thread = @json($thread)
</script>
@endsection
@section('content')
<thread-view  :thread="{{ $thread }}" inline-template>
<div class="container">
    <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                               
                            <span class="flex">
                                    <img src="{{ asset($thread->creator->avatar_path) }}" alt="{{ $thread->creator->name }}" height="25" width="25">
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
                    <replies  @added="repliesCount++"   @removed="repliesCount--"></replies>
                   
                    {{-- {{ $replies->links()}} --}}
                </div>      
                
    
                    
        
        </div>
        <div class="col-md-4">
            <div class="card">
               
                <div class="card-body">
                   
                    <p class="card-text">
                        This thread was published {{ $thread->created_at->diffForHumans() }} by 
                        <a href="#"> {{ $thread->creator->name }}</a> and currently has <span v-text="repliesCount"></span>
                       {{ Str::plural('comment', $thread->replies_count)}}
                    </p>
                    <p class="level">
                       <subscribe-button :active={{ json_encode($thread->isSubscribedTo) }} v-if="signedIn"></subscribe-button>
                       <button class="btn btn-secondary ml-3" v-if="authorize('isAdmin')" @click="togglelock" v-text="locked ? 'Unlock': 'lock'"></button>
                    </p>
                </div>
            </div>
        </div>
    </div>
      
    
   
</div>
</thread-view>
@endsection
