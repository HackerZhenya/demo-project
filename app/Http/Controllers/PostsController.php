<?php

namespace App\Http\Controllers;

use App\Person;
use App\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $instance = [
            "limit" => $request->input("limit", "25"),
            "showUnpublished" => $request->input("showUnpublished", "false"),
        ];

        $posts = Post::orderBy("id", "desc")->paginate((int)$instance["limit"]);

        return view("page.posts.index", [
            "count" => Post::count(),
            "posts" => $posts,
            "pagination" => $posts->appends($instance)->links()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("page.posts.create", [
            "count" => Post::count(),
            "personsCount" => Person::count()
        ]);
    }

    public function formValidationRules() {
        $personsCount = Person::count();
        return [
            "userId" => "required|integer|between:1,$personsCount",
            "postHead" => "required|max:255",
            "postBody" => "required"
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->formValidationRules());

        $post = new Post();
        $post->person_id = $request->input("userId");
        $post->head = $request->input("postHead");
        $post->body = $request->input("postBody");
        $post->published = !(bool)$request->input("isDraft", false);
        $post->save();

        return redirect(route("posts.index"));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view("page.posts.read", [
            "count" => Post::count(),
            "post" => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view("page.posts.edit", [
            "count" => Post::count(),
            "personsCount" => Person::count(),
            "post" => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->validate($request, $this->formValidationRules());

        $post->person_id = $request->input("userId");
        $post->head = $request->input("postHead");
        $post->body = $request->input("postBody");
        $post->published = !(bool)$request->input("isDraft", false);
        $post->save();

        return redirect(route("posts.index"))->withMessage("Post #".$post->id." successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect(route("posts.index"))->withMessage("Post #".$post->id." successfully deleted");
    }
}
