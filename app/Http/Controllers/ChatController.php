<?php

namespace App\Http\Controllers;

use App\Events\MessageSend;
use App\Models\User;
use App\Notifications\MessageSendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function chat($receiverId)
    {
        try {
            $users = User::where('id', '!=', Auth::id())->get();
            $authUserId = auth()->id();
            $receiverName = User::where('id', $receiverId)->value('name');

            // Get messages where the authenticated user is either the sender or the receiver
            $notifications = DB::table('notifications')
                ->where(function ($query) use ($authUserId, $receiverId) {
                    $query->where(function ($query) use ($authUserId, $receiverId) {
                        $query->where('data->sender_id', $authUserId)
                            ->where('data->receiver_id', $receiverId);
                    })
                        ->orWhere(function ($query) use ($authUserId, $receiverId) {
                            $query->where('data->sender_id', $receiverId)
                                ->where('data->receiver_id', $authUserId);
                        });
                })
                ->orderBy('created_at', 'asc')
                ->get();
            // dd($notifications);
            return view('chat', compact('users', 'receiverId', 'notifications','receiverName'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function fetchLatestNotification(Request $request)
    {
        try {
            $authUserId = auth()->id();
            $receiverId = $request->query('receiver_id'); // Use query parameter for GET request

            // Fetch the latest notification with the given conditions
            $notification = DB::table('notifications')
                ->where(function ($query) use ($authUserId, $receiverId) {
                    $query->where(function ($query) use ($authUserId, $receiverId) {
                        $query->where('data->sender_id', $authUserId)
                            ->where('data->receiver_id', $receiverId);
                    })
                        ->orWhere(function ($query) use ($authUserId, $receiverId) {
                            $query->where('data->sender_id', $receiverId)
                                ->where('data->receiver_id', $authUserId);
                        });
                })
                ->orderBy('created_at', 'desc') 
                ->first();


            return response()->json($notification);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        try {
            $user = User::find(Auth::id());
            $user->notify(new MessageSendNotification($request['message'], Auth::id(), $request->user_id));
            $receiverId=(int)($request->user_id);
            event(new MessageSend($user,$request['message'],$receiverId,Auth::user()->id));
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Message not sent!!']);
        }
    }

    public function messenger()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('messenger', compact('users'));
    }
}
