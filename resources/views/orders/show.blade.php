@extends('layouts.app')

@section('content')
{{-- ⚠️ VULN #3: IDOR — halaman ini terbuka untuk semua order, bukan hanya milik user login --}}
<div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-2">✅ Order Berhasil!</h1>
    <p class="text-gray-500 mb-6">
        Order ID: <strong>#{{ $order->id }}</strong> —
        Pemilik: <strong>{{ $order->user->name ?? 'Unknown' }}</strong> —
        Status: {{ ucfirst($order->status) }}
    </p>

    {{-- ⚠️ VULN #10: Info disclosure — tampilkan user_id pemilik order --}}
    <p class="text-xs text-gray-400 mb-4">User ID: {{ $order->user_id }}</p>

    @foreach($order->items as $item)
        <div class="flex justify-between py-2 border-b">
            {{-- ⚠️ VULN #2: Stored XSS --}}
            <span>{!! $item->product->name !!} x{{ $item->quantity }}</span>
            <span class="font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
        </div>
    @endforeach

    <div class="flex justify-between mt-4 text-lg font-bold">
        <span>Total</span>
        <span class="text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>

    <a href="{{ route('products.index') }}"
       class="block text-center mt-6 bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700">
        Lanjut Belanja
    </a>
</div>
@endsection
