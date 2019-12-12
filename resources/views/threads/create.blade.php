@extends('layouts.app')
@section ('header')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a New thread</div>

                <div class="card-body">
                  <form action="/threads" method="POST">
                  @csrf
                    <div class="form-group">
                        <label for="channel_id">Choose a Channel</label>
                        <select id="channel_id" class="form-control" name="channel_id" >
                          <option value="">Chose One ...</option>   
                            @foreach ($channels as $channel)
                        <option value="{{$channel->id}}" {{ old('channel_id') == $channel->id ? 'selected' : ''}}>{{$channel->name}}</option>
                            @endforeach
                           
                        </select>
                    </div>
                     <div class="form-group">
                         <label for="title">Title</label>
                     <input id="title" class="form-control" type="text" name="title" value="{{ old('title')}}" >
                     </div> 
                    
                     <div class="form-group">
                         <label for="body">Body</label>
                         <textarea id="body" class="form-control" name="body" rows="8" >{{ old('body')}}</textarea>
                     </div>
                     <div class="form-group">
                         <div class="g-recaptcha" data-sitekey="6LecPscUAAAAAHsCfeFakX9iEAFcQAFfi_iTPrP_"></div>
                     </div>

                     <div class="form-group">
                         <button class="btn btn-primary" type="submit">Publish</button>

                     </div>
                    
                      @if (count($errors))

                  <div class="alert alert-danger" role="alert">
                      <ul >
                      @foreach ($errors->all() as $error)
                          <li> {{$error}}</li>
                      @endforeach
                      </ul>
                  </div>
                  
                  
                      
                  @endif   
                  </form>  
                              
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
