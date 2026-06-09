<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elektronik = Category::create(['name' => 'Elektronik']);
        $fashion = category::create(['name' => 'Fashion']);

        Product::create([
            'category_id' => $elektronik->id,
            'name' => 'Headphone Wireles',
            'description' => 'headphone dengan kualitas suara yang sanagt premium',
            'price' => 500000,
            'stock' => 50
        ]);

        Prduct::create([
            'category_id' => $fashion->id,
            'name' => 'Baju',
            'description' => 'baju dengan kualitas terbaik',
            'price' => 100000
            'stock' => 100,
            
        ]);

        Product::create([
            'category_id' => $elektronik->id,
            'name' => 'Iphone 15 Pro Max '
            'description' => 'hp terbaik dengan kualitas terbaik pada masa nya',
            'price' => 20000000,
            'stock' => 20,
        ]);

        Product::create([
            'category_id' => $fashion->id,
            'name' => 'kaos Polos Premium',
            'description' => 'kaos bahan cottom combed 30s',
            'price' => 100000
            'stock' => 200
        ]);
        
    }
}
