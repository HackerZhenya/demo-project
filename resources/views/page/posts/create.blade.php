@extends("layout.app")

@section("title", "Create post")

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
                <li class="active">
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
                <p>Create post</p>
            </blockquote>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    The given data failed to pass validation:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="well">
                <form class="form-horizontal" method="POST" action="{{ route('posts.store') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="inputUserID" class="col-sm-2 control-label">User ID</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="inputUserID" name="userId" placeholder="User ID (1..{{ $personsCount }})" value="{{ old("userId") }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPostHead" class="col-sm-2 control-label">Post head</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputPostHead" name="postHead" placeholder="Some text" value="{{ old("postHead") }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPostBody" class="col-sm-2 control-label">Post body</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="inputPostBody" name="postBody" rows="4" placeholder="Some text">{{ old("postBody") }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="isDraft" value="true" @if((bool)old("isDraft")) checked @endif> Draft
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Publish</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection