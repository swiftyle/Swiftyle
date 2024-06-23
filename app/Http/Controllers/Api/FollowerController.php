<?php

namespace App\Http\Controllers\Api;

use App\Events\NewFollower;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
                $followable = Shop::find($followableId);
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

        // Check if the user is trying to unfollow themselves
        if ($user->id == $followableId) {
            return response()->json(['message' => 'Cannot unfollow yourself'], 422);
        }

        // Check if the followable entity exists
        $followable = null;
        switch ($followableType) {
            case 'shop':
                $followable = Shop::find($followableId);
                break;
            case 'user':
                $followable = User::find($followableId);
                break;
        }

        if (!$followable) {
            return response()->json(['message' => ucfirst($followableType) . ' not found'], 404);
        }

        // Check if the user is following
        if (!$user->followings()->where('followable_id', $followableId)->where('followable_type', ucfirst($followableType))->exists()) {
            return response()->json(['message' => 'Not following this ' . $followableType], 422);
        }

        // Remove the follow relationship
        $user->followings()->detach($followableId, ['followable_type' => ucfirst($followableType)]);

        return response()->json(['message' => 'Unfollowed this ' . $followableType], 200);
    }

    public function read(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Retrieve the followers of the authenticated user
        $followers = DB::table('followers')
            ->where('follower_id', $user->id)
            ->get();

        return response()->json([
            'followers' => $followers
        ], 200);
    }

    public function delete(Request $request, $followableId)
    {
        // Get the authenticated user
        $user = $request->user();
    
        // Check if the follower exists and belongs to the authenticated user
        $follower = DB::table('followers')
            ->where('follower_id', $user->id)
            ->where('followable_id', $followableId)
            ->first();
    
        // If follower exists and belongs to the authenticated user, delete it
        if ($follower) {
            DB::table('followers')
                ->where('follower_id', $user->id)
                ->where('followable_id', $followableId)
                ->delete();
    
            return response()->json([
                'message' => 'Unfollow successful'
            ], 200);
        }
    
        // If follower doesn't exist or doesn't belong to the authenticated user, return error
        return response()->json([
            'error' => 'Follower not found or unauthorized'
        ], 404);
    }
}