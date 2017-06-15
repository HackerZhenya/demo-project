<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route("posts.index");
});

Route::post("/posts/{post}/update", [
    "as" => "posts.update",
    "uses" => "PostsController@update"
]);

Route::get("/posts/{post}/delete", [
    "as" => "posts.destroy",
    "uses" => "PostsController@destroy"
]);

Route::get("/posts/json", [
    "as" => "posts.json",
    "uses" => "PostsController@json"
]);

Route::resource("posts", "PostsController", ['except' => [ 'update', 'destroy' ]]);