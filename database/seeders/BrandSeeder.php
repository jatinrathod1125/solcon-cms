<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        Brand::updateOrCreate(
            ['code' => 'SOL'],
            [
                'name' => 'Solcon',
                'slug' => 'solcon',
                'is_active' => true,
            ]
        );

        Brand::updateOrCreate(
            ['code' => 'FIX'],
            [
                'name' => 'Fixora',
                'slug' => 'fixora',
                'is_active' => true,
            ]
        );
    }
}
