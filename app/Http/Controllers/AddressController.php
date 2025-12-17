<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Simpan alamat baru untuk user yang login
     */

    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'full_address'   => 'required|string',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'required|string|max:10',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Jika ini alamat pertama user, otomatis jadi primary
        if (Auth::user()->addresses()->count() === 0) {
            $data['is_primary'] = true;
        } else {
            $data['is_primary'] = false;
        }

        Address::create($data);

        return back()->with('success', 'Alamat baru berhasil ditambahkan!');
    }

    /**
     * Jadikan alamat tertentu sebagai alamat utama (primary)
     */
    public function setPrimary(Address $address)
    {
        // Keamanan: pastikan alamat ini milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Reset semua alamat user jadi non-primary
        Auth::user()->addresses()->update(['is_primary' => false]);

        // Set alamat ini jadi primary
        $address->update(['is_primary' => true]);

        return back()->with('success', 'Alamat utama berhasil diubah!');
    }

    /**
     * Hapus alamat
     */
    public function destroy(Address $address)
    {
        // Keamanan: pastikan alamat ini milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus!');
    }
}