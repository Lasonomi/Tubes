<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman utama profile (informasi pribadi + alamat)
     */
    public function index(): View
    {
        $user = Auth::user();
        $addresses = $user->addresses()->latest()->get();

        return view('profile.index', compact('user', 'addresses'));
    }

    /**
     * Update nomor HP user
     */
        public function updatePhone(Request $request): RedirectResponse
    {
        $request->validate([
                 'phone' => [
                'required',
                'string',
                'min:10',
                'max:15',
                'regex:/^08[0-9]{8,13}$/'  // mulai 08, diikuti 8-13 digit angka
            ],
        ]);

        Auth::user()->update(['phone' => $request->phone]);

        return back()->with('success', 'Nomor HP berhasil diperbarui!');
    }

    /**
     * Display the user's profile form (Breeze default - ubah nama/email)
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information (nama & email - Breeze default)
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account (Breeze default)
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
 }