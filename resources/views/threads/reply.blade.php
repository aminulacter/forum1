 {{-- <reply :attributes="{{ $reply }}"  v-cloak> --}}
 <div id="reply-{{ $reply->id }}" class="card mt-3" >
      <div class="card-header">
            <div class="level">
                <h5 class="flex">
                        <a href="{{ route('profile',$reply->owner->name) }}" >{{ $reply->owner->name }}</a>  said {{ $reply->created_at->diffForHumans() }} ...
                </h5>
            @if (Auth::check())
               <div>
                     <favorite :reply="{{ $reply }}"></favorite>
                     
               </div>
            @endif
            
            </div>
             
     </div>

     <div class="card-body">
        <div v-if="editing">
          <div class="form-group">
               <textarea id="my-textarea" class="form-control" name="body" rows="3" v-model="body"></textarea>
          </div>
           <button class="btn btn-sm btn-primary" @click="update">Update</button>
           <button class="btn btn-sm btn-link" @click="editing = false">cancel</button>
           
         
        </div>
        <div v-else v-text = "body">
           
        </div>
            
    </div>
    @can('update', $reply)
      <div class="card-footer level">
         <button class="btn btn-outline-primary btn-xs mr-2" @click="editing = true">Edit</button>
         <button class="btn btn-danger btn-xs mr-2" @click="destroy">Delete</button>
       
      </div>
    @endcan
    

 </div>
 {{-- </reply> --}}
                      