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
        // Require current login password for security
        $validated = $request->validateWithBag('updateTopupPassword', [
            'password' => ['required', 'current_password'],
            'topup_password' => ['required', 'min:6', 'confirmed'],
        ]);

        $request->user()->update([
            'topup_password' => \Illuminate\Support\Facades\Hash::make($validated['topup_password']),
        ]);

        LogAktivitas::create([
            'user_id' => $request->user()->id,
            'aktivitas' => 'Memperbarui Password Top Up'
        ]);

        return back()->with('status', 'topup-password-updated');
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
}
