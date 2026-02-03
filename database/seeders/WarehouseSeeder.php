<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::create([
            'name' => 'Gudang Central Jakarta',
            'location' => 'Jakarta Pusat',
            'price_per_month' => 5000000,
            'size' => 100,
            'description' => 'Gudang strategis di pusat kota dengan akses mudah ke jalan tol.',
            'image_url' => 'https://placehold.co/600x400',
        ]);

        Warehouse::create([
            'name' => 'Gudang Logistics Bandung',
            'location' => 'Bandung',
            'price_per_month' => 3500000,
            'size' => 150,
            'description' => 'Fasilitas lengkap termasuk CCTV dan keamanan 24 jam.',
            'image_url' => 'https://placehold.co/600x400',
        ]);
        
        Warehouse::create([
            'name' => 'Gudang Dingin Surabaya',
            'location' => 'Surabaya',
            'price_per_month' => 7000000,
            'size' => 200,
            'description' => 'Cold storage cocok untuk makanan beku.',
            'image_url' => 'https://placehold.co/600x400',
        ]);
    }
}
