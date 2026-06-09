<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        $total = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);
        return view('cart.index', compact('carts', 'total'));
    }

    // ⚠️ VULN #8: Negative Quantity — tidak ada validasi quantity, bisa negatif
    // ⚠️ VULN #7: Price Manipulation — quantity tidak dibatasi, stok tidak dicek ulang
    public function add(Request $request, $product_id)
    {
        // Tidak pakai model binding aman, ambil langsung dari DB
        $product = Product::find($product_id);

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // ⚠️ VULN: Tidak ada pengecekan stok — bisa beli melebihi stok
        // ⚠️ VULN #8: Quantity dari request diterima mentah, bisa negatif atau sangat besar
        $quantity = $request->input('quantity', 1); // Tidak divalidasi!

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            // Tidak ada batas maksimum quantity
            $cart->update(['quantity' => $cart->quantity + $quantity]);
        } else {
            Cart::create([
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
                'quantity'   => $quantity, // ⚠️ bisa -999
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang');
    }

    // ⚠️ VULN #3: IDOR — siapapun yang login bisa hapus cart orang lain
    // hanya perlu tebak ID cart
    public function remove($cart_id)
    {
        // Tidak ada pengecekan kepemilikan cart!
        $cart = Cart::find($cart_id);
        if ($cart) {
            $cart->delete();
            return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang');
        }
        return redirect()->route('cart.index')->with('error', 'Cart tidak ditemukan');
    }
}
