<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Narai Coder',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Website name'
            ],
            [
                'key' => 'site_description',
                'value' => 'Platform for coding education and tech events',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Website description'
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@naraicoder.com',
                'group' => 'contact',
                'type' => 'string',
                'description' => 'Contact email address'
            ],
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/naraicoder',
                'group' => 'social',
                'type' => 'json',
                'description' => 'Social media links'
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/naraicoder',
                'group' => 'social',
                'type' => 'json',
                'description' => 'Twitter profile URL'
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/naraicoder',
                'group' => 'social',
                'type' => 'json',
                'description' => 'Instagram profile URL'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
