@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto">

    {{-- ⚠️ VULN #2: Stored XSS — gambar URL dan nama tidak di-escape --}}
    @if($product->image ?? null)
        <img src="{{ asset('storage/' . $product->image) }}"
             class="w-full h-64 object-cover rounded mb-4">
    @endif

    {{-- ⚠️ VULN #2: Stored XSS — nama dan deskripsi ditampilkan tanpa sanitasi --}}
    <h1 class="text-2xl font-bold">{!! $product->name !!}</h1>
    <p class="text-gray-500 mb-2">{!! $product->category_name ?? 'Tanpa Kategori' !!}</p>
    <p class="text-blue-600 text-xl font-bold mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

    {{-- ⚠️ VULN #2: Stored XSS — deskripsi bisa berisi script berbahaya --}}
    <div class="text-gray-700 mb-6">{!! $product->description !!}</div>

    <p class="text-sm text-gray-500 mb-4">Stok: {{ $product->stock }}</p>

    @auth
        @if($product->stock > 0)
            {{-- ⚠️ VULN #8: quantity dari form tidak divalidasi --}}
            <form method="POST" action="/cart/{{ $product->id }}">
                @csrf
                <div class="flex gap-3 mb-3">
                    <input type="number" name="quantity" value="1"
                           class="border rounded px-3 py-2 w-24"
                           placeholder="Qty">
                        {{-- ⚠️ Tidak ada validasi min/max — bisa isi -999 atau 99999 --}}
                </div>
                <button class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 font-semibold">
                    🛒 Tambah ke Cart
                </button>
            </form>
        @else
            <button disabled class="w-full bg-gray-400 text-white py-3 rounded-xl font-semibold cursor-not-allowed">
                Stok Habis
            </button>
        @endif
    @else
        <a href="{{ route('login') }}"
           class="block text-center bg-gray-400 text-white py-3 rounded-xl hover:bg-gray-500">
            Login untuk Beli
        </a>
    @endauth

    {{-- ⚠️ VULN #4: Tombol admin tampil untuk semua user yang login --}}
    @auth
        <div class="mt-6 border-t pt-4">
            <p class="text-xs text-gray-400 mb-2">Panel Admin (tidak ada pengecekan role):</p>
            <div class="flex gap-2">
                <a href="/admin/products/{{ $product->id }}/edit"
                   class="text-sm bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">
                    ✏️ Edit Produk
                </a>
                <form method="POST" action="/admin/products/{{ $product->id }}">
                    @csrf
                    @method('DELETE')
                    <button class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                        🗑️ Hapus Produk
                    </button>
                </form>
            </div>
        </div>
    @endauth
</div>
@endsection
