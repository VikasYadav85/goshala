<?php

namespace Database\Seeders;

use App\Models\CowCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CowCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Indian Desi', 'Gir', 'Sahiwal', 'Tharparkar', 'Rescued', 'Elderly', 'Calf'] as $i => $name) {
            CowCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort_order' => $i],
            );
        }
    }
}
