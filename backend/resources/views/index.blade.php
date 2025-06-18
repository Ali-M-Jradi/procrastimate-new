@extends('layouts.app')

@section('content')
<header class="header">
    <h1>Welcome to Procrastimate</h1>
    <nav>
        <ul>
            <li><a href="{{ route('homepage') }}">Home</a></li>
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
            <li><a href="#aboutUs">About Us</a></li>
            <li><a href="#features">Features</a></li>
        </ul>
    </nav>
    <br>
    <h2>Guest HomePage</h2>
    <p>
        Welcome to Procrastimate! This is a simple web application that helps you manage your tasks and time effectively.<br>
        You can create, update, and delete tasks, as well as track your time spent on each task.
    </p>
    <br>
    <p>
        To get started, please <a href="{{ route('login') }}">log in</a> or <a href="{{ route('register') }}">register</a> to create an account.<br>
        Once you have an account, you can access all the features of the application.
    </p>
</header>

<div id="aboutUs" class="container fade-section">
    <h2>About Us</h2>
    <p>
        We are a team of developers who are passionate about helping people manage their time and tasks effectively.<br>
        Our goal is to create a simple and user-friendly application that helps you stay organized and productive.
    </p>
</div>

<div id="features" class="container fade-section">
    <h2>Features of Procrastimate</h2>
    <ul>
        <li>Create, update, and delete tasks</li>
        <li>Track time spent on each task</li>
        <li>Set deadlines and reminders for tasks</li>
        <li>View task history and statistics</li>
        <li>User-friendly interface</li>
    </ul>
</div>

<div class="contact-section container fade-section">
    <h2>Contact Us</h2>
    <p>
        If you have any questions or feedback, please feel free to contact us at
        <a href="mailto:101220427@mu.edu.lb">101220427@mu.edu.lb</a>
    </p>
</div>
@endsection