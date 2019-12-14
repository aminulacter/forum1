<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show(Trending $trending)
    {
       // $threads = Thread::search(request('q'))->get();

       
      // dd($threads);
        return view('threads.search', [
            'trending' =>  $trending->get(),
            'question' => request('q')
            ]);
    }
}
    

