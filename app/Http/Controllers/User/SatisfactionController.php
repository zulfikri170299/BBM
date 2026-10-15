<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SatisfactionIndex;
use Illuminate\Support\Facades\Auth;

class SatisfactionController extends Controller
{
    public function create()
    {
        $userId = Auth::id();
        $todayRating = SatisfactionIndex::where('user_id', $userId)
            ->whereDate('created_at', now())
            ->latest()
            ->first();

        $todaySatisfaction = (bool) $todayRating;

        $userRatings = SatisfactionIndex::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $totalRatings = SatisfactionIndex::count();
        $sangatPuasCount = SatisfactionIndex::where('rating', 3)->count();
        $puasCount = SatisfactionIndex::where('rating', 2)->count();
        $tidakPuasCount = SatisfactionIndex::where('rating', 1)->count();

        $stats = [
            'total' => $totalRatings,
            'sangat_puas' => $sangatPuasCount,
            'puas' => $puasCount,
            'tidak_puas' => $tidakPuasCount,
            'p_sangat_puas' => $totalRatings > 0 ? round(($sangatPuasCount / $totalRatings) * 100, 1) : 0,
            'p_puas' => $totalRatings > 0 ? round(($puasCount / $totalRatings) * 100, 1) : 0,
            'p_tidak_puas' => $totalRatings > 0 ? round(($tidakPuasCount / $totalRatings) * 100, 1) : 0,
        ];

        return view('user.satisfaction.create', compact('todaySatisfaction', 'todayRating', 'userRatings', 'stats'));
    }
}
