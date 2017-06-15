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
                <h4>Filters (found {{ $filteredCount }} posts)</h4>

                <form class="form-horizontal" action="{{ route("posts.index") }}">
                    <div class="form-group">
                        <label for="inputID" class="col-sm-3 control-label">#ID</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">since</span>
                                <input type="text" id="inputID" name="inputIdSince" class="form-control need-apply" placeholder="1" value="{{ request("inputIdSince") }}">
                                <span class="input-group-addon">before</span>
                                <input type="text" name="inputIdBefore" class="form-control need-apply" placeholder="{{ $count }}" value="{{ request("inputIdBefore") }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputHead" class="col-sm-3 control-label">'Head' contains</label>
                        <div class="col-sm-9">
                            <input type="text" id="inputHead" name="inputHead" class="form-control need-apply" placeholder="Some word" value="{{ request("inputHead") }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputBody" class="col-sm-3 control-label">'Body' contains</label>
                        <div class="col-sm-9">
                            <input type="text" id="inputBody" name="inputBody" class="form-control need-apply" placeholder="Some word" value="{{ request("inputBody") }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="highlightUnpublished" class="col-sm-3 control-label">Unpublished</label>
                        <div class="col-sm-9">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="hideUnpublished" class="need-apply" name="hideUnpublished" value="true" @if ((bool)request("hideUnpublished")) checked @endif> Hide posts
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="highlightUnpublished" name="highlightUnpublished" value="true" id="showUnpublishedPosts" @if ((bool)request("highlightUnpublished")) checked @endif> Highlight posts
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="postsPerPage" class="col-sm-3 control-label need-apply">Posts per page</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="postsPerPage" name="postsPerPage" data-value="{{ request("postsPerPage", 25) }}">
                                <option value="25">25 posts</option>
                                <option value="50">50 posts</option>
                                <option value="75">75 posts</option>
                                <option value="100">100 posts</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <span class="help-block">If the field is empty, the filter will not be applied</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button id="applyChangesButton" type="submit" class="btn btn-default" style="margin-right: 10px">Apply filters</button>
                            <span id="applyChangesText" class="text-primary" style="display: none">You need to apply changes</span>
                        </div>
                    </div>
                </form>

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
                    <th>Time <span class="glyphicon glyphicon-chevron-up"></span></th>
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
            $("#hideUnpublished").change(function() {
                var state = $("#hideUnpublished").prop("checked");

                if (state)
                    $("#highlightUnpublished").prop("disabled", true).prop("checked", false);
                else
                    $("#highlightUnpublished").prop("disabled", false);

                $("#highlightUnpublished").change();
            });

            $("#highlightUnpublished").change(function() {
                var state = $("#highlightUnpublished").prop("checked");

                if (state)
                    $(".unpublished").addClass("danger");
                else
                    $(".unpublished").removeClass("danger");

                updateQuery($.query.set("highlightUnpublished", state.toString()));
            });


            var postsPerPage = $("select#postsPerPage").data("value");
            if (postsPerPage !== null) {
                if ($("select#postsPerPage > option[value="+postsPerPage+"]")[0] !== undefined) {
                    $("select#postsPerPage > option[value="+postsPerPage+"]").attr("selected", true);
                } else {
                    $("select#postsPerPage").prepend("<option value='"+postsPerPage+"' selected>Custom value ("+postsPerPage+" posts)</option>");
                }
            }

            $("#highlightUnpublished").change();
            $("#hideUnpublished").change();

            $(".need-apply").change(function() {
                $("#applyChangesButton").removeClass("btn-default").addClass("btn-primary");
                $("#applyChangesText").show();
            });
        });
    </script>
@endsection