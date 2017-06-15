@extends("layout.app")

@section("title", "Read post")

@section("app.container", "container-fluid")

@section("app.content")
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li>
                    <a href="{{ route("posts.index") }}">
                        <span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;Posts <span class="badge">{{ $count }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route("posts.create") }}">
                        <span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Create post
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <blockquote>
                <p>Read post</p>
            </blockquote>
            @if ($post->published)
                <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Пост опубликован</div>
            @else
                <div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Пост не опубликован</div>
            @endif
            <div class="well">
                <strong>#ID: </strong>{{ $post->id }}<br>
                <strong>Name: </strong>{{ $post->person->name }}<br>
                <strong>Head: </strong>{{ $post->head }}<br>
                <strong>Body: </strong>{{ nl2br($post->body) }}<br>
                <strong>Created at: </strong>{{ $post->created_at->diffForHumans() }} ({{ $post->created_at }})<br>
                <strong>Updated at: </strong>{{ $post->updated_at->diffForHumans() }} ({{ $post->updated_at }})
            </div>
            <a class="btn btn-default" href="{{ url()->previous() }}">Back</a>
            <a class="btn btn-success" href="{{ route("posts.edit", ["post" => $post->id]) }}">Edit</a>
            <a class="btn btn-danger" href="{{ route("posts.destroy", ["post" => $post->id]) }}">Delete</a>
        </div>
    </div>

@endsection