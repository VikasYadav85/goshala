<?php

namespace Database\Seeders;

use App\Models\Cow;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Demo gallery albums (cover images use Unsplash CDN URLs via external_url-like setup not shown here — keep simple)
        $albums = [
            ['Daily Goshala Care', 'feeding', 'Photos of our daily feeding, cleaning and care routines.'],
            ['Rescue Missions 2025-26', 'rescue', 'Highway rescues, emergency calls and rehabilitation stories.'],
            ['Janmashtami 2025', 'events', 'Celebration of Lord Krishna\'s birth at the Goshala.'],
            ['Volunteer Stories', 'volunteers', 'Our volunteers from across India in seva.'],
        ];
        foreach ($albums as $i => [$name, $cat, $desc]) {
            GalleryAlbum::updateOrCreate(
                ['slug' => Str::slug($name) . '-demo'],
                ['name' => $name, 'category' => $cat, 'description' => $desc, 'is_published' => true, 'sort_order' => $i],
            );
        }

        // Demo donations (mix of statuses)
        if (! Donation::exists()) {
            $cat = DonationCategory::first();
            $cow = Cow::first();
            $donors = [
                ['Anita Sharma', 'anita@example.com', 1100, 'success'],
                ['Rajesh Khanna', 'rajesh@example.com', 5100, 'success'],
                ['Mukund Tiwari', 'mukund@example.com', 2100, 'success'],
                ['Vidhi Patel', 'vidhi@example.com', 11000, 'success'],
                ['Dheeraj Gupta', 'dheeraj@example.com', 501, 'pending'],
                ['Suresh Iyer', 'suresh@example.com', 21000, 'success'],
                ['Priya Mehta', 'priya@example.com', 2100, 'success'],
                ['Naveen Yadav', 'naveen@example.com', 1100, 'failed'],
            ];

            foreach ($donors as [$name, $email, $amt, $status]) {
                Donation::create([
                    'donation_category_id' => $cat?->id,
                    'cow_id' => rand(0, 1) ? $cow?->id : null,
                    'donor_name' => $name,
                    'donor_email' => $email,
                    'donor_phone' => '+91-9' . rand(100000000, 999999999),
                    'donor_country' => 'India',
                    'amount' => $amt,
                    'currency' => 'INR',
                    'frequency' => 'one_time',
                    'wants_80g_receipt' => true,
                    'payment_method' => 'razorpay',
                    'payment_status' => $status,
                    'paid_at' => $status === 'success' ? now()->subDays(rand(1, 60)) : null,
                ]);
            }
        }
    }
}
