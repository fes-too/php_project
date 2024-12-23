<?php

namespace App\Http\Controllers;


use id;
use auth;
use Eloquent;
use App\Model;
use App\Model\User;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Jobs\SendNewPostMailJob;

use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache ;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\put;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return all posts from database
        

       

              $posts =   Cache::remember('posts', 10, function (){
                    sleep(4);
                    return Post::paginate(6);
                  });
        

        return view('posts.index', ['posts'  => $posts]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        
        //create post
        return view('posts.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //    Post::store([
    //     'title' => $request->title,
    //     'content' =>  $request->content,
    //          ]);
       
    $validated = $request->validate([
        'title' => ['required', 'min:5', 'max:255'],
        'content' => ['required', 'min:10'],
        'thumbnail' => ['required', 'image'],

      ]); 

      $validated['thumbnail'] =$request->file('thumbnail')->store('thumbnails');
      
    //   auth()->user()->posts()->create($validated);
      auth()->user()->posts()->create($validated);

      dispatch(new SendNewPostMailJob(['email' => auth()->user()->email, 'name' => auth()
      ->user()->name, 'title' => $validated['title'] ]));
  
    // return redirect()->route('posts.index');
             return to_route('posts.index')->with('message', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //show specific post
        // $post = Post::find($id);
        
        return view('posts.show', ['post'=>$post]);
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //show edit page only if post belongs  to the user
        // if($post->user_id  !== auth()->id()){


        //     abort(403);
            
        // }

        Gate::authorize('update', $post);
        //edit post
        return view('posts.edit',['post' => $post]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);
        $validated = $request->validate([
            'title' => ['required', 'min:5', 'max:255'],
            'content' => ['required', 'min:10'],
            'thumbnail' => ['sometimes', 'image'],
    
          ]);

          if($request->hasFile('thumbnail')){
            File::delete(storage_path('app/public/'. $post->thumbnail) );
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails');
          }

      $post->update($validated);

      return to_route('posts.show', ['post' =>$post])->with('message', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);
        File::delete(storage_path('app/public/'. $post->thumbnail) );
        $post->delete();
        return to_route('posts.index');
    }
}
