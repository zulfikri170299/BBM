<?php

namespace App\Http\Controllers;

use App\Models\SatisfactionIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SatisfactionIndexController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|in:1,2,3',
            'note' => 'nullable|string|max:255',
        ]);

        SatisfactionIndex::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'note' => $request->note,
        ]);

        return back()->with('success', 'Terima kasih atas penilaian Anda!');
    }
}
