@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">🛒 Cart Kamu</h1>

@if($carts->isEmpty())
    <p class="text-gray-500">Cart kamu kosong.</p>
@else
    <div class="bg-white rounded-xl shadow overflow-hidden">
        @foreach($carts as $cart)
            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    {{-- ⚠️ VULN #2: Stored XSS — nama produk tidak di-escape --}}
                    <p class="font-semibold">{!! $cart->product->name !!}</p>
                    <p class="text-gray-500 text-sm">
                        Rp {{ number_format($cart->product->price, 0, ',', '.') }} x {{ $cart->quantity }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <p class="font-bold text-blue-600">
                        Rp {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}
                    </p>

                    {{-- ⚠️ VULN #3 & #9: IDOR — cart_id bisa milik user lain, tidak ada CSRF --}}
                    <form method="POST" action="/cart/remove/{{ $cart->id }}">
                        {{-- Tidak ada @csrf — CSRF vulnerability --}}
                        <button class="text-red-500 hover:text-red-700">🗑️</button>
                    </form>
                </div>
            </div>
        @endforeach

        <div class="p-4 flex justify-between items-center">
            <p class="text-lg font-bold">Total: Rp {{ number_format($total, 0, ',', '.') }}</p>

            {{-- ⚠️ VULN #7: Price Manipulation — total_price dikirim sebagai hidden field --}}
            <form method="POST" action="{{ route('orders.checkout') }}">
                @csrf
                {{-- Attacker bisa ubah nilai ini via DevTools / Burp Suite --}}
                <input type="hidden" name="total_price" value="{{ $total }}">
                <button class="bg-green-600 text-white px-6 py-2 rounded-xl hover:bg-green-700 font-semibold">
                    Checkout ✅
                </button>
            </form>
        </div>
    </div>
@endif
@endsection