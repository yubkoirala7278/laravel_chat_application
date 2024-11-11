<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/emoji-button@latest/dist/emoji-button.min.css">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <title>Chat</title>
</head>

<body>
    <input type="hidden" name="" id="receiverId" value="{{ $receiverId }}">
    <input type="hidden" name="" id="senderId" value="{{Auth::user()->id}}">
    <div class="sidebar">
        <div class="sidebar-header"><a href="/messenger"
                style="text-decoration: none;color:white">{{ Auth::user()->name }}</a></div>
        <ul class="user-list">
            @if (count($users) > 0)
                @foreach ($users as $user)
                    <a href="/chat/{{ $user->id }}" class="user-item" style="text-decoration: none;color:black">
                        <img src="{{ asset('images/profile.webp') }}" class="user-avatar">
                        <div class="user-name">{{ $user->name }}</div>
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
        <div class="chat-header">
            <img src="{{ asset('images/profile.webp') }}" class="user-avatar">
            <div class="user-name">{{ $receiverName }}</div>
        </div>
        <div class="chat-body" id="chat-body">
            @if ($notifications->count() > 0)
                @foreach ($notifications as $notification)
                    @php
                        $data = json_decode($notification->data, true);
                    @endphp

                    @if ($data['sender_id'] == auth()->id())
                        <!-- Authenticated user's message (right side) -->
                        <div class="chat-right">
                            <div class="message send">
                                {{ $data['message'] }}
                            </div>
                        </div>
                    @else
                        <!-- Other user's message (left side) -->
                        <div class="chat-left">
                            <div class="message received">
                                {{ $data['message'] }}
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

        </div>
        <div class="chat-footer">
            <button id="emoji-button" class="emoji-button"><i class="fa fa-smile"></i></button>
            <div id="emoji-picker" class="emoji-picker"></div>
            <form id="chatForm" style="display: flex; flex: 1; position: relative;">
                <input type="hidden" name="user_id" value="{{ $receiverId }}">
                <input type="text" id="message-input" name="message" class="message-input"
                    placeholder="Type a message...">
                <button id="send-button" class="send-button" type="button"><i class="fa fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // ===csrf setup========
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }

            });
            // ======end of csrf setup===

            // ===========send message===========
            $(document).ready(function() {
                function sendMessage() {
                    // Get the form element by its correct ID
                    var formData = new FormData($('#chatForm')[0]);

                    $.ajax({
                        url: "{{ route('chat.send') }}",
                        method: 'POST',
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function(response) {
                            console.log(response);
                            $('#message-input').val('');
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }

                $('#send-button').click(function(e) {
                    e.preventDefault();
                    sendMessage();
                });

                $('#message-input').keypress(function(e) {
                    if (e.which == 13) {
                        e.preventDefault();
                        sendMessage();
                    }
                });
            });

            // =========end of send message======
        });
    </script>
    <!-- Auto-scroll JavaScript -->
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var chatBody = document.getElementById('chat-body');
            if (chatBody) {
                chatBody.scrollTo({
                    top: chatBody.scrollHeight,
                    behavior: 'smooth'
                });
            }
        });
    </script>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('5303216338af26cb3e15', {
            cluster: 'mt1'
        });
        var senderId = $('#senderId').val();
        var channel = pusher.subscribe('my-messenger');
        // Bind the real-time event (e.g., using Pusher or another WebSocket service)
        channel.bind('message-submitted', function(data) {
            console.log(data);
            fetchLatestNotification();
        });

        function fetchLatestNotification() {
            $.ajax({
                url: '/fetch-latest-notification',
                method: 'GET',
                data: {
                    receiver_id: $('#receiverId').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.error) {
                        console.error(response.error);
                    } else {
                        // Parse response data and render the new message
                        var data = JSON.parse(response.data);
                        var message = data.message;
                        var senderId = data.sender_id;

                        // Append the message to the correct side (left/right)
                        if (senderId == {{ auth()->id() }}) {
                            // Message from the authenticated user (right side)
                            $('.chat-body').append(
                                '<div class="chat-right"><div class="message send">' + message +
                                '</div></div>'
                            );
                        } else {
                            // Message from the other user (left side)
                            $('.chat-body').append(
                                '<div class="chat-left"><div class="message received">' + message +
                                '</div></div>'
                            );
                        }

                        // Scroll to the bottom of the chat smoothly
                        var chatBody = document.getElementById('chat-body');
                        chatBody.scrollTo({
                            top: chatBody.scrollHeight,
                            behavior: 'smooth'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }
    </script>
</body>

</html>
