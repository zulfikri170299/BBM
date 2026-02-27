<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\LogAktivitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        LogAktivitas::create([
            'user_id' => $request->user()->id,
            'aktivitas' => 'Memperbarui profil akun (Nama/Email/Username)'
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's topup password.
     */
    public function updateTopupPassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Jika sudah punya topup_password, verifikasi pakai password top up lama
        // Jika pertama kali (belum punya), verifikasi pakai password login
        if ($user->topup_password) {
            $request->validateWithBag('updateTopupPassword', [
                'password' => ['required'],
                'topup_password' => ['required', 'min:6', 'confirmed'],
            ]);

            // Cek password top up lama
            if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->topup_password)) {
                return back()->withErrors(['password' => 'Password Top Up lama salah.'], 'updateTopupPassword');
            }
        } else {
            // Pertama kali: pakai password login
            $request->validateWithBag('updateTopupPassword', [
                'password' => ['required', 'current_password'],
                'topup_password' => ['required', 'min:6', 'confirmed'],
            ]);
        }

        $user->update([
            'topup_password' => \Illuminate\Support\Facades\Hash::make($request->topup_password),
        ]);

        LogAktivitas::create([
            'user_id' => $user->id,
            'aktivitas' => 'Memperbarui Password Top Up'
        ]);

        return back()->with('status', 'topup-password-updated');
    }

    /**
     * Reset the user's topup password to default ("zulfikri").
     */
    public function resetTopupPassword(Request $request): RedirectResponse
    {
        $request->user()->update([
            'topup_password' => \Illuminate\Support\Facades\Hash::make('zulfikri'),
        ]);

        LogAktivitas::create([
            'user_id' => $request->user()->id,
            'aktivitas' => 'Reset Password Top Up ke default'
        ]);

        return back()->with('status', 'topup-password-reset');
    }

    /**
     * Update user location.
     */
    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $request->user()->update([
            'last_latitude' => $validated['latitude'],
            'last_longitude' => $validated['longitude'],
            'last_activity_at' => now(),
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:1024'], // 1MB Max
        ]);

        $user = $request->user();

        if ($request->file('photo')) {
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->update(['profile_photo_path' => $path]);
            
            LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => 'Memperbarui foto profil'
            ]);
        }

        return back()->with('status', 'photo-updated');
    }
}
