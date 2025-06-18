{{-- Standard header for all pages --}}
<header class="header">
    <h1>{{ $title ?? 'Procrastimate' }}</h1>
    @if(auth()->check())
        @php
            $user = auth()->user();
            $role = $user->role;
        @endphp
        <nav>
            <ul>
                @if($role === 'admin')
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('groups.index') }}">Groups</a></li>
                    <li><a href="{{ route('admin.user.createForm') }}">Users</a></li>
                    <li><a href="{{ route('admin.notification.index') }}">Notifications</a></li>
                @elseif($role === 'coach')
                    <li><a href="{{ route('coach.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('groups.index') }}">Groups</a></li>
                    <li><a href="{{ route('coach.task.create') }}">Tasks</a></li>
                    <li><a href="{{ route('notifications.view') }}">Notifications</a></li>
                @elseif($role === 'user')
                    <li><a href="{{ route('userDashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('task.createForm') }}">Tasks</a></li>
                    <li><a href="{{ route('groups.index') }}">Groups</a></li>
                    <li><a href="{{ route('notifications.view') }}">Notifications</a></li>
                @endif
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    @else
        <nav>
            <ul>
                <li><a href="{{ route('homepage') }}">Home</a></li>
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
                <li><a href="#aboutUs">About Us</a></li>
                <li><a href="#features">Features</a></li>
            </ul>
        </nav>
    @endif
</header>
