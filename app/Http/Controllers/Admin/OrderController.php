<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('member');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_order', 'like', "%{$search}%")
                    ->orWhere('meja', 'like', "%{$search}%")
                    ->orWhereHas('member', fn ($q2) => $q2->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $orders = $query->with(['member', 'items.produk'])
            ->withSum('items as jumlah_item', 'qty')
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.order.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_order' => 'required|string|unique:order,kode_order',
            'total_harga' => 'required|numeric|min:0',
            'diskon' => 'required|numeric|min:0',
            'member_id' => 'nullable|exists:member,id',
            'meja' => 'required|string',
            'pembayaran' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        Order::create($validated);

        return redirect()->route('admin.order.index')->with('success', 'Order berhasil ditambahkan.');
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'kode_order' => 'required|string|unique:order,kode_order,'.$order->id,
            'total_harga' => 'required|numeric|min:0',
            'diskon' => 'required|numeric|min:0',
            'member_id' => 'nullable|exists:member,id',
            'meja' => 'required|string',
            'pembayaran' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $order->update($validated);

        return redirect()->route('admin.order.index')->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.order.index')->with('success', 'Order berhasil dihapus.');
    }
}
