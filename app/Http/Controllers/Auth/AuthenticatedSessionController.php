<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LogAktivitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melakukan Login Aplikasi'
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Handle an incoming face authentication request.
     */
    public function storeFaceLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'face_descriptor' => ['required', 'string'],
        ]);

        $submittedDescriptorArray = json_decode($request->face_descriptor, true);

        if (!is_array($submittedDescriptorArray) || count($submittedDescriptorArray) !== 128) {
            Log::warning('[FaceLogin] Invalid descriptor submitted. Length: ' . (is_array($submittedDescriptorArray) ? count($submittedDescriptorArray) : 'not array'));
            return back()->with('error', 'Data wajah tidak valid.');
        }

        $users = \App\Models\User::whereNotNull('face_descriptor')->get();
        
        Log::info('[FaceLogin] Comparing against ' . $users->count() . ' registered faces');
        
        $matchedUser = null;
        $minDistance = 0.6; // Threshold for face matching (increased for better tolerance)
        $bestDistance = PHP_FLOAT_MAX;

        foreach ($users as $user) {
            $userDescriptor = json_decode($user->face_descriptor, true);
            if (!is_array($userDescriptor) || count($userDescriptor) !== 128) {
                Log::warning('[FaceLogin] User ' . $user->id . ' has invalid descriptor');
                continue;
            }

            // Calculate Euclidean distance
            $distance = 0;
            for ($i = 0; $i < 128; $i++) {
                $distance += pow($submittedDescriptorArray[$i] - $userDescriptor[$i], 2);
            }
            $distance = sqrt($distance);

            Log::info('[FaceLogin] Distance to user ' . $user->id . ' (' . $user->name . '): ' . round($distance, 4));

            if ($distance < $bestDistance) {
                $bestDistance = $distance;
            }

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $matchedUser = $user;
            }
        }

        if ($matchedUser) {
            Log::info('[FaceLogin] Match found! User: ' . $matchedUser->name . ' (distance: ' . round($minDistance, 4) . ')');
            Auth::login($matchedUser);
            $request->session()->regenerate();

            LogAktivitas::create([
                'user_id' => $matchedUser->id,
                'aktivitas' => 'Melakukan Login Aplikasi via Scan Wajah'
            ]);

            return redirect()->route('dashboard');
        }

        Log::warning('[FaceLogin] No match. Best distance was: ' . round($bestDistance, 4) . ' (threshold: 0.6). Registered faces: ' . $users->count());
        return back()->with('error', 'Wajah tidak dikenali atau belum terdaftar.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melakukan Logout Aplikasi'
        ]);

        \Illuminate\Support\Facades\Cache::forget('user-is-online-' . Auth::id());

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
