<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    // ---------------------------------------------------------------------------
    // Hardcoded fallbacks — used when the DB row is missing or the field is empty.
    // These match the values that were previously hardcoded in the views.
    // ---------------------------------------------------------------------------
    public const DEFAULTS = [
       'phone_primary'    => '+91 99939 55051',
        'phone_secondary'  => '',
        'email'            => 'info@konichiwachemicals.com',
        'whatsapp_number'  => '919993955051',   // digits only, with country code
        'address_reg'      => 'Innov8 Cybergreen, 5th Floor, Cyber Green Part 1, Sec-25, Gurugram, 122002, India',
        'address_region'   => '173, 3rd Floor, PU4 Vijay Nagar, Near IndoThai Security, Indore (M.P.) 452010',
        'map_embed_url'    => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3506.529614369104!2d77.09035807517458!3d28.493709390371922!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d19e280ef20f5%3A0xcdb80acc180fb7d8!2sInnov8%20DLF%20Cyber%20Green%20Tower%20C!5e0!3m2!1sen!2sin!4v1777284590890!5m2!1sen!2sin',
        'facebook'         => '',
        'instagram'        => '',
        'youtube'          => '',
        'linkedin'         => '',
        'twitter'          => '',
    ];

            public const CACHE_KEY = 'site_settings_all';

    /** Per-request copy so the '*' view composer does not hit the cache for every view. */
    private static ?object $resolved = null;

    public static function allAsObject(): object
    {
        if (self::$resolved !== null) {
            return self::$resolved;
        }

        try {
            $data = Cache::rememberForever(
                self::CACHE_KEY,
                fn () => static::pluck('value', 'key')->all()
            );
        } catch (\Throwable $e) {
            // Table missing or DB down: use DEFAULTS and do not cache the failure.
            $data = [];
        }

        $merged = array_merge(
            self::DEFAULTS,
            array_filter((array) $data, fn ($v) => $v !== null && $v !== '')
        );

        // Cleaned values for tel: links (digits and a leading + only).
        $merged['phone_primary_tel']   = self::telValue($merged['phone_primary']);
        $merged['phone_secondary_tel'] = self::telValue($merged['phone_secondary']);

        return self::$resolved = (object) $merged;
    }

    private static function telValue(?string $phone): string
    {
        $phone  = (string) $phone;
        $digits = preg_replace('/\D+/', '', $phone);

        return ($digits !== '' && str_starts_with(ltrim($phone), '+')) ? '+' . $digits : $digits;
    }

    public static function saveAll(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        self::clearCache();
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        self::$resolved = null;
    }
}