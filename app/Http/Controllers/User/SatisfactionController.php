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
        $todaySatisfaction = SatisfactionIndex::where('user_id', Auth::id())
            ->whereDate('created_at', now())
            ->exists();

        return view('user.satisfaction.create', compact('todaySatisfaction'));
    }
}
