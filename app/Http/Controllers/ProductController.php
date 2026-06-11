<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //  VULN #1: SQL Injection — input langsung dimasukkan ke raw query tanpa sanitasi
    // VULN #2: Reflected XSS — $search dikembalikan ke view tanpa escaping
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        // SQL INJECTION: raw query tanpa prepared statement
        $products = DB::select("SELECT products.*, categories.name as category_name
                                FROM products
                                LEFT JOIN categories ON products.category_id = categories.id
                                WHERE products.name LIKE '%" . $search . "%'
                                   OR products.description LIKE '%" . $search . "%'");

        return view('products.index', compact('products', 'search'));
    }

    //  VULN #3: IDOR — tidak ada pengecekan apakah produk aktif atau milik siapa
    public function show(Request $request, $id)
    {
        // Tidak pakai model binding yang aman, langsung query dengan ID mentah
        $product = DB::select("SELECT products.*, categories.name as category_name
                               FROM products
                               LEFT JOIN categories ON products.category_id = categories.id
                               WHERE products.id = " . $id)[0] ?? null;

        if (!$product) {
            //  VULN #12: Information Disclosure — pesan error detail
            abort(404, "Product dengan ID $id tidak ditemukan di database.");
        }

        return view('products.show', compact('product'));
    }
}
