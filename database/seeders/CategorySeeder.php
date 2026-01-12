<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Paintings', 'slug' => 'paintings'],
            ['name' => 'Drawings', 'slug' => 'drawings'],
            ['name' => 'Photographic Images', 'slug' => 'photographic-images'],
            ['name' => 'Sculptures', 'slug' => 'sculptures'],
            ['name' => 'Carvings', 'slug' => 'carvings'],
        ];

        foreach ($rows as $r) {
            DB::table('categories')->updateOrInsert(['slug' => $r['slug']], $r);
        }
    }
}
