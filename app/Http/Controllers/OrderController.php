<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ⚠️ VULN #7: Price Manipulation — total_price diterima dari client
    // Attacker bisa kirim POST /checkout dengan total_price=1
    public function checkout(Request $request)
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // ⚠️ VULN: Harga diambil dari request, bukan dihitung server-side!
        $total = $request->input('total_price') 
                 ?? $carts->sum(fn($c) => $c->product->price * $c->quantity);

        $order = DB::transaction(function () use ($carts, $total) {
            $order = Order::create([
                'user_id'     => Auth::id(),
                'total_price' => $total, // ⚠️ dari client!
                'status'      => 'pending'
            ]);

            foreach ($carts as $cartItem) {
                // ⚠️ VULN: price juga diterima dari request
                $price = request()->input('price_' . $cartItem->product_id)
                         ?? $cartItem->product->price;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity'   => $cartItem->quantity,
                    'price'      => $price, // ⚠️ dari client!
                ]);

                // ⚠️ VULN: tidak ada pengecekan stok sebelum decrement
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            Cart::where('user_id', Auth::id())->delete();

            return $order;
        });

        return redirect()->route('order.show', $order)->with('success', 'Order berhasil dibuat!');
    }

    // ⚠️ VULN #3: IDOR — tidak ada pengecekan kepemilikan order
    // User A bisa akses order milik User B dengan tebak ID
    public function show($order_id)
    {
        // Tidak ada: if ($order->user_id !== Auth::id()) abort(403)
        $order = Order::with('items.product')->find($order_id);

        if (!$order) {
            // ⚠️ VULN #12: Info disclosure — bocorkan bahwa order ID tsb tidak ada
            return response("Order #$order_id tidak ada di database.", 404);
        }

        return view('orders.show', compact('order'));
    }

    // ⚠️ VULN #3: IDOR — user biasa bisa lihat semua order semua user
    public function index()
    {
        // Tanpa filter user_id — semua order tampil!
        $orders = Order::with('items.product')->get();
        return view('orders.index', compact('orders'));
    }
}
