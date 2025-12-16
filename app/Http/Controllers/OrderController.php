<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ← Tambah ini kalau belum ada

class OrderController extends Controller
{
    public function history()
    {
        $orders = Order::where('user_id', Auth::id()) // ← pakai Auth::id()
                        ->latest()
                        ->get();

        return view('shop.orders.history', compact('orders'));
    }
}