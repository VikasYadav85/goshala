<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Rajesh Khanna', 'Donor', 'Mumbai', 'Seeing the dedication of the volunteers at Gopal Seva Samarpan Trust changed my perspective on charity. The transparency in how they use every rupee is commendable.'],
            ['Smt. Meera Devi', 'Devotee', 'Vrindavan', 'The peace I felt during the Gau Aarti at this Goshala is unmatched. Truly a spiritual sanctuary.'],
            ['Dr. Ramesh Iyer', 'Veterinary Volunteer', 'Mathura', 'I have volunteered at many goshalas, but the medical infrastructure here rivals professional veterinary clinics.'],
            ['Anita Sharma', 'Monthly Donor', 'Delhi', 'I sponsor a cow named Tulsi every month. The trust shares photos and care updates without fail. Real, transparent seva.'],
            ['Pandit Mukund Joshi', 'Spiritual Advisor', 'Vrindavan', 'A model goshala. Every cow looks healthy, every shelter is clean — exactly how seva should be.'],
            ['Suresh & Vidhi Patel', 'CSR Partner', 'Ahmedabad', 'Our company has partnered with the Trust for 3 years. Their reporting and impact metrics are world-class.'],
        ];

        foreach ($items as $i => [$name, $role, $loc, $quote]) {
            Testimonial::updateOrCreate(
                ['name' => $name],
                ['role' => $role, 'location' => $loc, 'quote' => $quote, 'rating' => 5, 'is_published' => true, 'is_featured' => $i < 3, 'sort_order' => $i],
            );
        }
    }
}
