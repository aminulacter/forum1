 <div class="card mt-3" >
      <div class="card-header">
            <div class="level">
                <h5 class="flex">
                        <a href="#" >{{ $reply->owner->name }}</a>  said {{ $reply->created_at->diffForHumans() }} ...
                </h5>
                
             <div>
                <form method="post" action="/replies/{{$reply->id}}/favorites">
                @csrf
                <button class="btn btn-primary" type="submit">Favorite</button>
                </form>
             </div>
            </div>
             
     </div>

     <div class="card-body">
            {{ $reply->body }}
    </div>

 </div>
                      