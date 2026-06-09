@extends('layouts.app')

@section('content')
    {{-- ⚠️ VULN #4: Broken Access Control — siapapun yang login bisa edit produk --}}
    <div class="bg-white rounded-xl shadow p-6 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">✏️ Edit Produk</h1>

        <form method="POST" action="/admin/products/{{ $product->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="category_id" class="w-full border rounded-lg px-3 py-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Harga</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            {{-- ⚠️ VULN #6: Insecure File Upload — tidak ada validasi tipe file di sini --}}
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Foto Produk (semua tipe file diterima)</label>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="h-20 mb-2 rounded">
                @endif
                <input type="file" name="image" class="w-full border rounded-lg px-3 py-2">
            </div>

            <button type="submit"
                    class="w-full bg-yellow-500 text-white py-3 rounded-xl hover:bg-yellow-600 font-semibold">
                Update Produk
            </button>
        </form>
    </div>
@endsection
