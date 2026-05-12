<?php

namespace Database\Seeders;

use App\Models\DonationCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DonationCategorySeeder extends Seeder
{
    public function run(): void
    {
        $cats = [
            ['Gau Bhojan Seva',           '🌾', 'Feed the entire Goshala for a day with fresh fodder, oil cakes and water.', 1100, [501, 1100, 2100, 5100], true],
            ['Adopt a Cow (Monthly)',     '🐄', 'Monthly support for a specific cow\'s health, food and care. Receive photo updates.', 2100, [1100, 2100, 5100, 11000], true],
            ['Medical Emergency Fund',    '🩺', 'Help us buy medicines, vaccines, and equipment for injured and elderly cattle.', 2100, [501, 1100, 2100, 5100], false],
            ['Construction Support',      '🛖', 'Help us expand our sheds and build weather-proof shelters for more rescued souls.', 11000, [5100, 11000, 21000, 51000], false],
            ['Festival Gau Pujan',        '🪔', 'Celebrate your special day with a Gau Pujan ceremony in your name. Includes blessings & prasad.', 5100, [2100, 5100, 11000, 21000], true],
            ['Annadan (Daily Feeding)',   '🥣', 'Sponsor one full day of meals for all rescued cows in our Goshala.', 5100, [1100, 2100, 5100, 11000], false],
            ['Water & Borewell Seva',     '💧', 'Help us maintain clean drinking water through our solar pumps and borewell systems.', 1100, [501, 1100, 2100, 5100], false],
            ['Cow Ambulance Fund',        '🚑', 'Support our 24/7 cow rescue ambulance covering Mathura and surrounding districts.', 2100, [1100, 2100, 5100, 11000], false],
            ['Gau Daan (Lifetime Sponsor)', '🌟', 'Premium spiritual offering — lifetime sponsorship for one cow with annual photo updates.', 51000, [21000, 51000, 101000, 251000], false],
        ];

        foreach ($cats as $i => [$name, $icon, $desc, $default, $suggested, $featured]) {
            DonationCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'icon' => $icon,
                    'short_description' => $desc,
                    'description' => $desc,
                    'default_amount' => $default,
                    'suggested_amounts' => $suggested,
                    'is_active' => true,
                    'is_featured' => $featured,
                    'sort_order' => $i,
                ],
            );
        }
    }
}
