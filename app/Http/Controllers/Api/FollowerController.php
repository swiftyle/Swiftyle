<?php

namespace App\Http\Controllers\Api;

use App\Events\NewFollower;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function follow(Request $request, $followableType, $followableId)
    {
        $user = $request->user();

        // Validate $followableType
        if (!in_array($followableType, ['shop', 'user'])) {
            return response()->json(['message' => 'Invalid followable type'], 422);
        }

        // Check if the user is trying to follow themselves
        if ($user->id == $followableId) {
            return response()->json(['message' => 'Cannot follow yourself'], 422);
        }

        // Check if the followable entity exists
        $followable = null;
        switch ($followableType) {
            case 'shop':
                $followable = User::where('id', $followableId)->where('role', 'Seller')->first();
                break;
            case 'user':
                $followable = User::find($followableId);
                break;
        }

        if (!$followable) {
            return response()->json(['message' => ucfirst($followableType) . ' not found'], 404);
        }

        // Check if the user is already following
        if ($user->followings()->where('followable_id', $followableId)->where('followable_type', ucfirst($followableType))->exists()) {
            return response()->json(['message' => 'Already following this ' . $followableType], 422);
        }

        // Create the follow relationship
        $user->followings()->attach($followableId, ['followable_type' => ucfirst($followableType)]);

        // Dispatch event to send notification
        event(new NewFollower($user, $followableId, ucfirst($followableType)));

        return response()->json(['message' => 'Now following this ' . $followableType], 200);
    }

    public function unfollow(Request $request, $followableType, $followableId)
    {
        $user = $request->user();

        // Validate $followableType
        if (!in_array($followableType, ['shop', 'user'])) {
            return response()->json(['message' => 'Invalid followable type'], 422);
        }

        // Check if the followable entity exists
        $followable = null;
        switch ($followableType) {
            case 'shop':
                $followable = User::where('id', $followableId)->where('role', 'Seller')->first();
                break;
            case 'user':
                $followable = User::find($followableId);
                break;
        }

        if (!$followable) {
            return response()->json(['message' => ucfirst($followableType) . ' not found'], 404);
        }

        // Check if the user is currently following
        if (!$user->followings()->where('followable_id', $followableId)->where('followable_type', ucfirst($followableType))->exists()) {
            return response()->json(['message' => 'Not following this ' . $followableType], 422);
        }

        // Remove the follow relationship
        $user->followings()->detach($followableId);

        return response()->json(['message' => 'Unfollowed this ' . $followableType], 200);
    }
}

