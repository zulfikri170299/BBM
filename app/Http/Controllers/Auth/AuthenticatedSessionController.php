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
        $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
        $mobileHeroImage = \App\Models\Setting::where('key', 'mobile_hero_image')->value('value') ?? '';
        $heroFit = \App\Models\Setting::where('key', 'mobile_hero_fit')->value('value') ?? 'cover';
        $heroZoom = \App\Models\Setting::where('key', 'mobile_hero_zoom')->value('value') ?? '100';
        $heroBg = \App\Models\Setting::where('key', 'mobile_hero_bg')->value('value') ?? 'transparent';
        return view('auth.login', compact('personelAccessControl', 'mobileHeroImage', 'heroFit', 'heroZoom', 'heroBg'));
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

        $redirectTo = $request->input('redirect_to') 
            ?? $request->query('redirect_to') 
            ?? $request->cookie('simbbm_redirect_to') 
            ?? session('simbbm_redirect_to');

        // Bersihkan cookie redirect jika ada
        \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('simbbm_redirect_to'));
        session()->forget('simbbm_redirect_to');

        if (!empty($redirectTo)) {
            $user = Auth::user();
            $target = strtolower(trim($redirectTo));
            
            $targetRoute = match ($target) {
                'kendaraan', 'kendaraans' => match ($user->role) {
                    'super_admin', 'kasubbag', 'admin' => 'admin.kendaraans.index',
                    'admin_satker', 'satker' => 'satker.kendaraans.index',
                    'petugas_bbm', 'petugas' => 'petugas.dashboard',
                    default => 'dashboard',
                },
                'hutang' => match ($user->role) {
                    'super_admin', 'kasubbag', 'admin' => 'admin.hutang.index',
                    'admin_satker', 'satker' => 'satker.hutang.index',
                    'petugas_bbm', 'petugas' => 'petugas.hutang.index',
                    default => 'dashboard',
                },
                'riwayat', 'riwayat bbm' => match ($user->role) {
                    'super_admin', 'kasubbag', 'admin' => 'admin.riwayat.index',
                    'admin_satker', 'satker' => 'satker.riwayat.index',
                    'petugas_bbm', 'petugas' => 'petugas.riwayat.index',
                    default => 'dashboard',
                },
                'transfer' => match ($user->role) {
                    'super_admin', 'kasubbag', 'admin' => 'admin.transfer-saldo.index',
                    'admin_satker', 'satker' => 'satker.kendaraans.index',
                    'petugas_bbm', 'petugas' => 'petugas.dashboard',
                    default => 'dashboard',
                },
                'persetujuan', 'persetujuan kendaraan' => match ($user->role) {
                    'super_admin', 'kasubbag', 'admin' => 'admin.persetujuan-kendaraan.index',
                    'admin_satker', 'satker' => 'satker.persetujuan-kendaraan.index',
                    'petugas_bbm', 'petugas' => 'petugas.dashboard',
                    default => 'dashboard',
                },
                default => null,
            };

            if ($targetRoute && \Illuminate\Support\Facades\Route::has($targetRoute)) {
                Log::info("Auth Redirect: user {$user->email} ({$user->role}) menuju {$targetRoute} via {$target}");
                $routeParams = [];
                if ($target === 'transfer' && in_array($user->role, ['admin_satker', 'satker'])) {
                    $routeParams['open_transfer'] = 1;
                }
                return redirect()->route($targetRoute, $routeParams)->withCookie(\Illuminate\Support\Facades\Cookie::forget('simbbm_redirect_to'));
            }
        }

        return redirect()->route('dashboard');
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
