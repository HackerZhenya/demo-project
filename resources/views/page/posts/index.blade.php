@extends("layout.app")

@section("title", "Posts")

@section("app.container", "container-fluid")

@section("app.content")
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="active">
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
        <div class="col-md-4">

            <div class="well">
                <h4>Filters</h4>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="showUnpublishedPosts"> Show unpublished posts
                    </label>
                </div>

                <div class="input-group">
                    <span class="input-group-addon">Posts per page</span>
                    <select class="form-control" id="postsPerPage">
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="75">75</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <hr style="border-top: 1px solid #cacaca;">

            </div>
        </div>
        <div class="col-md-8">

            @if (session('message'))
                <div class="alert alert-info">{{ session('message') }}</div>
            @endif

            <table class="table table-hover">
                <thead>
                <tr>
                    <th width="10px">#</th>
                    <th>Author</th>
                    <th>Head</th>
                    <th>Body</th>
                    <th>Time</th>
                    <th width="170px">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($posts as $post)
                    <tr @if (!$post->published) class="unpublished" @endif>
                        <th scope="row">{{ $post->id }}</th>
                        <td>{{ $post->person->name }}</td>
                        <td>{{ str_limit($post->head, $limit = 40, $end = '...') }}</td>
                        <td>{{ str_limit($post->body, $limit = 40, $end = '...') }}</td>
                        <td>{{ $post->updated_at->diffForHumans() }}</td>
                        <td class="post-actions" data-id="{{ $post->id }}">
                            <a href="{{ route('posts.show', ['post' => $post->id]) }}">Read</a> |
                            <a href="{{ route('posts.edit', ['post' => $post->id]) }}">Edit</a> |
                            <a href="{{ route('posts.destroy', ['post' => $post->id]) }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
    <div style="text-align: center;">{{ $pagination }}</div>

    <script>
        function updateQuery(newQuery){
            if (Object.keys(newQuery).length == 0) {
                history.replaceState(null, null, window.location.href.split("?")[0]);
            } else {
                history.replaceState(null, null, window.location.href.split("?")[0] + newQuery);
            }
        }

        $(document).ready(function() {
            // ============================== [Filter] Posts per page ==============================
            var paramLimit = new URL(window.location.href).searchParams.get("limit");

            if (paramLimit !== null) {
                if ($("select#postsPerPage > option[value="+paramLimit+"]")[0] !== undefined) {
                    $("select#postsPerPage > option[value="+paramLimit+"]").attr("selected", true);
                } else {
                    $("select#postsPerPage").prepend("<option value='"+paramLimit+"' selected>Custom value ("+paramLimit+" posts)</option>");
                }
            }

            $("select#postsPerPage").change(function() {
                var limit = $("select#postsPerPage :selected").val();
                updateQuery($.query.set("limit", limit));
                window.location.reload();
            });

            // ============================== [Filter] Show Unpublished ==============================
            $("#showUnpublishedPosts").change(function() {
                if ($("#showUnpublishedPosts").prop("checked")) {
                    $(".unpublished").addClass("danger");
                    updateQuery($.query.set("showUnpublished", "true"));
                } else {
                    $(".unpublished").removeClass("danger");
                    updateQuery($.query.set("showUnpublished", "false"));
                }
            });

            if ($.query.get("showUnpublished") == "true") {
                $("#showUnpublishedPosts").prop("checked", true)
            } else {
                $("#showUnpublishedPosts").prop("checked", false)
            }
            $("#showUnpublishedPosts").change();
        });
    </script>
@endsection