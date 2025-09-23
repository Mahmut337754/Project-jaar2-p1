<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite status for an event
     */
    public function toggle(Request $request, Event $event): JsonResponse
    {
        $user = Auth::user();
        
        $favorite = UserFavorite::where('user_id', $user->id)
                               ->where('event_id', $event->id)
                               ->first();
        
        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            $isFavorited = false;
            $message = 'Removed from favorites';
        } else {
            // Add to favorites
            UserFavorite::create([
                'user_id' => $user->id,
                'event_id' => $event->id
            ]);
            $isFavorited = true;
            $message = 'Added to favorites';
        }
        
        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
            'message' => $message
        ]);
    }

    /**
     * Show user's favorite events
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $favoriteEvents = $user->favoriteEvents()
                              ->with(['activeTickets'])
                              ->where('is_active', true)
                              ->where('status', 'upcoming')
                              ->where('start_date', '>', now())
                              ->orderBy('start_date')
                              ->get();
        
        return view('favorites.index', compact('favoriteEvents'));
    }
}
