
@section('content')
<div class="container" style="max-width: 400px; margin: 40px auto;">
    <h2>Login</h2>
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div style="margin-bottom: 15px;">
            <label for="email">Email:</label>
            <input id="email" type="email" name="email" required autofocus class="form-control" value="{{ old('email') }}">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="password">Password:</label>
            <input id="password" type="password" name="password" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
@endsection
