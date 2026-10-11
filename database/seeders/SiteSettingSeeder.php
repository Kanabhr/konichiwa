<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

/**
 * Seeds the site_settings table with the values that were previously
 * hardcoded in the Blade views.  Safe to run multiple times (upsert).
 */
class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Phones — stored with + and country code, no spaces
            'phone_primary'   => '+919993955051',
            'phone_secondary' => '',

            // Email
            'email'           => 'info@konichiwachemicals.com',

            // WhatsApp — digits only, with country code (used in wa.me links)
            'whatsapp_number' => '919993955051',

            // Addresses
            'address_reg'    => 'Innov8 Cybergreen, 5th Floor, Cyber Green Part 1, Sec-25, Gurugram, 122002, India',
            'address_region' => '173, 3rd Floor, PU4 Vijay Nagar, Near IndoThai Security, Indore (M.P.) 452010',

            // Google Maps embed src
            'map_embed_url'  => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3506.529614369104!2d77.09035807517458!3d28.493709390371922!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d19e280ef20f5%3A0xcdb80acc180fb7d8!2sInnov8%20DLF%20Cyber%20Green%20Tower%20C!5e0!3m2!1sen!2sin!4v1777284590890!5m2!1sen!2sin',

            // Social links (empty = hidden in templates)
            'facebook'   => '',
            'instagram'  => '',
            'youtube'    => '',
            'linkedin'   => '',
            'twitter'    => '',
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Bust any stale cache so the next request picks up these values.
        SiteSetting::clearCache();
    }
}
