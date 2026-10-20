<?php

namespace App\Http\Controllers;

use App\Models\SatisfactionIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SatisfactionIndexController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|in:1,2,3',
            'note' => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();

        SatisfactionIndex::create([
            'user_id' => $userId,
            'rating' => $request->rating,
            'note' => $request->note,
        ]);

        $cacheKey = 'user_sat_' . $userId . '_' . now()->format('Ym');
        Cache::forget($cacheKey);
        Cache::put($cacheKey, true, now()->endOfMonth()->addDay());

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Terima kasih atas penilaian Anda!'
            ]);
        }

        return back()->with('success', 'Terima kasih atas penilaian Anda!');
    }
}
