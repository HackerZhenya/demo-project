@extends("layout.app")

@section("title", "Error")

@section("app.content")
    <div style="text-align: center;">
        @if ($exception->getStatusCode() == 404)
            <img src="{{ asset("assets/images/404.png") }}" alt="Error 404">
        @else
            <img src="https://http.cat/{{ $exception->getStatusCode() }}" alt="Error {{ $exception->getStatusCode() }}">
        @endif
            <br><br>
            <a href="{{ url()->previous() }}" class="btn btn-default">Go back</a>
    </div>

@endsection