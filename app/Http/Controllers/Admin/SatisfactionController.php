<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SatisfactionIndex;
use Illuminate\Http\Request;

class SatisfactionController extends Controller
{
    public function index()
    {
        $ratings = SatisfactionIndex::with('user.satker', 'user.personel')
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => SatisfactionIndex::count(),
            'avg' => SatisfactionIndex::avg('rating'),
            'sangat_puas' => SatisfactionIndex::where('rating', '=', '3')->count(),
            'puas' => SatisfactionIndex::where('rating', '=', '2')->count(),
            'tidak_puas' => SatisfactionIndex::where('rating', '=', '1')->count(),
        ];

        return view('admin.satisfaction.index', compact('ratings', 'stats'));
    }
}
