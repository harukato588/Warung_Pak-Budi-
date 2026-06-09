@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-6 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Tambah Produk</h1>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

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
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="category_id" class="w-full border rounded-lg px-3 py-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full border rounded-lg px-3 py-2">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Harga</label>
                <input type="number" name="price" value="{{ old('price') }}"
                       class="w-full border rounded-lg px-3 py-2 @error('price') border-red-500 @enderror">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Stok</label>
                <input type="number" name="stock" value="{{ old('stock') }}"
                       class="w-full border rounded-lg px-3 py-2 @error('stock') border-red-500 @enderror">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Foto Produk</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 font-semibold">
                Simpan Produk
            </button>
        </form>
    </div>
@endsection