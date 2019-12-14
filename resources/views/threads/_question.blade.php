  {{-- Editing the question --}}
  <div class="card" v-if="editing">
      <div class="card-header">
        <div class="level">
        
            <input type="text" class="form-control"  v-model = "form.title">
            
        </div>
    </div>

    <div class="card-body">  
        <div class="form-group">
          
                <wysiwyg  v-model="form.body" :value="form.body" name="body"></wysiwyg>
        </div>
      
    </div>

    <div class="card-footer level">
      
        <button class="btn-sm btn btn-secondary level-item" @click="resetForm">cancel</button>
          <button class="btn-sm btn btn-primary level-item" @click="update">Update</button>
           @can('update', $thread)
                <form method="post" action="{{ $thread->path() }}" class="ml-auto">
                   @csrf 
                   @method('delete') 
                   <button type="submit" class="btn btn-danger">Delete Thread</button>      
                </form>
        @endcan
    </div>


</div>

  <div class="card" v-else>
      <div class="card-header">
        <div class="level">
                               
             <span class="flex">
             <img src="{{ asset($thread->creator->avatar_path) }}" alt="{{ $thread->creator->name }}" height="25" width="25">
             <a href="{{route('profile', $thread->creator)}}">{{ $thread->creator->name }}</a> posted:    @{{ form.title }}
             </span>
             {{-- @can('update', $thread)
                <form method="post" action="{{ $thread->path() }}">
                   @csrf 
                   @method('delete') 
                   <button type="submit" class="btn btn-link">Delete Thread</button>      
                </form>
             @endcan --}}
        </div>
    </div>

    <div class="card-body" v-html="form.body">  
    
    </div>
       @can('update', $thread)
    <div class="card-footer">
        <button class="btn-sm btn btn-secondary" @click="editing = true">Edit</button>
      
    </div>
      @endcan

</div>