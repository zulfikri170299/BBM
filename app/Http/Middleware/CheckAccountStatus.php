<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Cek Status Individu (Prioritas Utama)
            if (!$user->is_active) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan oleh Administrator. Silakan hubungi admin untuk aktivasi kembali.');
            }

            $settings = Schema::hasTable('settings')
                ? Setting::query()->pluck('value', 'key')
                : collect();
            
            // Cek Akun Satker (Global)
            if ($user->role === 'admin_satker') {
                $isEnabled = $settings['is_satker_enabled'] ?? '1';
                if ($isEnabled == '0') {
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Akses akun Satker sedang dinonaktifkan sementara oleh Super Admin.');
                }
            }

            // Cek Akun Personel (Global)
            if ($user->role === 'personel') {
                $isEnabled = $settings['is_personel_enabled'] ?? '1';
                $personelAccessControl = $settings['personel_access_control'] ?? '1';
                if ($isEnabled == '0' || $personelAccessControl == '0') {
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Akses Ditolak');
                }
            }
        }

        return $next($request);
    }
}
