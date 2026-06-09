@extends('layouts.app')

@section('content')
    {{-- ⚠️ VULN #2: Reflected XSS — $search tidak di-escape, ditampilkan langsung --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Semua Produk</h1>
        <form method="GET" action="/products" class="flex gap-2">
            <input type="text" name="search" value="{!! $search !!}"
                   placeholder="Cari produk..."
                   class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Cari</button>
        </form>
    </div>

    {{-- Tampilkan keyword pencarian — ⚠️ XSS: tidak pakai {{ }}, pakai {!! !!} --}}
    @if($search)
        <p class="mb-4 text-gray-600">
            Hasil pencarian untuk: <strong>{!! $search !!}</strong>
        </p>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-xl shadow p-4">
                @if($product->image ?? null)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         class="w-full h-48 object-cover rounded mb-3">
                @else
                    <div class="w-full h-48 bg-gray-200 rounded mb-3 flex items-center justify-center text-gray-400">
                        No Image
                    </div>
                @endif

                {{-- ⚠️ VULN #2: Stored XSS — nama produk tidak di-escape --}}
                <h2 class="font-semibold text-lg">{!! $product->name !!}</h2>
                <p class="text-gray-500 text-sm">{!! $product->category_name ?? '' !!}</p>
                <p class="text-blue-600 font-bold mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                <a href="/products/{{ $product->id }}"
                   class="block mt-3 text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Lihat Detail
                </a>
            </div>
        @empty
            <p class="text-gray-500 col-span-3">Belum ada produk.</p>
        @endforelse
    </div>
@endsection