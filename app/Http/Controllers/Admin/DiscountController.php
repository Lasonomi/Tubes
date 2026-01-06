<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Tampilkan halaman manage diskon (list + form tambah/edit dalam satu view)
     */
    public function index(Request $request)
    {
        // Ambil semua diskon beserta produknya
        $discounts = Discount::with('product')->latest()->get();

        // Untuk mode edit: kalau ada query ?edit=1
        $editDiscount = null;
        if ($request->has('edit')) {
            $editDiscount = Discount::findOrFail($request->query('edit'));
        }

        return view('admin.discounts', compact('discounts', 'editDiscount'));
    }

    /**
     * Simpan diskon baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'  => 'required|exists:products,id|unique:discounts,product_id', // satu produk satu diskon saja
            'percentage'  => 'required|integer|min:1|max:100',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        Discount::create($validated);

        return redirect()
            ->route('admin.discounts')
            ->with('success', 'Diskon berhasil ditambahkan!');
    }

    /**
     * Update diskon yang sudah ada
     */
    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'product_id'  => 'required|exists:products,id|unique:discounts,product_id,' . $discount->id,
            'percentage'  => 'required|integer|min:1|max:100',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $discount->update($validated);

        return redirect()
            ->route('admin.discounts')
            ->with('success', 'Diskon berhasil diperbarui!');
    }

    /**
     * Hapus diskon
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect()
            ->route('admin.discounts')
            ->with('success', 'Diskon berhasil dihapus!');
    }
}