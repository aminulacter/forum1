<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Thread;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;
use App\Rules\Recaptcha;
use App\Rules\SpamFree;
use App\Trending;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);
        if (request()->wantsJson()) {
            return $threads;
        }
       
        return view('threads.index', [
            'threads' =>$threads,
            'trending' =>  $trending->get()]);
    }

    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        return $threads->paginate(5);
    }


   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
                'title' => ['required', new SpamFree],
                'body' => ['required', new SpamFree],
                'channel_id' => 'required|exists:channels,id',
                'g-recaptcha-response' => ['required', new Recaptcha()]
            ]);
        
        

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'title' => request('title'),
            'channel_id' =>request('channel_id'),
            'body' => request('body'),
           
        ]);
        if (request()->wantsJson()) {
            return response($thread, 201);
        }
       
        return redirect($thread->path())
        ->with('flash', 'Your Thread has been published!');
    }


    // public function store(Request $request, Recaptcha $recaptcha)
    // {
      
    //     request()->validate([
    //             'title' => ['required', new SpamFree],
    //             'body' => ['required', new SpamFree],
    //             'channel_id' => 'required|exists:channels,id',
    //             'g-recaptcha-response' => ['required', $recaptcha]
    //         ]);
        
        

    //     $thread = Thread::create([
    //         'user_id' => auth()->id(),
    //         'title' => request('title'),
    //         'channel_id' =>request('channel_id'),
    //         'body' => request('body'),
           
    //     ]);
    //     if(request()->wantsJson())
    //     {
    //         return response($thread, 201);
    //     }
       
    //     return redirect($thread->path())
    //     ->with('flash', 'Your Thread has been published!');
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);
        //$thread->visits()->record();
        $thread->increment('visits');

        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        $thread->update(request()->validate([
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
        ]));


        // $thread->title = request()->title;
        // $thread->body = request()->body;
        // $thread->save();


        // $thread->update(request('title', 'body'));
        //dd($thread);
        //return $thread;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        //     $thread->replies()->delete();

       
        $thread->delete();
        if (request()->wantsJson()) {
            return response([], 204);
        }
        return redirect('/threads');
    }
}
