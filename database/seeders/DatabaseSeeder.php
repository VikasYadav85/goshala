<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            SiteSettingSeeder::class,
            CowCategorySeeder::class,
            CowSeeder::class,
            DonationCategorySeeder::class,
            CampaignSeeder::class,
            EventSeeder::class,
            BlogContentSeeder::class,
            TestimonialSeeder::class,
            TeamMemberSeeder::class,
            FaqSeeder::class,
            ContentDemoSeeder::class,
        ]);
    }
}
