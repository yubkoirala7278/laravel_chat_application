<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messenger</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Your custom styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background-color: #f0f2f5;
        }

        .sidebar {
            width: 300px;
            background-color: #ffffff;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            height: 100%;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #ddd;
            font-size: 1.5rem;
            font-weight: bold;
            background-color: #57606a;
            color: #ffffff;
        }

        .user-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .user-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .user-item:hover {
            background-color: #f1f1f1;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user-name {
            font-weight: bold;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .main-content h2 {
            font-size: 2rem;
            color: #333;
        }

        .main-content p {
            font-size: 1.2rem;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">{{ Auth::user()->name }}</div>
        <ul class="user-list">
            @if (count($users) > 0)
                @foreach ($users as $user)
                    <a href="/chat/{{ $user->id }}" class="user-item" style="text-decoration: none;color:black">
                        <img src="{{ asset('images/profile.webp') }}" class="user-avatar">
                        <div class="user-name" style="margin-right: 5px">{{ $user->name }}</div>
                        @if ($user->isOnline())
                            <i class="fa-solid fa-earth-americas" style="color: green"></i>
                        @else
                            <small>({{ $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'Never' }})</small>
                        @endif
                    </a>
                @endforeach
            @endif
            <li class="user-item">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();"
                    style="text-decoration: none;">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    <div class="main-content">
        <h2>Welcome to Messenger</h2>
        <p>Select a user from the sidebar to start a chat.</p>
    </div>

</body>

</html>
