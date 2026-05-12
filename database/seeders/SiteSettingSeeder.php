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
            ['contact', 'contact_phone',    '+91-98765-43210', 'string',  'Phone',    'Primary phone shown across the site.'],
            ['contact', 'contact_email',    'seva@gopalsevatrust.org', 'string', 'Email',    'Primary email shown across the site.'],
            ['contact', 'contact_whatsapp', '+91-98765-43210', 'string',  'WhatsApp', 'WhatsApp number — used for the floating chat link.'],
            ['contact', 'contact_address',  'Gopal Seva Samarpan Trust, Vrindavan, Mathura, Uttar Pradesh - 281121', 'text', 'Address', 'Goshala address.'],

            // brand group
            ['brand', 'site_tagline',  'Serving Gau Mata with Devotion, Compassion & Humanity.', 'string', 'Site tagline', null],
            ['brand', 'footer_about',  'Gopal Seva Samarpan Trust is a sanctuary for rescued and abandoned cows, rooted in spiritual values and committed to transparency.', 'text', 'Footer about', null],

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
