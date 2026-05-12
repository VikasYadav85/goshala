<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['Gopal Das ji Maharaj', 'Founder & Chairman', 'trustee', 'Visionary founder of the Trust. Spent 30+ years in Gau Seva and Vedic studies.'],
            ['Smt. Radha Devi', 'Trustee & Secretary', 'trustee', 'Oversees daily operations and financial transparency.'],
            ['Shri Mukund Sharma', 'Trustee', 'trustee', 'Manages community outreach and partnerships.'],
            ['Pt. Suresh Joshi', 'Trustee & Treasurer', 'trustee', 'Chartered Accountant. Audits all finances and ensures compliance.'],

            ['Dr. Ramesh Iyer', 'Chief Veterinarian', 'veterinarian', 'BVSc & AH. Leads our on-site veterinary clinic and emergency response.'],
            ['Dr. Priya Mehta', 'Veterinarian', 'veterinarian', 'Specialises in calf care and surgical procedures.'],

            ['Anjali Verma', 'Volunteer Coordinator', 'team', 'Coordinates 200+ active volunteers across districts.'],
            ['Rakesh Yadav', 'Goshala Operations Lead', 'team', 'Oversees feeding, cleaning, and shed maintenance schedules.'],
            ['Naveen Tiwari', 'Communications Lead', 'team', 'Manages our digital presence and donor communications.'],
        ];

        foreach ($members as $i => [$name, $role, $group, $bio]) {
            TeamMember::updateOrCreate(
                ['name' => $name],
                ['role' => $role, 'group' => $group, 'bio' => $bio, 'is_published' => true, 'sort_order' => $i],
            );
        }
    }
}
