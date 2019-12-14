@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('threads._list')
            {{ $threads->links()}}
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Search
                    <img src="{{ asset('algolia/logo-algolia-nebula-blue-full.png') }}" alt="" height="30" width="60">
                </div>
                <div class="card-body">
                    <form method="get" action="/threads/search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search for something..." name="q"> 
                        </div>
                        <div class="form-group">
                               <button class="btn btn-light shadow"  type="submit">Search</button>
                        </div>
                    </form>                                   
                </div>
            </div>
            @if (count($trending))
            <div class="card">
                    <div class="card-header">
                        Trending Threads
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                                @foreach ($trending as $thread)
                                <li class="list-group-item">
                                     <a href="{{ url($thread->path) }}">
                                             {{ $thread->title }}
                                     </a>
                                    
                                 </li>
                             @endforeach
                        </ul>
                       
                    </div>
            </div>
            @endif
           
        </div>
    </div>
</div>
@endsection
