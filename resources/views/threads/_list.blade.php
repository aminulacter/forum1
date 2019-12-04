@forelse ($threads as $thread)
<div class="card mt-3" >
    <div class="card-header">
         <div class="level">
             <div class="flex">
                    <h4 >
                        <a href="{{ $thread->path() }}">
                          @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                          <strong>{{ $thread->title }}</strong>
                           @else
                           {{ $thread->title }}
                           @endif
                        </a></h4>
                        <h5>Posted By: <a href="{{ route('profile',$thread->creator->name) }}">{{ $thread->creator->name }}</a></h5>
                        <a href="{{ $thread->path() }}">
                    </div>
                    <strong>{{ $thread->replies_count }} 
                    {{ Str::plural('comment', $thread->replies_count)}}</strong></a>
                                           
         </div>
    </div>

    <div class="card-body">                         

        <div class="body">{{ $thread->body }}</div>

    </div>
</div>
    @empty
        <p>There are no relevant results at this time.</p>
     @endforelse