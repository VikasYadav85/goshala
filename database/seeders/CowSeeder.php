<?php

namespace Database\Seeders;

use App\Models\Cow;
use App\Models\CowCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CowSeeder extends Seeder
{
    public function run(): void
    {
        $rescued = CowCategory::where('slug', 'rescued')->first();
        $gir     = CowCategory::where('slug', 'gir')->first();
        $sahiwal = CowCategory::where('slug', 'sahiwal')->first();
        $elderly = CowCategory::where('slug', 'elderly')->first();
        $calf    = CowCategory::where('slug', 'calf')->first();

        $cows = [
            [
                'name' => 'Nandi (The Brave)',
                'category_id' => $rescued?->id,
                'breed' => 'Indian Desi',
                'age' => '7 years',
                'gender' => 'male',
                'color' => 'White & Grey',
                'rescued_at' => now()->subYears(2)->toDateString(),
                'rescue_story' => 'Rescued from a highway accident near Mathura. Sustained leg injuries that took 8 months to heal. Today, he is the proud leader of our herd.',
                'description' => 'Nandi loves jaggery, evening aarti, and walks with the morning volunteers.',
                'monthly_sponsorship_amount' => 3100,
                'is_featured' => true,
            ],
            [
                'name' => 'Gauri (The Gentle)',
                'category_id' => $gir?->id,
                'breed' => 'Gir',
                'age' => '5 years',
                'gender' => 'female',
                'color' => 'Brown & White',
                'rescued_at' => now()->subYears(1)->toDateString(),
                'rescue_story' => 'Found abandoned in a city dump in Agra. Severely undernourished. Now a healthy mother to twin calves born at our Goshala.',
                'description' => 'Gauri has a special love for oil-cakes (khali) and never misses morning aarti.',
                'monthly_sponsorship_amount' => 2500,
                'is_featured' => true,
            ],
            [
                'name' => 'Lakshmi',
                'category_id' => $sahiwal?->id,
                'breed' => 'Sahiwal',
                'age' => '4 years',
                'gender' => 'female',
                'color' => 'Reddish brown',
                'rescued_at' => now()->subMonths(8)->toDateString(),
                'rescue_story' => 'Rescued from a slaughterhouse-bound truck. Today, she is one of the calmest cows in the Goshala.',
                'monthly_sponsorship_amount' => 2100,
                'is_featured' => true,
            ],
            [
                'name' => 'Govinda',
                'category_id' => $calf?->id,
                'breed' => 'Indian Desi',
                'age' => '8 months',
                'gender' => 'male',
                'color' => 'White',
                'rescue_story' => 'Born at our Goshala to mother Gauri. Loves running with the volunteers.',
                'monthly_sponsorship_amount' => 1500,
                'is_featured' => true,
            ],
            [
                'name' => 'Radha',
                'category_id' => $gir?->id,
                'breed' => 'Gir',
                'age' => '6 years',
                'gender' => 'female',
                'rescue_story' => 'Found injured near a temple in Vrindavan. Leg surgery successful — fully active now.',
                'monthly_sponsorship_amount' => 2500,
            ],
            [
                'name' => 'Arjun',
                'category_id' => $rescued?->id,
                'breed' => 'Indian Desi',
                'age' => '3 years',
                'gender' => 'male',
                'rescue_story' => 'Rescued from highway abandonment. Fully rehabilitated.',
                'monthly_sponsorship_amount' => 2100,
            ],
            [
                'name' => 'Tulsi',
                'category_id' => $elderly?->id,
                'breed' => 'Indian Desi',
                'age' => '15 years',
                'gender' => 'female',
                'rescue_story' => 'Elderly mother brought to us when her former owners could no longer care for her. Lives a peaceful retired life.',
                'monthly_sponsorship_amount' => 1800,
            ],
            [
                'name' => 'Kanha',
                'category_id' => $calf?->id,
                'breed' => 'Indian Desi',
                'age' => '5 months',
                'gender' => 'male',
                'rescue_story' => 'Newborn calf rescued with his mother from an unsafe shelter.',
                'monthly_sponsorship_amount' => 1500,
            ],
        ];

        foreach ($cows as $i => $data) {
            Cow::updateOrCreate(
                ['slug' => Str::slug($data['name']) . '-demo'],
                $data + [
                    'slug' => Str::slug($data['name']) . '-demo',
                    'sort_order' => $i,
                    'is_available_for_sponsorship' => true,
                    'status' => 'active',
                ],
            );
        }
    }
}
