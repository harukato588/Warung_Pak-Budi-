<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class AdminProductController extends Controller
{
    // ⚠️ VULN #4: Broken Access Control — TIDAK ada middleware admin
    // Siapapun yang sudah login bisa akses halaman ini
    public function create()
    {
        $categories = Category::all();
        return view('admin.create', compact('categories'));
    }

    // ⚠️ VULN #6: Insecure File Upload — tidak ada validasi ekstensi file
    // Attacker bisa upload file .php sebagai webshell
    // ⚠️ VULN #5: Mass Assignment — $request->all() langsung dimasukkan
    public function store(Request $request)
    {
        // Validasi minimal — ekstensi file TIDAK dicek!
        $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            // ⚠️ 'image' => 'image' — TIDAK ada! Semua tipe file diterima
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // ⚠️ VULN #6: Simpan file dengan nama aslinya, tidak ada validasi tipe
            $filename = $file->getClientOriginalName(); // nama asli dari attacker
            $imagePath = $file->storeAs('products', $filename, 'public');
        }

        // ⚠️ VULN #5: Mass Assignment — semua field dari request diterima
        $data = $request->except(['_token']);
        $data['image'] = $imagePath;

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    // ⚠️ VULN #4: Broken Access Control — user biasa bisa hapus produk manapun
    public function destroy($id)
    {
        Product::find($id)?->delete();
        return redirect()->route('products.index')->with('success', 'Produk dihapus');
    }

    // ⚠️ VULN #4: user biasa bisa edit produk manapun
    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $filename  = $file->getClientOriginalName(); // ⚠️ nama file dari attacker
            $imagePath = $file->storeAs('products', $filename, 'public');
        }

        // ⚠️ VULN #5: Mass Assignment
        $data = $request->except(['_token', '_method']);
        $data['image'] = $imagePath;
        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk diupdate');
    }
}
