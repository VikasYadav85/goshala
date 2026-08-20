<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // contact group
            ['contact', 'contact_phone',    '+91 75068 62607', 'string',  'Phone',    'Primary phone shown across the site.'],
            ['contact', 'contact_phone_2', '+91 8591300362', 'string',  'Phone (secondary)', 'Second phone shown near the address.'],
            ['contact', 'contact_email',    'indiabooks@gmail.com', 'string', 'Email',    'Primary email shown across the site.'],
            ['contact', 'contact_email_2', 'vy32353@gmail.com', 'string', 'Email (secondary)', 'Second email shown near the address.'],
            ['contact', 'contact_whatsapp', '+918591300362', 'string',  'WhatsApp', 'WhatsApp number — used for the floating chat link.'],
            ['contact', 'contact_address',  'Gram Kukudipur, Post Saidpur Gadaur, Tehsil Sadar, Jaunpur, Uttar Pradesh - 222001', 'text', 'Goshala address', 'Goshala / visiting address shown on the contact page and footer.'],
            ['contact', 'registered_office', 'A-002, Somnath Apartment, S.V. Cross Road, Chandresh Agency, Asha Nagar, Bhayandar East, Thane, Maharashtra - 401105', 'text', 'Registered office', 'Registered office address (from the trust deed) — shown on the contact page, footer, and 80G receipt.'],

            // brand group
            ['brand', 'site_tagline',  'Serving Gau Mata with Devotion, Compassion & Humanity.', 'string', 'Site tagline', null],
            ['brand', 'footer_about',  'Gopal Samarpan Sewa Charitable Trust is a sanctuary for rescued and abandoned cows, rooted in spiritual values and committed to transparency.', 'text', 'Footer about', null],

            // social group
            ['social', 'social_instagram', 'https://instagram.com/gopalsevatrust', 'string', 'Instagram URL', null],
            ['social', 'social_facebook',  'https://facebook.com/gopalsevatrust',  'string', 'Facebook URL',  null],
            ['social', 'social_youtube',   'https://youtube.com/@gopalsevatrust',  'string', 'YouTube URL',   null],
            ['social', 'social_twitter',   'https://twitter.com/gopalsevatrust',   'string', 'X / Twitter URL', null],

            // banking group
            ['banking', 'bank_name',     'HDFC Bank',                'string', 'Bank name', null],
            ['banking', 'bank_account',  '50100-XXX-XXX-XX',         'string', 'Account number', null],
            ['banking', 'bank_ifsc',     'HDFC0001234',              'string', 'IFSC code', null],
            ['banking', 'bank_upi',      'gopalseva@hdfcbank',       'string', 'UPI ID', null],
        ];

        foreach ($rows as $i => [$group, $key, $value, $type, $label, $description]) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $group,
                    'value' => $value,
                    'type' => $type,
                    'label' => $label,
                    'description' => $description,
                    'sort_order' => $i,
                ],
            );
        }

        SiteSetting::flushCache();
    }
}
