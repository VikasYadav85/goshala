<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $campaigns = [
            [
                'title' => 'URGENT: Winter Blanket & Shelter Extension',
                'short_description' => 'As temperatures drop, our elderly cows and rescued calves need extra warmth. Help us fund high-quality blankets and a new weather-proof shed.',
                'description' => "Our 30 elderly cows and 12 rescued calves are at risk this winter. We need to procure 50 high-quality woolen blankets and complete construction of a new 1,200 sq.ft. weather-proof shed before the temperature drops below 5°C.\n\nFunds raised will cover:\n- Woolen blankets (₹1.2L)\n- Shed construction (₹2.5L)\n- Heating lamps for sick calves (₹50K)\n- Extra fodder reserve (₹80K)\n\nEvery contribution counts. Help us protect our Gau Mata this winter.",
                'goal_amount' => 500000,
                'raised_amount' => 325000,
                'status' => 'emergency',
                'is_emergency' => true,
                'is_featured' => true,
                'start_date' => now()->subMonth()->toDateString(),
                'end_date' => now()->addMonths(2)->toDateString(),
            ],
            [
                'title' => 'New Cow Ambulance for Mathura Region',
                'short_description' => 'A fully-equipped cow ambulance can save dozens of lives every month across Mathura and Vrindavan.',
                'description' => "We are raising funds for a fully-equipped cow ambulance to respond to emergencies across Mathura district. The ambulance will include a hydraulic lift, oxygen cylinders, IV equipment, first-aid kits, and trained handlers.\n\nThe vehicle will operate 24/7 and respond to:\n- Highway cattle accidents\n- Abandoned cows in distress\n- Medical emergencies in remote villages\n- Disaster response (floods, etc.)",
                'goal_amount' => 1200000,
                'raised_amount' => 380000,
                'status' => 'active',
                'is_featured' => true,
                'start_date' => now()->subWeeks(3)->toDateString(),
                'end_date' => now()->addMonths(4)->toDateString(),
            ],
            [
                'title' => 'Goshala Veterinary Clinic Expansion',
                'short_description' => 'We are doubling our on-site veterinary infrastructure with a fully-equipped surgery room and recovery wing.',
                'description' => "Our existing vet clinic handles 15-20 cows per day. With expansion, we will be able to serve 50+ animals daily, including surgeries, X-rays, and emergency stabilisation.",
                'goal_amount' => 800000,
                'raised_amount' => 145000,
                'status' => 'active',
                'start_date' => now()->subWeeks(2)->toDateString(),
                'end_date' => now()->addMonths(6)->toDateString(),
            ],
            [
                'title' => 'Janmashtami Annadan 2026',
                'short_description' => 'Join us for the largest Annadan of the year — feeding 5,000+ devotees and 500+ cows on Janmashtami.',
                'description' => "Annual Annadan event that brings together volunteers, devotees and trustees for a day-long celebration of feeding and seva.",
                'goal_amount' => 350000,
                'raised_amount' => 0,
                'status' => 'upcoming',
                'start_date' => now()->addMonths(2)->toDateString(),
                'end_date' => now()->addMonths(3)->toDateString(),
            ],
            [
                'title' => 'Rescued: 50 Cows from Drought-Hit District',
                'short_description' => 'Mission completed — 50 cows from Bundelkhand are now safe in our Goshala.',
                'description' => "Thanks to your generous support, we successfully rescued and rehabilitated 50 cows from a drought-hit district last summer. All cows are now healthy and integrated with our herd.",
                'goal_amount' => 600000,
                'raised_amount' => 612000,
                'status' => 'completed',
                'start_date' => now()->subMonths(8)->toDateString(),
                'end_date' => now()->subMonths(5)->toDateString(),
            ],
        ];

        foreach ($campaigns as $i => $data) {
            Campaign::updateOrCreate(
                ['slug' => Str::slug($data['title']) . '-demo'],
                $data + ['slug' => Str::slug($data['title']) . '-demo', 'sort_order' => $i],
            );
        }
    }
}
