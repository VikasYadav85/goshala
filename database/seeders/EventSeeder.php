<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'Janmashtami Annadan & Gau Pujan 2026',
                'type' => 'festival',
                'short_description' => 'Day-long celebration of Lord Krishna\'s birth — Annadan, cow pujan, bhajan and prasad distribution.',
                'description' => "All-day celebration starting with morning aarti, followed by special Gau Pujan, bhajans, prasad distribution and an evening cultural programme.\n\nDevotees from across Bharat are invited. Free Annadan from 11am to 5pm.",
                'starts_at' => now()->addDays(45)->setTime(6, 0),
                'ends_at' => now()->addDays(45)->setTime(21, 0),
                'venue' => 'Gopal Seva Goshala, Vrindavan',
                'address' => 'Vrindavan, Mathura, Uttar Pradesh',
                'capacity' => 5000,
                'status' => 'upcoming',
                'is_featured' => true,
            ],
            [
                'title' => 'Gopashtami — The Sacred Day of Cows',
                'type' => 'festival',
                'short_description' => 'Celebrate the day Lord Krishna was officially given charge of the cows. Special pujan and decoration of all our cows.',
                'description' => 'Gopashtami is when Lord Krishna was given the responsibility of the cows. We honour this day with full pujan, decoration of every cow, and a special bhog.',
                'starts_at' => now()->addDays(60)->setTime(6, 0),
                'ends_at' => now()->addDays(60)->setTime(20, 0),
                'venue' => 'Gopal Seva Goshala',
                'capacity' => 1500,
                'status' => 'upcoming',
                'is_featured' => true,
            ],
            [
                'title' => 'Govardhan Puja & Annakut',
                'type' => 'festival',
                'short_description' => 'Symbolic Govardhan Parvat made of 56 dishes (Annakut) offered with full ritual and bhajans.',
                'description' => 'Annual Govardhan Puja with cow worship, hill-shaped offerings of 56 dishes, and grand evening aarti.',
                'starts_at' => now()->addDays(90)->setTime(7, 0),
                'venue' => 'Gopal Seva Goshala',
                'status' => 'upcoming',
            ],
            [
                'title' => 'Monthly Cow Health Camp',
                'type' => 'seva',
                'short_description' => 'Free veterinary health camp for cows in the surrounding villages — vaccination, deworming, and treatment.',
                'description' => 'Held on the first Sunday of every month. Veterinary doctors and trained volunteers provide free vaccination and treatment to cows from nearby villages.',
                'starts_at' => now()->addDays(15)->setTime(8, 0),
                'venue' => 'Goshala Vet Clinic',
                'status' => 'upcoming',
            ],
        ];

        foreach ($events as $i => $data) {
            Event::updateOrCreate(
                ['slug' => Str::slug($data['title']) . '-demo'],
                $data + ['slug' => Str::slug($data['title']) . '-demo'],
            );
        }
    }
}
