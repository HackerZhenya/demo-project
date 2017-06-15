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
        $saved = [
            "inputIdSince" => $request->input("inputIdSince"),
            "inputIdBefore" => $request->input("inputIdBefore"),
            "inputHead" => $request->input("inputHead"),
            "inputBody" => $request->input("inputBody"),
            "hideUnpublished" => $request->input("hideUnpublished"),
            "highlightUnpublished" => $request->input("highlightUnpublished"),
            "postsPerPage" => $request->input("postsPerPage", "25")
        ];

        $posts = Post::latest()
            ->idSince($saved["inputIdSince"])
            ->idBefore($saved["inputIdBefore"])
            ->headContains($saved["inputHead"])
            ->bodyContains($saved["inputBody"])
            ->published((bool)$saved["hideUnpublished"]);

        $filteredCount = $posts->count();
        $posts = $posts->paginate((int)$saved["postsPerPage"]);


        $saved = array_where($saved, function ($value) {
            return strlen(trim($value)) > 0;
        });

        return view("page.posts.index", [
            "count" => Post::count(),
            "filteredCount" => $filteredCount,
            "posts" => $posts,
            "pagination" => $posts->appends($saved)->links()
        ]);
    }

    public function json(Request $request)
    {
         return Post::latest()->paginate();
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

        if ( starts_with(str_after(url()->previous(), route('posts.index')), "/") ) { // is not 'posts.index'
            $redirect = redirect(route("posts.index"));
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->withMessage("Post #".$post->id." successfully deleted");
    }
}
