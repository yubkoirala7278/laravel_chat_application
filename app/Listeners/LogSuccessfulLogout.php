<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class LogSuccessfulLogout
{
    public function handle(Logout $event)
    {
        $user = $event->user;

        if ($user) {
            // Remove the user's online status from the cache
            Cache::forget('user-is-online-' . $user->id);

            // Update the last seen time
            $user->last_seen_at = now();
            $user->save();
        }
    }
}

